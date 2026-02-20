<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\PodcastBooking;
use App\Models\User;
use App\Models\Role;
use App\Models\Kalender;
use App\Models\Staff;

class VerifikatorPodcastController extends Controller
{
    // public function __construct()
    // {
    //     // $this->middleware('auth:internal');
    //     $this->middleware(function ($request, $next) {
    //         if (!Auth::guard('internal')->user()->isVerifikatorPodcast()) {
    //             abort(403, 'Unauthorized access.');
    //         }
    //         return $next($request);
    //     });
    // }

    public function dashboard()
    {
        // dd( 'masuk');
        // $user = Auth::guard('internal')->user();
        
        $totalPodcasts = PodcastBooking::count();
        $pendingPodcasts = PodcastBooking::where('status_verifikasi', 'pending')->count();
        $approvedPodcasts = PodcastBooking::where('status_verifikasi', 'disetujui')->count();
        $rejectedPodcasts = PodcastBooking::where('status_verifikasi', 'ditolak')->count();
        $rescheduledPodcasts = PodcastBooking::where('status_verifikasi', 'penjadwalan ulang')->count();
        
        $recentPodcasts = PodcastBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('verifikator-podcast.dashboard', compact(
            'totalPodcasts', 
            'pendingPodcasts', 
            'approvedPodcasts', 
            'rejectedPodcasts',
            'rescheduledPodcasts',
            'recentPodcasts'
        ));
    }

    public function approval()
    {
        $podcasts = PodcastBooking::with(['user', 'verifikator'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('verifikator-podcast.approval', compact('podcasts'));
    }

    public function showApprovalForm($id)
    {
        $podcast = PodcastBooking::with('user')->findOrFail($id);
        
        // Get available time slots for this date (exclude already booked times)
        $bookedTimes = PodcastBooking::whereDate('tanggal', $podcast->tanggal)
            ->where('status_verifikasi', 'disetujui')
            ->where('id', '!=', $id) // Exclude current booking
            ->pluck('waktu')
            ->filter() // Remove null values
            ->toArray();
            
        // Define all possible time slots
        $allTimeSlots = [
            '08:00-10:00',
            '10:00-12:00', 
            '13:00-15:00',
            '15:00-17:00'
        ];
        
        $availableTimeSlots = array_diff($allTimeSlots, $bookedTimes);
        
        $hosts = Staff::where('role', 'host')->orderBy('nama')->get();
        return view('verifikator-podcast.approval-form', compact('podcast', 'availableTimeSlots', 'hosts'));
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,disetujui,ditolak,penjadwalan ulang',
            'host' => 'nullable|string|max:100',
            'waktu' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $podcast = PodcastBooking::findOrFail($id);
        $user = Auth::guard('internal')->user();

        // Jika status disetujui, wajib ada waktu dan host
        if ($request->status_verifikasi === 'disetujui') {
            if (!$request->waktu) {
                return back()->withErrors(['waktu' => 'Waktu wajib diisi untuk status disetujui.'])->withInput();
            }
            
            // Check if time slot is already taken
            $existingBooking = PodcastBooking::whereDate('tanggal', $podcast->tanggal)
                ->where('waktu', $request->waktu)
                ->where('status_verifikasi', 'disetujui')
                ->where('id', '!=', $id)
                ->exists();
                
            if ($existingBooking) {
                return back()->withErrors(['waktu' => 'Slot waktu ini sudah ditempati oleh booking lain.'])->withInput();
            }
        }

        $updateData = [
            'status_verifikasi' => $request->status_verifikasi,
            'id_verifikator' => $user->id_internal_user,
            'verifikasi' => $user->nama_user,
            'catatan' => $request->catatan,
        ];

        // Only update host and waktu if provided
        if ($request->host) {
            $updateData['host'] = $request->host;
        }
        
        if ($request->waktu) {
            $updateData['waktu'] = $request->waktu;
        }

        $podcast->update($updateData);

        return redirect()->route('verifikator-podcast.approval')
            ->with('success', 'Status podcast berhasil diperbarui.');
    }

    public function report()
    {
        $podcasts = PodcastBooking::with(['user', 'verifikator'])
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return view('verifikator-podcast.report', compact('podcasts'));
    }

        public function uploadCover(Request $request, $id)
        {
        $request->validate([
                'cover' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048'
            ]);

            $podcast = PodcastBooking::findOrFail($id);

            // hapus cover lama (kalau ada)
            if ($podcast->cover_path) {
                Storage::disk('public')->delete($podcast->cover_path);
            }

            /// simpan ke storage/app/public/cover-podcast
            $path = $request->file('cover')
                            ->store('cover-podcast', 'public');

            $podcast->update([
                'cover_path' => $path,
            ]);

            return back()->with('success', 'Cover podcast berhasil diunggah.');
        }

        public function deleteCover($id)
        {
            $podcast = PodcastBooking::findOrFail($id);

            if ($podcast->cover_path) {
                Storage::disk('public')->delete($podcast->cover_path);
                $podcast->update([
                    'cover_path' => null,
                ]);
                return back()->with('success', 'Cover podcast berhasil dihapus.');
            }

            return back()->with('error', 'Cover podcast tidak ditemukan.');
        }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak,penjadwalan ulang',
            'host' => 'nullable|string|max:100',
            'waktu' => 'nullable|string|max:50',
            'catatan' => 'nullable|string|max:500',
        ]);

        $podcast = PodcastBooking::findOrFail($id);
        
        $updateData = [
            'status_verifikasi' => $request->status_verifikasi,
            'verifikasi' => Auth::user()->nama_user,
            'id_verifikator' => Auth::id(),
            'catatan' => $request->catatan,
        ];

        if ($request->filled('host')) {
            $updateData['host'] = $request->host;
        }

        if ($request->filled('waktu')) {
            $updateData['waktu'] = $request->waktu;
        }

        $podcast->update($updateData);

        // Create kalender entry if approved
        if ($request->status_verifikasi == 'disetujui') {
            $kalender = Kalender::updateOrCreate(
                [
                    'tanggal_kalender' => $podcast->tanggal,
                    'jenis_agenda' => 'podcast',
                    'id_agenda' => $podcast->id,
                ],
                [
                    'waktu' => $request->waktu ?? '13:00-16:00',
                    'sudah_dibooking' => true,
                ]
            );
            
            $podcast->update(['id_kalender' => $kalender->id_kalender]);
        }

        return back()->with('success', 'Status podcast berhasil diperbarui.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PodcastBooking;
use App\Models\User;
use App\Models\Role;
use App\Models\Kalender;

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
        
        return view('verifikator-podcast.approval-form', compact('podcast', 'availableTimeSlots'));
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
}
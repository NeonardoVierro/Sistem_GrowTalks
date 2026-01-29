<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoachingBooking;
use App\Models\User;
use Carbon\Carbon;

class VerifikatorCoachingController extends Controller
{
    public function dashboard()
    {
        $totalCoachings = CoachingBooking::count();
        $pendingCoachings = CoachingBooking::where('status_verifikasi', 'pending')->count();
        $approvedCoachings = CoachingBooking::where('status_verifikasi', 'disetujui')->count();
        $rejectedCoachings = CoachingBooking::where('status_verifikasi', 'ditolak')->count();
        
        $recentCoachings = CoachingBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('verifikator-coaching.dashboard', compact(
            'totalCoachings', 
            'pendingCoachings', 
            'approvedCoachings', 
            'rejectedCoachings',
            'recentCoachings'
        ));
    }
    
    public function approval(Request $request)
    {
        $status = $request->get('status', 'all');
        $search = $request->get('search');
        
        $query = CoachingBooking::with('user');
        
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('keterangan', 'like', "%{$search}%")
                ->orWhere('layanan', 'like', "%{$search}%")
                ->orWhere('pic', 'like', "%{$search}%")
                ->orWhereHas('user', function($userQuery) use ($search) {
                    $userQuery->where('nama_opd', 'like', "%{$search}%");
                });
            });
    }
    
    if ($status !== 'all') {
        $query->where('status_verifikasi', $status);
    }
    
    $coachings = $query->orderBy('created_at', 'desc')
        ->paginate(15);
    
    // Hitung jumlah untuk setiap tab
    $allCount = CoachingBooking::count();
    $pendingCount = CoachingBooking::where('status_verifikasi', 'pending')->count();
    $approvedCount = CoachingBooking::where('status_verifikasi', 'disetujui')->count();
    $rejectedCount = CoachingBooking::where('status_verifikasi', 'ditolak')->count();
    $rescheduledCount = CoachingBooking::where('status_verifikasi', 'penjadwalan ulang')->count();
    
    return view('verifikator-coaching.approval', compact(
                                                            'coachings', 
                                                            'status', 
                                                            'search',
                                                            'allCount',
                                                            'pendingCount',
                                                            'approvedCount',
                                                            'rejectedCount',
                                                            'rescheduledCount'
                                                        ));
    }

    
    public function approvalForm($id)
    {
        $coaching = CoachingBooking::with('user')->findOrFail($id);
        
        // Available time slots for coaching (contoh)
        $availableTimeSlots = [
            '09:00 - 10:00',
            '10:30 - 11:30',
            '13:00 - 14:00',
            '14:30 - 15:30',
        ];
        
        return view('verifikator-coaching.approval-form', compact('coaching', 'availableTimeSlots'));
    }

    // Route compatibility: some routes call `showApprovalForm`
    public function showApprovalForm($id)
    {
        return $this->approvalForm($id);
    }
    
    public function updateApproval(Request $request, $id)
    {
        $validated = $request->validate([
            'status_verifikasi' => 'required|in:pending,disetujui,ditolak,penjadwalan ulang',
            'coach' => 'nullable|string|max:100',
            'waktu' => 'nullable|string|max:50',
            'catatan' => 'nullable|string',
        ]);
        
        $coaching = CoachingBooking::findOrFail($id);
        
        // Jika disetujui, pastikan waktu dan coach diisi
        if ($validated['status_verifikasi'] == 'disetujui') {
            if (empty($validated['waktu'])) {
                return back()->withErrors(['waktu' => 'Waktu harus diisi untuk coaching yang disetujui.']);
            }
        }
        
        // Update data coaching
        $coaching->update([
            'status_verifikasi' => $validated['status_verifikasi'],
            'coach' => $validated['coach'] ?? null,
            'waktu' => $validated['waktu'] ?? null,
            'catatan' => $validated['catatan'] ?? null,
            'id_verifikator' => auth()->id(),
            'verifikasi' => Carbon::now(),
        ]);
        
        // Jika disetujui, buat entry di kalender
        if ($validated['status_verifikasi'] == 'disetujui') {
            $this->createCalendarEntry($coaching);
        }
        
        return redirect()->route('verifikator-coaching.approval')
            ->with('success', 'Status coaching berhasil diperbarui.');
    }
    
    private function createCalendarEntry($coaching)
    {
        // Pastikan belum ada entry kalender untuk coaching ini
        if (!$coaching->kalender) {
            // Normalize waktu: kalender.waktu is a TIME column, accept single time like '07:00:00'
            $waktuValue = null;
            if (!empty($coaching->waktu)) {
                // Try to extract the first time-like substring (e.g. '07.00' or '07:00')
                if (preg_match('/(\d{1,2}[:.]\d{2})/', $coaching->waktu, $m)) {
                    $start = str_replace('.', ':', $m[1]);
                    // Ensure seconds
                    if (preg_match('/^\d{1,2}:\d{2}$/', $start)) {
                        $waktuValue = $start . ':00';
                    }
                }
            }

            $kalender = \App\Models\Kalender::create([
                'tanggal_kalender' => $coaching->tanggal,
                'sudah_dibooking' => true,
                'jenis_agenda' => 'coaching',
                'judul' => $coaching->layanan,
                'keterangan' => $coaching->keterangan,
                'waktu' => $waktuValue,
            ]);
            
            $coaching->update(['id_kalender' => $kalender->id]);
        }
    }
    
    public function report(Request $request)
    {
        $startDate = $request->get('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));
        
        $reportData = CoachingBooking::with(['user', 'verifikator'])
            ->whereBetween('tanggal', [$startDate, $endDate])
            ->orderBy('tanggal', 'asc')
            ->get()
            ->groupBy(function($item) {
                return $item->tanggal->format('Y-m-d');
            });
        
        $stats = [
            'total' => CoachingBooking::whereBetween('tanggal', [$startDate, $endDate])->count(),
            'approved' => CoachingBooking::whereBetween('tanggal', [$startDate, $endDate])
                ->where('status_verifikasi', 'disetujui')->count(),
            'pending' => CoachingBooking::whereBetween('tanggal', [$startDate, $endDate])
                ->where('status_verifikasi', 'pending')->count(),
            'rejected' => CoachingBooking::whereBetween('tanggal', [$startDate, $endDate])
                ->where('status_verifikasi', 'ditolak')->count(),
        ];
        
        return view('verifikator-coaching.report', compact('reportData', 'stats', 'startDate', 'endDate'));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\CoachingBooking;
use App\Models\User;
use App\Models\Staff;
use Carbon\Carbon;

class VerifikatorCoachingController extends Controller
{
    public function dashboard(Request $request)
    {
        $totalCoachings = CoachingBooking::count();
        $pendingCoachings = CoachingBooking::where('status_verifikasi', 'pending')->count();
        $approvedCoachings = CoachingBooking::where('status_verifikasi', 'disetujui')->count();
        $rejectedCoachings = CoachingBooking::where('status_verifikasi', 'ditolak')->count();
        
        $recentCoachings = CoachingBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        // Kalender params
        $month = $request->get('month', date('m'));
        $year  = $request->get('year', date('Y'));

        $startOfMonth = Carbon::create($year, $month, 1)->startOfMonth();
        $endOfMonth   = Carbon::create($year, $month, 1)->endOfMonth();

        // Ambil bookings di bulan tersebut
        $coachings = CoachingBooking::whereBetween('tanggal', [$startOfMonth, $endOfMonth])
            ->orderBy('tanggal')
            ->get()
            ->groupBy(function ($item) {
                return Carbon::parse($item->tanggal)->format('Y-m-d');
            });

        // Buat struktur kalender (awal minggu Senin - akhir Minggu)
        $calendar = [];
        $currentDate = $startOfMonth->copy()->startOfWeek(Carbon::MONDAY);
        $endOfCalendar = $endOfMonth->copy()->endOfWeek(Carbon::SUNDAY);

        while ($currentDate <= $endOfCalendar) {
            $week = [];
            for ($i = 0; $i < 7; $i++) {
                $week[] = [
                    'date' => $currentDate->copy(),
                    'date_string' => $currentDate->format('Y-m-d'),
                    'day' => $currentDate->day,
                    'is_current_month' => $currentDate->month == $month,
                    // Available days for coaching (example: Rabu dan Jumat)
                    'is_available_day' => in_array($currentDate->dayOfWeek, [Carbon::WEDNESDAY, Carbon::FRIDAY]),
                ];
                $currentDate->addDay();
            }
            $calendar[] = $week;
        }

        return view('verifikator-coaching.dashboard', compact(
            'totalCoachings', 
            'pendingCoachings', 
            'approvedCoachings', 
            'rejectedCoachings',
            'recentCoachings',
            'month',
            'year',
            'calendar'
        ))->with('bookings', $coachings);
    }

    /**
     * AJAX: get bookings by date for modal
     */
    public function getBookingsByDate(Request $request)
    {
        $date = $request->get('date');
        if (!$date) {
            return response()->json(['success' => false, 'message' => 'Date required']);
        }

        $coachings = CoachingBooking::whereDate('tanggal', $date)->orderBy('waktu')->get();

        $data = $coachings->map(function ($c) {
            return [
                'id' => $c->id,
                'kode' => 'CCA-' . $c->tanggal->format('Ymd') . $c->id,
                'status' => $c->status_verifikasi,
                'instansi' => $c->nama_opd ?? $c->instansi ?? '-',
                'layanan' => $c->layanan,
                'agenda' => $c->keterangan,
                'pic' => $c->pic,
                'no_telp' => $c->no_telp,
                'waktu' => $c->waktu ?? 'Akan ditentukan',
                'coach' => $c->coach ?? '-',
                'catatan' => $c->catatan,
            ];
        })->toArray();

        return response()->json(['success' => true, 'bookings' => $data]);
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
        
        $coaches = Staff::where('role', 'coach')->orderBy('nama')->get();
        return view('verifikator-coaching.approval-form', compact('coaching', 'availableTimeSlots', 'coaches'));
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
        
    $query = CoachingBooking::with('user')
        ->where('status_verifikasi', 'disetujui');

    // Filter pencarian
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function ($q) use ($search) {
            $q->where('keterangan', 'like', "%{$search}%")
              ->orWhere('layanan', 'like', "%{$search}%")
              ->orWhere('coach', 'like', "%{$search}%")
              ->orWhereHas('user', function ($u) use ($search) {
                  $u->where('nama_opd', 'like', "%{$search}%");
              });
        });
    }

    // Filter status (optional, default disetujui)
    if ($request->filled('status')) {
        $query->where('status_verifikasi', $request->status);
    }

    // Filter tahun
    if ($request->filled('year')) {
        $query->whereYear('tanggal', $request->year);
    }

    $coachings = $query
        ->orderBy('tanggal', 'desc')
        ->get();

    // Statistik (optional, buat dashboard laporan)
    $stats = [
        'total'     => CoachingBooking::count(),
        'approved'  => CoachingBooking::where('status_verifikasi', 'disetujui')->count(),
        'pending'   => CoachingBooking::where('status_verifikasi', 'pending')->count(),
        'rejected'  => CoachingBooking::where('status_verifikasi', 'ditolak')->count(),
    ];
        
        return view('verifikator-coaching.report', compact('coachings', 'stats', 'startDate', 'endDate'));
    }

    public function uploadDokumentasi(Request $request, $id)
    {
        $request->validate([
            'dokumentasi' => 'required|image|max:2048',
        ]);

        $coaching = CoachingBooking::findOrFail($id);

        // kalau sudah ada dokumentasi, hapus dulu (edit / replace)
        if ($coaching->dokumentasi_path) {
            Storage::disk('public')->delete($coaching->dokumentasi_path);
        }

        // simpan file baru
        $path = $request->file('dokumentasi')
                        ->store('dokumentasi-coaching', 'public');

        // update ke database
        $coaching->update([
            'dokumentasi_path' => $path,
        ]);

        return back()->with('success', 'Dokumentasi berhasil diunggah.');

    }

    public function deleteDokumentasi($id)
    {
        $coaching = CoachingBooking::findOrFail($id);

        if ($coaching->dokumentasi_path) {
            Storage::disk('public')->delete($coaching->dokumentasi_path);
            $coaching->update([
                'dokumentasi_path' => null,
            ]);
            return back()->with('success', 'Dokumentasi berhasil dihapus.');
        }

        return back()->with('error', 'Dokumentasi tidak ditemukan.');
    }
}
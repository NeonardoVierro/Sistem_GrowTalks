<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoachingBooking;
use App\Models\Kalender;
use Carbon\Carbon;

class CoachingController extends Controller
{
    public function index(Request $request)
    {
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        if ($month < 1 || $month > 12) {
            $month = date('m');
        }
        if ($year < 2024 || $year > 2030) {
            $year = date('Y');
        }

        $bookings = CoachingBooking::where('id_user', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->with('kalender')
            ->orderBy('created_at', 'desc')
            ->paginate(8);
            
        $approvedBookings = CoachingBooking::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->approved()
            ->get()
            ->groupBy(function ($b) { return $b->tanggal->format('Y-m-d'); })
            ->map(function ($bookings) {
                return $bookings->map(function ($booking) {
                    return [
                        'id' => $booking->id,
                        'user_id' => $booking->id_user,
                        'waktu' => $booking->waktu,
                        'layanan' => $booking->layanan,
                        'agenda' => $booking->keterangan,
                        'pic' => $booking->pic,
                        'is_mine' => $booking->id_user == Auth::id()
                    ];
                });
            });

        $userApprovedBookings = CoachingBooking::where('id_user', Auth::id())
            ->where('status_verifikasi', 'disetujui')
            ->get()
            ->keyBy('tanggal');    
        
        $calendar = $this->generateCalendar($month, $year);
        
        return view('coaching.index', compact('bookings', 'calendar', 'month', 'year', 'approvedBookings', 'userApprovedBookings'));
    }

    private function generateCalendar($month, $year)
    {
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = Carbon::create($year, $month, 1)->endOfMonth();
        
        $calendar = [];
        $currentWeek = [];
        
        $startDay = $firstDay->copy()->startOfWeek(Carbon::MONDAY);
        
        while ($startDay->lt($firstDay)) {
            $currentWeek[] = [
                'day' => $startDay->day,
                'is_current_month' => false,
                'date' => $startDay->copy(),
                'is_wednesday' => $startDay->isWednesday(),
                'is_friday' => $startDay->isFriday(),
            ];
            $startDay->addDay();
        }
        
        $currentDate = $firstDay->copy();
        while ($currentDate->lte($lastDay)) {
            if (count($currentWeek) === 7) {
                $calendar[] = $currentWeek;
                $currentWeek = [];
            }
            
            $currentWeek[] = [
                'day' => $currentDate->day,
                'is_current_month' => true,
                'date' => $currentDate->copy(),
                'is_wednesday' => $currentDate->isWednesday(),
                'is_friday' => $currentDate->isFriday(),
                'date_string' => $currentDate->format('Y-m-d'),
            ];
            
            $currentDate->addDay();
        }
        
        $endDay = $lastDay->copy()->endOfWeek(Carbon::SUNDAY);
        $currentDate = $lastDay->copy()->addDay();
        while ($currentDate->lte($endDay)) {
            if (count($currentWeek) === 7) {
                $calendar[] = $currentWeek;
                $currentWeek = [];
            }
            
            $currentWeek[] = [
                'day' => $currentDate->day,
                'is_current_month' => false,
                'date' => $currentDate->copy(),
                'is_wednesday' => $currentDate->isWednesday(),
                'is_friday' => $currentDate->isFriday(),
            ];
            
            $currentDate->addDay();
        }
        
        if (!empty($currentWeek)) {
            $calendar[] = $currentWeek;
        }
        
        return $calendar;
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'layanan' => 'required|string|max:100',
            'instansi' => 'required|string|max:150',
            'keterangan' => 'required|string|max:200',
            'pic' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'persetujuan' => 'required|accepted',
        ]);

        $date = Carbon::parse($validated['tanggal']);

        if (!in_array($date->dayOfWeek, [Carbon::WEDNESDAY, Carbon::FRIDAY])) {
            return back()->withErrors(['tanggal' => 'Coaching clinic hanya bisa diajukan pada hari Rabu atau Jumat.'])->withInput();
        }

        if ($date->lt(Carbon::today())) {
            return back()->withErrors(['tanggal' => 'Tidak bisa membooking tanggal yang sudah lewat.'])->withInput();
        }

        $userPendingBooking = CoachingBooking::where('id_user', Auth::id())
            ->whereDate('tanggal', $validated['tanggal'])
            ->pending()
            ->exists();
            
        if ($userPendingBooking) {
            return back()->withErrors(['tanggal' => 'Anda sudah memiliki pengajuan pending pada tanggal ini.'])->withInput();
        }

        $existingBookingsCount = CoachingBooking::whereDate('tanggal', $validated['tanggal'])
            ->approved()
            ->count();
            
        if ($existingBookingsCount >= 3) {
            return back()->withErrors(['tanggal' => 'Kuota coaching untuk tanggal ini sudah penuh.'])->withInput();
        }

        $booking = CoachingBooking::create([
            'id_user' => Auth::id(),
            'tanggal' => $validated['tanggal'],
            'layanan' => $validated['layanan'],
            'nama_opd' => $validated['instansi'],
            'keterangan' => $validated['keterangan'],
            'pic' => $validated['pic'],
            'no_telp' => $validated['no_telp'],
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('coaching.index')
            ->with('success', 'Pengajuan coaching clinic berhasil dikirim! Verifikator akan meninjau pengajuan Anda.');
    }

    public function detail($date)
    {
        $dateObj = Carbon::parse($date);

        $bookings = CoachingBooking::whereDate('tanggal', $dateObj)
            ->orderBy('status_verifikasi', 'desc')
            ->get()
            ->map(function ($b) {
                return [
                    'id' => $b->id,
                    'id_user' => $b->id_user,
                    'layanan' => $b->layanan,
                    'keterangan' => $b->keterangan,
                    'instansi' => $b->nama_opd,
                    'pic' => $b->pic,
                    'no_telp' => $b->no_telp,
                    'status' => $b->status_verifikasi,
                    'waktu' => $b->waktu,
                    'coach' => $b->coach,
                    'catatan' => $b->catatan,
                    'is_mine' => $b->id_user == auth()->id(),
                ];
            });
        $approvedCount = CoachingBooking::whereDate('tanggal', $dateObj)
            ->where('status_verifikasi', 'disetujui')
            ->count();

        $userPending = CoachingBooking::where('id_user', auth()->id())
            ->whereDate('tanggal', $dateObj)
            ->pending()
            ->exists();

        $isAllowedDay = in_array($dateObj->dayOfWeek, [Carbon::WEDNESDAY, Carbon::FRIDAY]);
        $isPast = $dateObj->lt(Carbon::today());

        $canBook = !$isPast && $isAllowedDay && !$userPending && ($approvedCount < 3);

        return response()->json([
            'success' => true,
            'date' => $dateObj->format('Y-m-d'),
            'display_date' => $dateObj->locale('id')->isoFormat('D MMMM YYYY'),
            'bookings' => $bookings,
            'approved_count' => $approvedCount,
            'can_book' => $canBook,
        ]);
    }

    public function destroy($id)
    {
        $booking = CoachingBooking::findOrFail($id);
        
        if ($booking->id_user != Auth::id()) {
            abort(403);
        }
        
        if ($booking->status_verifikasi !== 'pending') {
            return redirect()->route('coaching.index')
                ->with('error', 'Hanya pengajuan pending yang bisa dihapus.');
        }
        
        if ($booking->kalender) {
            $booking->kalender->delete();
        }
        
        $booking->delete();
        
        return redirect()->route('coaching.index')
            ->with('success', 'Pengajuan berhasil dihapus!');
    }
}
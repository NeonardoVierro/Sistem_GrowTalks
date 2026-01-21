<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PodcastBooking;
use App\Models\Kalender;
use Carbon\Carbon;

class PodcastController extends Controller
{
    public function index(Request $request)
    {
        // Ambil parameter bulan dan tahun
        $month = $request->get('month', date('m'));
        $year = $request->get('year', date('Y'));

        // Validasi bulan (1-12)
        if ($month < 1 || $month > 12) {
            $month = date('m');
        }
        if ($year < 2024 || $year > 2030) {
            $year = date('Y');
        }

        // Get bookings for current user
        $bookings = PodcastBooking::where('id_user', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->with('kalender')
            ->orderBy('created_at', 'desc')
            ->with('kalender')
            ->paginate(8);
            
        // Get all approved podcast bookings for the selected month (untuk ditampilkan di kalender)
        $approvedBookings = PodcastBooking::whereYear('tanggal', $year)
            ->whereMonth('tanggal', $month)
            ->approved()
            ->get()
            ->groupBy('tanggal')
            ->map(function ($bookings) {
                return $bookings->map(function ($booking) {
                    return [
                        'id' => $booking->id_podcast,
                        'user_id' => $booking->id_user,
                        'waktu' => $booking->waktu,
                        'judul' => $booking->keterangan,
                        'narasumber' => $booking->narasumber,
                        'opd' => $booking->nama_opd,
                        'host' => $booking->host,
                        'is_mine' => $booking->id_user == Auth::id()
                    ];
                });
            });
        
        // Generate calendar
        $calendar = $this->generateCalendar($month, $year);
        
        return view('podcast.index', compact('bookings', 'calendar', 'month', 'year', 'approvedBookings'));
    }

    private function generateCalendar($month, $year)
    {
        $firstDay = Carbon::create($year, $month, 1);
        $lastDay = Carbon::create($year, $month, 1)->endOfMonth();
        
        $calendar = [];
        $currentWeek = [];
        
        // Mulai dari Senin
        $startDay = $firstDay->copy()->startOfWeek(Carbon::MONDAY);
        
        // Tambah hari-hari dari bulan sebelumnya
        while ($startDay->lt($firstDay)) {
            $currentWeek[] = [
                'day' => $startDay->day,
                'is_current_month' => false,
                'date' => $startDay->copy(),
                'is_friday' => $startDay->isFriday(),
            ];
            $startDay->addDay();
        }
        
        // Tambah hari-hari dari bulan ini
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
                'is_friday' => $currentDate->isFriday(),
                'date_string' => $currentDate->format('Y-m-d'),
            ];
            
            $currentDate->addDay();
        }
        
        // Tambah hari-hari dari bulan berikutnya
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
            'keterangan' => 'required|string|max:100',
            'narasumber' => 'required|string|max:100',
            'persetujuan' => 'required|accepted',
        ]);

        $date = Carbon::parse($validated['tanggal']);

        // Check if it's a Friday
        if ($date->dayOfWeek !== Carbon::FRIDAY) {
            return back()->withErrors(['tanggal' => 'Podcast hanya bisa diajukan pada hari Jumat.'])->withInput();
        }

        // Check if date is in the past
        if ($date->lt(Carbon::today())) {
            return back()->withErrors(['tanggal' => 'Tidak bisa membooking tanggal yang sudah lewat.'])->withInput();
        }

        // // Check if time slot is already booked
        // $existingBooking = PodcastBooking::whereDate('tanggal', $validated['tanggal'])
        //     ->where('waktu', $validated['waktu'])
        //     ->where('status_verifikasi', 'disetujui')
        //     ->exists();
            
        // if ($existingBooking) {
        //     return back()->withErrors(['waktu' => 'Slot waktu tersebut sudah dibooking.']);
        // }

        // Check if user already has a pending booking on the same date
        $userPendingBooking = PodcastBooking::where('id_user', Auth::id())
            ->whereDate('tanggal', $validated['tanggal'])
            ->pending()
            ->exists();
            
        if ($userPendingBooking) {
            return back()->withErrors(['tanggal' => 'Anda sudah memiliki pengajuan pending pada tanggal ini.'])->withInput();
        }

        // Create podcast booking
        $booking = PodcastBooking::create([
            'id_user' => Auth::id(),
            'tanggal' => $validated['tanggal'],
            'nama_opd' => Auth::user()->nama_opd,
            'nama_pic' => Auth::user()->nama_pic,
            'keterangan' => $validated['keterangan'],
            'narasumber' => $validated['narasumber'],
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('podcast.index')
            ->with('success', 'Pengajuan podcast berhasil dikirim! Verifikator akan meninjau pengajuan Anda.');
    }

    public function destroy($id)
    {
        $booking = PodcastBooking::findOrFail($id);
        
        if ($booking->id_user != Auth::id()) {
            abort(403);
        }
        
        // Only allow deletion if still pending
        if ($booking->status_verifikasi !== 'pending') {
            return redirect()->route('podcast.index')
                ->with('error', 'Hanya pengajuan pending yang bisa dihapus.');
        }
        
        // Delete kalender entry
        if ($booking->kalender) {
            $booking->kalender->delete();
        }
        
        $booking->delete();
        
        return redirect()->route('podcast.index')
            ->with('success', 'Pengajuan berhasil dihapus!');
    }
}
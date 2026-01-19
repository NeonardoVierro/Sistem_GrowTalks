<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoachingBooking;
use App\Models\Kalender;
use Carbon\Carbon;

class CoachingController extends Controller
{
    public function index()
    {
        $bookings = CoachingBooking::where('id_user', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->with('kalender')
            ->get();
            
        return view('coaching.index', compact('bookings'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'layanan' => 'required|string|max:100',
            'keterangan' => 'required|string',
            'pic' => 'required|string|max:100',
            'no_telp' => 'required|string|max:20',
            'persetujuan' => 'required|accepted',
        ]);

        // Check if it's Wednesday or Friday
        $date = Carbon::parse($validated['tanggal']);
        if (!in_array($date->dayOfWeek, [Carbon::WEDNESDAY, Carbon::FRIDAY])) {
            return back()->withErrors(['tanggal' => 'Coaching clinic hanya bisa diajukan pada hari Rabu atau Jumat.']);
        }

        // Check if date is already booked
        $existingBooking = CoachingBooking::whereDate('tanggal', $validated['tanggal'])->exists();
        if ($existingBooking) {
            return back()->withErrors(['tanggal' => 'Tanggal tersebut sudah dibooking.']);
        }

        // Create kalender entry
        $kalender = Kalender::create([
            'tanggal_kalender' => $validated['tanggal'],
            'sudah_dibooking' => true,
            'jenis_agenda' => 'coaching',
        ]);

        // Create coaching booking
        $booking = CoachingBooking::create([
            'id_user' => Auth::id(),
            'id_kalender' => $kalender->id_kalender,
            'tanggal' => $validated['tanggal'],
            'layanan' => $validated['layanan'],
            'keterangan' => $validated['keterangan'],
            'pic' => $validated['pic'],
            'no_telp' => $validated['no_telp'],
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('coaching.index')
            ->with('success', 'Pengajuan coaching clinic berhasil dikirim!');
    }

    public function destroy($id)
    {
        $booking = CoachingBooking::findOrFail($id);
        
        if ($booking->id_user != Auth::id()) {
            abort(403);
        }
        
        // Delete kalender entry
        if ($booking->kalender) {
            $booking->kalender->delete();
        }
        
        $booking->delete();
        
        return redirect()->route('coaching.index')
            ->with('success', 'Pengajuan berhasil dihapus!');
    }
}
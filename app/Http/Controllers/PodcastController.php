<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PodcastBooking;
use App\Models\Kalender;
use Carbon\Carbon;

class PodcastController extends Controller
{
    public function index()
    {
        $bookings = PodcastBooking::where('id_user', Auth::id())
            ->orderBy('tanggal', 'desc')
            ->with('kalender')
            ->get();
            
        return view('podcast.index', compact('bookings'));
    }

    public function submit(Request $request)
    {
        $validated = $request->validate([
            'tanggal' => 'required|date',
            'keterangan' => 'required|string|max:100',
            'narasumber' => 'required|string|max:100',
            'persetujuan' => 'required|accepted',
        ]);

        // Check if it's a Friday
        $date = Carbon::parse($validated['tanggal']);
        if ($date->dayOfWeek !== Carbon::FRIDAY) {
            return back()->withErrors(['tanggal' => 'Podcast hanya bisa diajukan pada hari Jumat.']);
        }

        // Check if date is already booked
        $existingBooking = PodcastBooking::whereDate('tanggal', $validated['tanggal'])->exists();
        if ($existingBooking) {
            return back()->withErrors(['tanggal' => 'Tanggal tersebut sudah dibooking.']);
        }

        // Create kalender entry
        $kalender = Kalender::create([
            'tanggal_kalender' => $validated['tanggal'],
            'sudah_dibooking' => true,
            'jenis_agenda' => 'podcast',
        ]);

        // Create podcast booking
        $booking = PodcastBooking::create([
            'id_user' => Auth::id(),
            'id_kalender' => $kalender->id_kalender,
            'tanggal' => $validated['tanggal'],
            'nama_opd' => Auth::user()->nama_opd,
            'nama_pic' => Auth::user()->nama_pic,
            'keterangan' => $validated['keterangan'],
            'narasumber' => $validated['narasumber'],
            'status_verifikasi' => 'pending',
        ]);

        return redirect()->route('podcast.index')
            ->with('success', 'Pengajuan podcast berhasil dikirim!');
    }

    public function destroy($id)
    {
        $booking = PodcastBooking::findOrFail($id);
        
        if ($booking->id_user != Auth::id()) {
            abort(403);
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
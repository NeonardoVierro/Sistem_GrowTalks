<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\PodcastBooking;
use App\Models\CoachingBooking;
use App\Models\InternalUser;

class AdminController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:admin');
    // }

    public function dashboard()
    {
        $stats = [
            'total_users' => User::count(),
            'total_podcasts' => PodcastBooking::count(),
            'total_coachings' => CoachingBooking::count(),
            'podcast_pending' => PodcastBooking::where('status_verifikasi', 'pending')->count(),
            'podcast_approved' => PodcastBooking::where('status_verifikasi', 'disetujui')->count(),
            'podcast_rejected' => PodcastBooking::where('status_verifikasi', 'ditolak')->count(),
            'coaching_pending' => CoachingBooking::where('status_verifikasi', 'pending')->count(),
            'coaching_approved' => CoachingBooking::where('status_verifikasi', 'disetujui')->count(),
            'coaching_rejected' => CoachingBooking::where('status_verifikasi', 'ditolak')->count(),
        ];

        $recentPodcasts = PodcastBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $recentCoachings = CoachingBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentPodcasts', 'recentCoachings'));
    }

    public function users()
    {
        $users = User::with('role')->get();
        return view('admin.users.index', compact('users'));
    }

    public function podcasts()
    {
        $podcasts = PodcastBooking::with(['user', 'kalender'])
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.podcast.index', compact('podcasts'));
    }

    public function coachings()
    {
        $coachings = CoachingBooking::with(['user', 'kalender'])
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.coaching.index', compact('coachings'));
    }

    public function updatePodcastStatus(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,disetujui,ditolak',
            'host' => 'nullable|string|max:100',
            'waktu' => 'nullable|date_format:H:i',
        ]);

        $podcast = PodcastBooking::findOrFail($id);
        
        $podcast->update([
            'status_verifikasi' => $request->status,
            'host' => $request->host,
            'verifikasi' => auth()->guard('admin')->user()->nama_user,
        ]);

        // Update kalender waktu jika ada
        if ($podcast->kalender && $request->waktu) {
            $podcast->kalender->update(['waktu' => $request->waktu]);
        }

        return back()->with('success', 'Status podcast berhasil diperbarui.');
    }

    public function updateCoachingStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,disetujui,ditolak',
        ]);

        $coaching = CoachingBooking::findOrFail($id);
        
        $coaching->update([
            'status_verifikasi' => $request->status,
            'verifikasi' => auth()->guard('admin')->user()->nama_user,
        ]);

        return back()->with('success', 'Status coaching clinic berhasil diperbarui.');
    }

    public function reportPodcast()
    {
        $podcasts = PodcastBooking::with('user')
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.reports.podcast', compact('podcasts'));
    }

    public function reportCoaching()
    {
        $coachings = CoachingBooking::with('user')
            ->orderBy('tanggal', 'desc')
            ->get();
        return view('admin.reports.coaching', compact('coachings'));
    }
}
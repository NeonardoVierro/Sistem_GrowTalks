<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\PodcastBooking;
use App\Models\CoachingBooking;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $podcastCount = PodcastBooking::where('id_user', $user->id)->count();
        $coachingCount = CoachingBooking::where('id_user', $user->id)->count();

        $pendingCount =
            PodcastBooking::where('id_user', $user->id)->where('status_verifikasi', 'pending')->count()
            +
            CoachingBooking::where('id_user', $user->id)->where('status_verifikasi', 'pending')->count();

        $recentPodcasts = PodcastBooking::with('user')
            ->where('status_verifikasi', 'disetujui')
            ->whereDate('tanggal', '>=', now())
            ->orderBy('tanggal', 'asc')
            ->get();

        $recentCoachings = CoachingBooking::with('user')
            ->where('status_verifikasi', 'disetujui')
            ->whereDate('tanggal', '>=', now())
            ->orderBy('tanggal', 'asc')
            ->get();

        return view('user.dashboard', compact(
            'podcastCount',
            'coachingCount',
            'pendingCount',
            'recentPodcasts',
            'recentCoachings'
        ));
    }

    public function showProfile()
    {
        $user = Auth::user();
        return view('user.profil', compact('user'));
    }

    public function editProfile()
    {
        $user = Auth::user();
        return view('user.edit-profile', compact('user'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'nama_pic' => 'required|string|max:100',
            'kontak_pic' => 'required|string|max:20',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'nama_pic' => $request->nama_pic,
            'kontak_pic' => $request->kontak_pic,
        ];

        if ($request->filled('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('user.profile')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
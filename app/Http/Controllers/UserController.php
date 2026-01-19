<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PodcastBooking;
use App\Models\CoachingBooking;

class UserController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();
        
        $podcastCount = PodcastBooking::where('id_user', $user->id)->count();
        $coachingCount = CoachingBooking::where('id_user', $user->id)->count();
        $pendingCount = PodcastBooking::where('id_user', $user->id)
            ->where('status_verifikasi', 'pending')
            ->count() + 
            CoachingBooking::where('id_user', $user->id)
            ->where('status_verifikasi', 'pending')
            ->count();
        
        $recentPodcasts = PodcastBooking::where('id_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
            
        $recentCoachings = CoachingBooking::where('id_user', $user->id)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();
        
        return view('user.dashboard', compact(
            'podcastCount',
            'coachingCount',
            'pendingCount',
            'recentPodcasts',
            'recentCoachings'
        ));
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoachingBooking;
use App\Models\User;

class VerifikatorCoachingController extends Controller
{
    // public function __construct()
    // {
    //     $this->middleware('auth:internal');
    //     $this->middleware(function ($request, $next) {
    //         if (!Auth::guard('internal')->user()->isVerifikatorCoaching()) {
    //             abort(403, 'Unauthorized access.');
    //         }
    //         return $next($request);
    //     });
    // }

    public function dashboard()
    {
        $user = Auth::guard('internal')->user();
        
        $totalCoachings = CoachingBooking::count();
        $pendingCoachings = CoachingBooking::where('status_verifikasi', 'pending')->count();
        $approvedCoachings = CoachingBooking::where('status_verifikasi', 'disetujui')->count();
        $rejectedCoachings = CoachingBooking::where('status_verifikasi', 'ditolak')->count();
        
        $recentCoachings = CoachingBooking::with('user')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        return view('verifikator-coaching.dashboard', compact(
            'totalCoachings', 
            'pendingCoachings', 
            'approvedCoachings', 
            'rejectedCoachings',
            'recentCoachings'
        ));
    }

    public function approval()
    {
        $coachings = CoachingBooking::with(['user', 'verifikator'])
            ->orderBy('created_at', 'desc')
            ->get();
            
        return view('verifikator-coaching.approval', compact('coachings'));
    }

    public function showApprovalForm($id)
    {
        $coaching = CoachingBooking::with('user')->findOrFail($id);
        return view('verifikator-coaching.approval-form', compact('coaching'));
    }

    public function updateApproval(Request $request, $id)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:pending,disetujui,ditolak',
            'coach' => 'nullable|string|max:100',
            'waktu' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        $coaching = CoachingBooking::findOrFail($id);
        $user = Auth::guard('internal')->user();

        $coaching->update([
            'status_verifikasi' => $request->status_verifikasi,
            'id_verifikator' => $user->id_internal_user,
            'verifikasi' => $user->nama_user,
            'coach' => $request->coach,
            'waktu' => $request->waktu,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('verifikator-coaching.approval')
            ->with('success', 'Status coaching clinic berhasil diperbarui.');
    }

    public function report()
    {
        $coachings = CoachingBooking::with(['user', 'verifikator'])
            ->orderBy('tanggal', 'desc')
            ->get();
            
        return view('verifikator-coaching.report', compact('coachings'));
    }
}
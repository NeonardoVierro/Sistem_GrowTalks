<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ValidateGuardSession
{
    /**
     * Middleware untuk memvalidasi dan memisahkan session antar guard
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if ($guard) {
            $session = $request->getSession();
            
            // Set session name berbeda untuk setiap guard
            $sessionNames = [
                'internal' => 'growtalks_internal_session',
                'web' => 'growtalks_web_session',
            ];

            if (isset($sessionNames[$guard])) {
                // Jika session belum memiliki nama atau nama berbeda, set yang baru
                if ($session->getName() !== $sessionNames[$guard]) {
                    $session->setName($sessionNames[$guard]);
                }
                
                // Simpan guard saat ini di session
                $session->put('_current_guard', $guard);
            }

            // Validasi bahwa authenticated user sesuai dengan guard
            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                // Pastikan session memiliki info tentang guard yang sedang aktif
                if (!$session->has('_guard_user_' . $guard)) {
                    $session->put('_guard_user_' . $guard, $user->id);
                }
            }
        }

        return $next($request);
    }
}

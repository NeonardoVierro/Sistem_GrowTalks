<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class SeparateGuardSessions
{
    /**
     * Middleware untuk benar-benar memisahkan session antar guard
     * dengan menggunakan session cookie names yang berbeda
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if ($guard) {
            // Map cookie names untuk setiap guard
            $cookieNames = [
                'internal' => 'growtalks_internal_session',
                'web' => 'growtalks_web_session',
            ];

            if (isset($cookieNames[$guard])) {
                $cookieName = $cookieNames[$guard];
                
                // Ganti session cookie name
                config(['session.cookie' => $cookieName]);
                
                // Rekonfigurasi session store dengan nama yang berbeda
                $this->reconfigureSession($request, $cookieName);
            }
        }

        return $next($request);
    }

    /**
     * Rekonfigurasi session dengan cookie name yang berbeda
     */
    private function reconfigureSession(Request $request, $cookieName)
    {
        $session = $request->getSession();
        
        // Set session name
        $session->setName($cookieName);
        
        // Jika session sudah ada, load dari cookie dengan nama baru
        if ($request->hasCookie($cookieName)) {
            $sessionId = $request->cookie($cookieName);
            $session->setId($sessionId);
        }
    }
}

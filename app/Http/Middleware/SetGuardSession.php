<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetGuardSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $guard = null): Response
    {
        if ($guard) {
            // Map session names untuk setiap guard
            $sessionNames = [
                'internal' => 'growtalks_internal_session',
                'web' => 'growtalks_web_session',
            ];

            if (isset($sessionNames[$guard])) {
                $session = $request->getSession();
                // Set session name sebelum session dimulai
                $session->setName($sessionNames[$guard]);
                
                // Store guard info di session untuk referensi
                $session->put('_guard', $guard);
            }
        }

        return $next($request);
    }
}

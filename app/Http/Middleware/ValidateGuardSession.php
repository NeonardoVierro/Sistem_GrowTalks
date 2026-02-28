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
            
            $sessionNames = [
                'internal' => 'growtalks_internal_session',
                'web' => 'growtalks_web_session',
            ];

            if (isset($sessionNames[$guard])) {
                if ($session->getName() !== $sessionNames[$guard]) {
                    $session->setName($sessionNames[$guard]);
                }
                
                $session->put('_current_guard', $guard);
            }

            if (Auth::guard($guard)->check()) {
                $user = Auth::guard($guard)->user();
                if (!$session->has('_guard_user_' . $guard)) {
                    $session->put('_guard_user_' . $guard, $user->id);
                }
            }
        }

        return $next($request);
    }
}

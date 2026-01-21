<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = Auth::guard('internal')->user();
        
        if (!$user) {
            return redirect('/login');
        }

        if (!in_array($user->role->kode_role, $roles)) {
            abort(403, 'Unauthorized access.');
        }

        return $next($request);
    }
}
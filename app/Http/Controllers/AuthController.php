<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\InternalUser;
use App\Models\Role;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        
        $identifier = $credentials['email'];
        $password = $credentials['password'];

        Log::info('Login attempt', [
            'identifier' => $identifier,
            'ip' => $request->ip(),
            'host' => $request->getHost(),
        ]);


        // Coba untuk login as InternalUser (Admin, Verifikator)
        if (Auth::guard('internal')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();

            $user = Auth::guard('internal')->user();

            // gunakan relasi role jika ada
            $roleCode = $user->role->kode_role ?? null;

            if (! $roleCode) {
                Auth::guard('internal')->logout();
                return back()->withErrors([
                    'email' => 'Akun internal tidak memiliki peran yang valid.',
                ])->onlyInput('email');
            }

            return $this->redirectBasedOnRole($roleCode);
        }
        
        // Coba untuk login as regular User 
        if (Auth::guard('web')->attempt($credentials, $request->remember)) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    private function redirectBasedOnRole($roleCode)
    {
        switch ($roleCode) {
            case 'admin':
                return redirect()->intended('/admin/dashboard');
            case 'verifikator_podcast':
                return redirect()->intended('/verifikator-podcast/dashboard');
            case 'verifikator_coaching':
                return redirect()->intended('/verifikator-coaching/dashboard');
            default:
                return redirect('/');
        }
    }

    public function logout(Request $request)
    {
        if (Auth::guard('internal')->check()) {
            Auth::guard('internal')->logout();
         } elseif (Auth::guard('web')->check()) {
            Auth::logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
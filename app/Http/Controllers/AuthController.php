<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use App\Models\User;
use App\Models\InternalUser;

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

        // Try to login as InternalUser (Admin) first by email or nama_user
        $admin = InternalUser::where('email', $identifier)
            ->orWhere('nama_user', $identifier)
            ->first();

        if ($admin) {
            Log::info('Admin found for login', ['admin_id' => $admin->{$admin->getAuthIdentifierName()}]);
            if (Hash::check($password, $admin->password)) {
                Log::info('Admin password matched, logging in');
                Auth::guard('admin')->login($admin, $request->has('remember'));
                $request->session()->regenerate();
                return redirect()->route('admin.dashboard');
            } else {
                Log::info('Admin password mismatch');
            }
        } else {
            Log::info('No admin record found for identifier');
        }

        // Try to login as regular User (by email)
        $userAttempt = Auth::attempt(['email' => $identifier, 'password' => $password], $request->has('remember'));
        Log::info('User attempt result', ['result' => $userAttempt]);
        if ($userAttempt) {
            $request->session()->regenerate();
            return redirect()->intended('/dashboard');
        }

        return back()->withErrors([
            'email' => 'Email atau password tidak valid.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        if (Auth::guard('admin')->check()) {
            Auth::guard('admin')->logout();
        } else {
            Auth::logout();
        }
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/login');
    }
}
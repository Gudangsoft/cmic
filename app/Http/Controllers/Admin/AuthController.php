<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Middleware\CheckMaintenanceMode;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }
        return view('admin.auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials, $request->boolean('remember'))) {
            $request->session()->regenerate();
            ActivityLog::log('login', 'Login ke admin panel');

            // Set maintenance bypass cookie so admin can view public site
            return redirect()->intended(route('admin.dashboard'))
                ->withCookie(CheckMaintenanceMode::makeBypassCookie());
        }

        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ])->onlyInput('email');
    }

    public function logout(Request $request)
    {
        ActivityLog::log('logout', 'Logout dari admin panel');
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        // Remove maintenance bypass cookie on logout
        return redirect()->route('admin.login')
            ->withCookie(Cookie::forget(CheckMaintenanceMode::BYPASS_COOKIE));
    }
}


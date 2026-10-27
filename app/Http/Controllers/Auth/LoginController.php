<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    protected function authenticated(Request $request, $user)
    {
        if ($user->hasRole('admin')) {
            return redirect('/home');
        }

        return redirect('/anggota/dashboard');
    }
    public function redirectTo()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->hasRole('admin')) {
            return '/dashboard';
        } elseif ($user->hasRole('anggota')) {
            return '/anggota/dashboard';
        }

        return '/';
    }
    public function logout(Request $request)
    {
        // Melakukan proses logout untuk guard 'web' (admin)
        $this->guard()->logout();

        // $request->session()->invalidate();

        // $request->session()->regenerateToken();

        // Setelah logout, redirect WAJIB ke halaman login admin
        return redirect()->route('admin.login');
    }
}

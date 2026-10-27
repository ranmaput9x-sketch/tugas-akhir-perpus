<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Anggota;

class AnggotaLoginController extends Controller
{
    /**
     * Middleware untuk memastikan hanya tamu (guest) yang bisa akses halaman login.
     * Kalau sudah login, akan dialihkan.
     */
    public function __construct()
    {
        $this->middleware('guest:anggota')->except('logout');
    }
    /**
     * Menampilkan form login untuk anggota.
     */
    public function showLoginForm()
    {
        return view('loginlogoutpublik.loginpub');
    }
    /**
     * Memproses percobaan login.
     */
    public function login(Request $request)
    {
        // 1. Validasi input dasar
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        // 2. Cek dulu, emailnya ada nggak di database?
        $anggota = Anggota::where('email', $credentials['email'])->first();

        // 3. Kalo email GA KETEMU, kasih pesan error email
        if (!$anggota) {
            return back()->withErrors([
                'email' => 'Waduh, email ini nggak terdaftar, guys. Coba cek lagi deh.',
            ])->onlyInput('email');
        }

        // 4. Kalo email KETEMU, baru coba login (cek password)
        if (Auth::guard('anggota')->attempt($credentials)) {
            // 5. Jika password BENAR, berhasil login
            $request->session()->regenerate();
            return redirect()->route('anggota.profil');
        }

        // 6. Jika password SALAH, kasih pesan error password
        return back()->withErrors([
            'password' => 'Yah, password-nya salah nih. Coba inget-inget lagi.',
        ])->onlyInput('email'); // Tetap pakai onlyInput('email') biar emailnya nggak hilang
    }
    /**
     * Memproses logout.
     */
    public function logout(Request $request)
    {
        // Logout menggunakan guard 'anggota'
        Auth::guard('anggota')->logout();
        // $request->session()->invalidate();
        // $request->session()->regenerateToken();
        // Redirect ke halaman utama setelah logout
        return redirect()->route('katalog.index');
    }
}

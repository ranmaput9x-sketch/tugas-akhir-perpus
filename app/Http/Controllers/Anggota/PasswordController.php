<?php

namespace App\Http\Controllers\Anggota;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('anggota.password.edit');
    }

    public function update(Request $request)
    {
        $request->validate([
            'password_lama' => ['required'],
            'password_baru' => ['required', 'min:6', 'confirmed'],
        ]);

        $anggota = \App\Models\Anggota::find(auth()->guard('anggota')->id());

        if (!$anggota || !Hash::check($request->password_lama, $anggota->password)) {
            return back()->withErrors(['password_lama' => 'Password lama salah.']);
        }

        $anggota->password = Hash::make($request->password_baru);
        $anggota->save();

        return redirect()->route('anggota.password.edit')->with('success', 'Password berhasil diganti.');
    }
}

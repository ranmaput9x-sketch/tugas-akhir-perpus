<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use Illuminate\Http\Request;

class RakController extends Controller
{
    public function index()
    {
        $raks = Rak::orderBy('kode_rak')->get();
        return view('rak.index', compact('raks'));
    }

    public function create()
    {
        return view('rak.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_rak' => 'required|unique:rak,kode_rak',
            'lokasi' => 'required',
        ]);

        Rak::create($request->only(['kode_rak', 'lokasi']));
        return redirect()->route('rak.index')->with('success', 'Rak berhasil ditambahkan.');
    }

    public function edit(Rak $rak)
    {
        return view('rak.edit', compact('rak'));
    }

    public function update(Request $request, Rak $rak)
    {
        $request->validate([
            'kode_rak' => 'required|unique:rak,kode_rak,' . $rak->id,
            'lokasi' => 'required',
        ]);

        $rak->update($request->only(['kode_rak', 'lokasi']));
        return redirect()->route('rak.index')->with('success', 'Rak berhasil diupdate.');
    }

    public function destroy(Rak $rak)
    {
        // Cek apakah ada item buku yang masih menggunakan rak ini
        if ($rak->items()->exists()) {
            return back()->with('error', 'Gagal! Rak ini tidak bisa dihapus karena masih ada buku di dalamnya.');
        }

        $rak->delete();
        return back()->with('success', 'Rak berhasil dihapus.');
    }
}

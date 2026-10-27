<?php

namespace App\Http\Controllers;

use App\Models\Koleksi;
use App\Models\Buku;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class KoleksiController extends Controller
{
    /**
     * Menampilkan halaman filter dan hasil pencarian buku berdasarkan koleksi.
     */
    public function index(Request $request)
    {
        $koleksis = Koleksi::orderBy('nama_koleksi')->get();
        $bukus = collect(); // Defaultnya koleksi kosong
        $selectedKoleksiId = $request->input('koleksi_id');

        if ($selectedKoleksiId) {
            $bukus = Buku::with('penulis')->where('koleksi_id', $selectedKoleksiId)->get();
        }

        return view('koleksi.index', [
            'koleksis' => $koleksis,
            'bukus' => $bukus,
            'selectedKoleksiId' => $selectedKoleksiId
        ]);
    }

    // Method CRUD lainnya untuk mengelola nama koleksi
    public function create()
    {
        return view('koleksi.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate(['nama_koleksi' => 'required|unique:koleksis']);
        Koleksi::create($validated);
        return redirect()->route('koleksi.index')->with('success', 'Jenis Koleksi baru berhasil ditambahkan.');
    }

    public function edit(Koleksi $koleksi)
    {
        return view('koleksi.edit', compact('koleksi'));
    }

    public function update(Request $request, Koleksi $koleksi)
    {
        $validated = $request->validate(['nama_koleksi' => ['required', Rule::unique('koleksis')->ignore($koleksi)]]);
        $koleksi->update($validated);
        return redirect()->route('koleksi.index')->with('success', 'Jenis Koleksi berhasil diupdate.');
    }
    public function destroy($id)
    {
        $koleksi = Koleksi::findOrFail($id);
        $koleksi->delete();

        return redirect()->route('koleksi.index')
            ->with('success', 'Data berhasil dihapus');
    }
}

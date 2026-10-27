<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use Illuminate\Http\Request;

class StokController extends Controller
{
    /**
     * Menampilkan halaman stok & kondisi buku.
     */
    public function index(Request $request)
    {
        $raks = Buku::select('rak')->distinct()->pluck('rak');
        $query = Buku::query();

        if ($request->filled('rak')) {
            $query->where('rak', $request->rak);
        }

        if ($request->filled('search')) {
            $query->where('judul_buku', 'like', '%' . $request->search . '%');
        }

        $bukus = $query->orderBy('judul_buku')->get();

        return view('stok.index', compact('bukus', 'raks'));
    }

    /**
     * Update stok buku secara manual (via input angka).
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'stok' => 'required|integer|min:0',
            'stok_rusak' => 'nullable|integer|min:0',
            'stok_ditarik' => 'nullable|integer|min:0',
        ]);

        $buku = Buku::findOrFail($id);

        $buku->update([
            'stok' => $request->stok,
            'stok_rusak' => $request->stok_rusak ?? 0,
            'stok_ditarik' => $request->stok_ditarik ?? 0,
        ]);

        return back()->with('success', 'Stok buku berhasil diperbarui.');
    }

    /**
     * Toggle nilai kondisi (Rusak/Ditarik) Ya <-> Tidak via AJAX.
     */
    public function toggle(Request $request, $id)
    {
        $buku = Buku::findOrFail($id);
        $field = $request->input('field');

        if (!in_array($field, ['stok_rusak', 'stok_ditarik'])) {
            return response()->json(['success' => false, 'message' => 'Field tidak valid']);
        }

        // Toggle nilai: 0 jadi 1, 1 jadi 0
        $buku->$field = $buku->$field ? 0 : 1;
        $buku->save();

        return response()->json([
            'success' => true,
            'value' => $buku->$field
        ]);
    }
}

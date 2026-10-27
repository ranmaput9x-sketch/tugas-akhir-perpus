<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penulis;
use Illuminate\Support\Facades\DB;

class PenulisController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Optional: filter by search query
        $query = Penulis::query();
        if ($request->filled('q')) {
            $query->where('nama_penulis', 'like', '%' . $request->q . '%');
        }
        $penulis = $query->orderBy('nama_penulis')->get();
        return view('penulis.index', compact('penulis'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('penulis.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_penulis' => 'required|unique:penulis,nama_penulis',
        ]);
        Penulis::create($validated);
        return redirect()->route('penulis.index')
            ->with('success', 'Penulis berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Penulis $penulis)
    {
        return view('penulis.show', compact('penulis'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Penulis $penulis)
    {
        $validated = $request->validate([
            'nama_penulis' => 'required|unique:penulis,nama_penulis,' . $penulis->id,
        ]);
        $penulis->update($validated);
        return redirect()->route('penulis.index')
            ->with('success', 'Penulis berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $penulis = Penulis::findOrFail($id);

        // cek pivot
        $isRelated = DB::table('buku_penulis')
            ->where('penulis_id', $id)
            ->exists();

        if ($isRelated) {
            // ambil detail karya via relasi Eloquent
            $titles = $penulis->buku()->select('judul_buku')->get()->pluck('judul_buku')->toArray();
            // flash array of titles
            return redirect()->route('penulis.index')
                ->with('error', 'Gagal! Penulis ini masih memiliki karya.')
                ->with('books', $titles);
        }

        try {
            $penulis->delete();
            return redirect()->route('penulis.index')
                ->with('success', 'Penulis berhasil dihapus.');
        } catch (\Throwable $e) {
            return redirect()->route('penulis.index')
                ->with('error', 'Terjadi kesalahan saat menghapus: ' . $e->getMessage());
        }
    }
}

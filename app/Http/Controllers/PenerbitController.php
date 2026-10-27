<?php
// app/Http/Controllers/PenerbitController.php
namespace App\Http\Controllers;

use App\Models\Penerbit;
use Illuminate\Http\Request;

class PenerbitController extends Controller
{
    public function index()
    {
        // Tampilkan 10 penerbit per halaman
        $penerbits = Penerbit::latest()->get();
        return view('penerbit.index', compact('penerbits'));
    }

    public function create()
    {
        return view('penerbit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:150',
        ]);

        Penerbit::create($request->only('nama_penerbit'));

        return redirect()
            ->route('penerbit.index')
            ->with('success', 'Penerbit berhasil ditambahkan 🎉');
    }

    public function show(Penerbit $penerbit)
    {
        return view('penerbit.show', compact('penerbit'));
    }

    public function edit(Penerbit $penerbit)
    {
        return view('penerbit.edit', compact('penerbit'));
    }

    public function update(Request $request, Penerbit $penerbit)
    {
        $request->validate([
            'nama_penerbit' => 'required|string|max:150',
        ]);

        $penerbit->update($request->only('nama_penerbit'));

        return redirect()
            ->route('penerbit.index')
            ->with('success', 'Data penerbit diperbarui 🚀');
    }

    public function destroy(Penerbit $penerbit)
    {
        $penerbit->delete();

        return redirect()
            ->route('penerbit.index')
            ->with('success', 'Penerbit dihapus 🗑️');
    }
}

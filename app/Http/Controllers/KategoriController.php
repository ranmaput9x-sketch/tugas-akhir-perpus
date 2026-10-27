<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    private $batasTampilBeranda = 9; // Batas kategori yang tampil di beranda
    public function index()
    {
        $kategoris = Kategori::orderBy('nama_kategori')->get();
        // Hitung berapa kategori yang sudah di-set untuk tampil di beranda
        $jumlahTampil = Kategori::where('tampil_di_beranda', true)->count();
        // 2. Di sini kita kirim variabel itu ke file Blade ('kategori.index')
        return view('kategori.index', [
            'kategoris' => $kategoris,
            'batasTampilBeranda' => $this->batasTampilBeranda,
            'jumlahTampil' => $jumlahTampil,
        ]);
    }

    public function create()
    {
        return view('kategori.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_kategori'    => 'required|string|max:255',
            'kode_klasifikasi' => 'nullable|string|max:100',
        ]);

        Kategori::create($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Kategori $kategori)
    {
        $validated = $request->validate([
            'nama_kategori'    => 'required|string|max:255',
            'kode_klasifikasi' => 'nullable|string|max:100',
        ]);

        $kategori->update($validated);

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil diubah.');
    }

    public function toggleBeranda(Kategori $kategori)
    {
        // PENJAGA GERBANG: Cek kuota SEBELUM MENGAKTIFKAN
        // Jika status saat ini 'false' (artinya mau diubah jadi 'true')
        if (!$kategori->tampil_di_beranda) {
            $jumlahTampilSaatIni = Kategori::where('tampil_di_beranda', true)->count();
            if ($jumlahTampilSaatIni >= $this->batasTampilBeranda) {
                return back()->with('error', 'Gagal! Batas kategori yang tampil di beranda sudah tercapai (' . $this->batasTampilBeranda . ').');
            }
        }

        // Logika toggle yang sudah ada
        $kategori->tampil_di_beranda = !$kategori->tampil_di_beranda;
        $kategori->save();
        $status = $kategori->tampil_di_beranda ? 'ditampilkan' : 'disembunyikan';
        $message = "Status kategori '{$kategori->nama_kategori}' berhasil diubah menjadi {$status} di beranda.";
        return back()->with('success', $message);
    }

    public function destroy(Kategori $kategori)
    {
        $kategori->delete();

        return redirect()
            ->route('kategori.index')
            ->with('success', 'Kategori berhasil dihapus.');
    }
}

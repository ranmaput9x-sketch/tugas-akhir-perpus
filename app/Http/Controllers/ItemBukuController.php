<?php

namespace App\Http\Controllers;

use App\Models\ItemBuku;
use App\Models\Buku;
use App\Models\Rak;
use Illuminate\Http\Request;

class ItemBukuController extends Controller
{
    /**
     * Menampilkan daftar semua eksemplar dari semua buku.
     */
    public function index()
    {
        $items = ItemBuku::with(['buku', 'rak'])->latest()->paginate(100);
        return view('items.index', compact('items'));
    }

    /**
     * Method store ini HANYA dipanggil dari halaman detail buku,
     * bukan dari form create-nya sendiri.
     */
    // Di dalam ItemBukuController.php

    public function store(Request $request, Buku $buku)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'rak_id' => 'required|exists:rak,id',
        ]);

        $jumlah = (int) $request->jumlah;
        $nomorEksemplarAwal = $buku->items()->count();

        for ($i = 1; $i <= $jumlah; $i++) {
            // GUNAKAN RELASI: '$buku->items()->create()' akan otomatis mengisi 'buku_id'
            $newItem = $buku->items()->create([
                'rak_id'            => $request->rak_id,
                'status'            => 'Tersedia',
                'sumber_perolehan'  => $request->sumber_perolehan,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'harga'             => $request->harga,
                'nomor_eksemplar'   => $nomorEksemplarAwal + $i,
            ]);

            // Update HANYA untuk kode barcode setelah dapat ID
            $newItem->update([
                'kode_barcode' => 'INV-' . str_pad($newItem->id, 6, '0', STR_PAD_LEFT),
            ]);
        }

        return redirect()->route('buku.show', $buku)->with('success', $jumlah . ' eksemplar baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit satu data eksemplar.
     * (Contoh: Mengubah status atau memindahkan rak)
     */
    public function edit(ItemBuku $item)
    {
        $raks = Rak::orderBy('kode_rak')->get();
        return view('items.edit', compact('item', 'raks'));
    }

    /**
     * Meng-update data satu eksemplar.
     */
    public function update(Request $request, ItemBuku $item)
    {
        $validated = $request->validate([
            'rak_id' => 'required|exists:rak,id',
            'status' => 'required|in:Tersedia,Dipinjam,Hilang,Perbaikan',
        ]);

        $item->update($validated);

        return redirect()->route('items.index')->with('success', 'Data eksemplar berhasil diupdate.');
    }

    /**
     * Menghapus satu data eksemplar.
     */
    public function destroy(ItemBuku $item)
    {
        // Fitur keamanan: jangan hapus eksemplar yang sedang dipinjam.
        if ($item->status === 'Dipinjam') {
            return back()->with('error', 'Gagal! Eksemplar ini sedang dipinjam dan tidak bisa dihapus.');
        }

        $item->delete();

        return back()->with('success', 'Eksemplar berhasil dihapus.');
    }
}

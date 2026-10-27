<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ItemBuku;
use App\Models\Rak;
use App\Models\Buku;
use Milon\Barcode\DNS1D;
use Barryvdh\DomPDF\Facade\Pdf;

class ItemController extends Controller
{
    /**
     * Store new items & generate UPC-A, simpan di kolom `kode_barcode`.
     */
    public function show($id)
    {
        // 1. Ambil buku + relasi items.rak
        $buku = Buku::with('items.rak')->findOrFail($id);

        // 2. Generate UPC-A untuk preview
        $upcCode = $this->generateUPCABarcode($buku);

        // 3. Ambil semua rak buat dropdown
        $raks = Rak::all();

        // 4. Kirim ke view
        return view('buku.show', compact('buku', 'upcCode', 'raks'));
    }
    public function store(Request $request, $buku_id)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1',
            'rak'    => 'required|exists:rak,kode_rak',
        ]);

        $buku = Buku::findOrFail($buku_id);
        $rak  = Rak::where('kode_rak', $request->rak)->firstOrFail();

        for ($i = 0; $i < $request->jumlah; $i++) {
            // 1) Simpan dulu tanpa kode_barcode → dapet ID
            $item = ItemBuku::create([
                'buku_id'       => $buku->id,
                'rak_id'        => $rak->id,
                'status'        => 'Tersedia',
                'tanggal_masuk' => now(),
            ]);

            // 2) Generate UPC-A unik berdasar $item->id
            $barcode = $this->generateItemUPCABarcode($item);

            // 3) Update record: simpan di kolom kode_barcode
            $item->update(['kode_barcode' => $barcode]);
        }

        return redirect()
            ->route('buku.show', $buku->id)
            ->with('success', "{$request->jumlah} item berhasil ditambahkan!");
    }



    /**
     * Cetak PDF barcode UPC-A (pakai kolom `kode_barcode`).
     */
    public function printBarcodePdf($id)
    {
        // Ambil data item
        $item = ItemBuku::findOrFail($id);

        // 👉 Instantiate DNS1D sebelum pakai getBarcodePNG()
        $d   = new DNS1D();
        $png = $d->getBarcodePNG(
            $item->kode_barcode, // kode numeric 12-digit
            'UPCA',              // format UPC-A
            3,                   // scale
            33,                  // height
            [1, 1, 1],             // warna (RGB)
            true                 // tampilkan angka
        );

        // Render PDF dengan DomPDF
        $pdf = Pdf::loadView('items.barcode-pdf', [
            'item'    => $item,
            'barcode' => $png,
        ])->setPaper('A4', 'portrait');

        return $pdf->stream("barcode_item_{$item->id}.pdf");
    }

    /**
     * Update status item (Tersedia, Dipinjam, dst).
     */
    public function updateStatus(Request $request, ItemBuku $item)
    {
        $request->validate([
            'status' => 'required|in:Tersedia,Dipinjam,Hilang,Perbaikan',
        ]);
        $item->update(['status' => $request->status]);

        return back()->with('success', 'Status item berhasil diubah.');
    }

    /**
     * Bangun 12-digit UPC-A dari ItemBuku.
     */
    private function generateItemUPCABarcode(ItemBuku $item): string
    {
        $manuf = '123456'; // ganti kode perpustakaan-mu
        $prod  = str_pad($item->id, 5, '0', STR_PAD_LEFT);
        $base  = $manuf . $prod;            // 11 digit
        $check = $this->calculateUPCCheckDigit($base); // 1 digit
        return $base . $check;              // total 12 digit
    }

    /**
     * Hitung check digit UPC-A (mod 10).
     */
    private function calculateUPCCheckDigit(string $num): string
    {
        $sum = 0;
        foreach (str_split($num) as $i => $d) {
            $n   = (int)$d;
            $sum += (($i + 1) % 2 === 1) ? $n * 3 : $n;
        }
        $mod = $sum % 10;
        return $mod === 0 ? '0' : (string)(10 - $mod);
    }
}

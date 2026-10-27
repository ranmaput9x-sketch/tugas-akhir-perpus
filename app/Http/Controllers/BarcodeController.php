<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Buku;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;

class BarcodeController extends Controller
{
    public function index()
    {
        $bukus = Buku::all();
        return view('barcode.index', compact('bukus'));
    }

    public function preview(Request $request)
    {
        $data = $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'ukuran' => 'required|in:small,medium,large',
            'buku_ids' => 'required|array|min:1',
            'buku_ids.*' => 'exists:buku,id',
        ]);

        $jumlah = $data['jumlah'];
        $ukuran = $data['ukuran'];

        // Ambil data buku sesuai urutan input
        $bukus = collect($data['buku_ids'])->map(function ($id) {
            return Buku::find($id);
        });

        return view('barcode.preview', compact('bukus', 'jumlah', 'ukuran'));
    }
    public function cetak(Request $request)
    {
        $data = $request->validate([
            'jumlah' => 'required|numeric|min:1',
            'ukuran' => 'required|in:small,medium,large',
            'buku_data' => 'required|array|min:1',
            'buku_data.*.id' => 'required',
            'buku_data.*.judul_buku' => 'required|string',
            'buku_data.*.barcode' => 'required|string',
        ]);

        $bukus = collect($data['buku_data']);
        $jumlah = $data['jumlah'];
        $ukuran = $data['ukuran'];

        $pdf = FacadePdf::loadView('barcode.pdf', compact('bukus', 'jumlah', 'ukuran'))
            ->setPaper('a4', 'portrait');

        return $pdf->download('barcode_' . now()->format('Ymd_His') . '.pdf');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Rak;
use App\Models\Buku;
use App\Models\ImportHistory;
use App\Imports\BukuImport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class BukuImportController extends Controller
{
    /**
     * Menampilkan form untuk mengimpor buku.
     */
    public function showForm()
    {
        $raks = Rak::orderBy('kode_rak')->get();
        return view('buku.impor', compact('raks'));
    }

    /**
     * Memproses file Excel/CSV untuk diimpor ke database.
     */
    public function processImport(Request $request)
    {
        $request->validate([
            'file_import'        => 'required|file|mimes:xlsx,xls,csv',
            'rak_id'             => 'required|exists:rak,id',
            'sumber_perolehan'   => 'required|string',
            'tanggal_perolehan'  => 'required|date',
        ]);

        $file = $request->file('file_import');

        try {
            Excel::import(
                new BukuImport(
                    $request->rak_id,
                    $request->sumber_perolehan,
                    $request->tanggal_perolehan
                ),
                $file
            );

            // Jika impor berhasil, catat hash file ke history
            ImportHistory::create([
                'file_hash' => md5_file($file->getRealPath()),
                'file_name' => $file->getClientOriginalName(),
            ]);

            return back()->with('success', 'Semua data buku berhasil diimpor!');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal mengimpor file: ' . $e->getMessage());
        }
    }

    /**
     * Menerima daftar konten (judul & penerbit) dari frontend
     * dan mengecek mana yang sudah ada di database.
     */
    public function checkContentDuplicates(Request $request)
    {
        $validated = $request->validate([
            'data'              => 'required|array',
            'data.*.judul_buku' => 'required|string',
            'data.*.penerbit'   => 'nullable|string',
        ]);

        $dataToCheck = $validated['data'];
        if (empty($dataToCheck)) {
            return response()->json(['duplicates' => []]);
        }

        // --- QUERY DENGAN NAMA TABEL YANG SUDAH BENAR ---
        $query = Buku::query()
            // Gabungkan dengan tabel 'penerbit' (tanpa 's')
            ->join('penerbit', 'buku.penerbit_id', '=', 'penerbit.id');

        foreach ($dataToCheck as $item) {
            $query->orWhere(function ($q) use ($item) {
                $q->where('buku.judul_buku', $item['judul_buku'])
                    // Cek ke tabel 'penerbit' (tanpa 's')
                    ->where('penerbit.nama_penerbit', $item['penerbit']);
            });
        }

        $existingItems = $query->select('buku.judul_buku', 'penerbit.nama_penerbit as penerbit')->get();
        // ---------------------------------------------

        return response()->json([
            'duplicates' => $existingItems
        ]);
    }
}

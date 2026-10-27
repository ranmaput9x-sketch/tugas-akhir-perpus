<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Peminjaman;
use App\Exports\PeminjamanExport;
use App\Exports\PengembalianExport;
use Maatwebsite\Excel\Facades\Excel; // jika pakai Laravel‑Excel
use PDF;                             // jika pakai DomPDF

class LaporanController extends Controller
{
    public function index()
    {
        return view('laporan.sirkulasi'); // nanti kita buat blade-nya
    }
    public function export(Request $req)
    {
        $req->validate([
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'tipe'          => 'required|in:pinjam,kembali,semua',
        ]);

        // Ambil data sesuai filter
        $q = Peminjaman::with(['anggota', 'items.buku'])
            ->whereBetween('tanggal_pinjam', [$req->tanggal_mulai, $req->tanggal_selesai]);

        if ($req->tipe === 'pinjam') {
            // hanya yang dibuat pinjam di range
        } elseif ($req->tipe === 'kembali') {
            $q->whereNotNull('tanggal_pengembalian_aktual')
                ->whereBetween('tanggal_pengembalian_aktual', [$req->tanggal_mulai, $req->tanggal_selesai]);
        }

        $data = $q->get();

        // Jika pilih Excel
        if ($req->export_as === 'excel') {
            return Excel::download(new \App\Exports\SirkulasiExport($data), 'laporan_sirkulasi.xlsx');
        }

        // Default: PDF
        $pdf = PDF::loadView('laporan.sirkulasi_pdf', compact('data', 'req'));
        return $pdf->download('laporan_sirkulasi.pdf');
    }
    // public function pinjamForm()
    // {
    //     return view('laporan.pinjam');
    // }

    // public function exportPinjam(Request $request)
    // {
    //     $request->validate([
    //         'from' => 'required|date',
    //         'to'   => 'required|date|after_or_equal:from',
    //     ]);

    //     $fileName = 'laporan-peminjaman-' . now()->format('Ymd_His') . '.xlsx';
    //     return Excel::download(
    //         new PeminjamanExport($request->from, $request->to),
    //         $fileName
    //     );
    // }

    // public function kembaliForm()
    // {
    //     return view('laporan.kembali');
    // }

    // public function exportKembali(Request $request)
    // {
    //     $request->validate([
    //         'from' => 'required|date',
    //         'to'   => 'required|date|after_or_equal:from',
    //     ]);

    //     $fileName = 'laporan-pengembalian-' . now()->format('Ymd_His') . '.xlsx';
    //     return Excel::download(
    //         new PengembalianExport($request->from, $request->to),
    //         $fileName
    //     );
    // }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\Reports\SirkulasiReport;
use App\Exports\GenericReportExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;
use App\Models\Anggota;
use App\Services\Reports\BukuReport;
use App\Services\Reports\AnggotaReport;


class ReportController extends Controller
{
    public function sirkulasi()
    {
        $anggotas = Anggota::orderBy('nama_anggota')->get();

        return view('reports.sirkulasi', compact('anggotas'));
    }

    public function generate(Request $request)
    {
        $report = new SirkulasiReport();
        $rows   = $report->query($request->all())->get();
        $mapped = $rows->map(fn($r) => $report->map($r));

        return match ($request->output) {
            'excel' => Excel::download(
                new GenericReportExport(
                    $mapped,
                    $report->headers()
                ),
                'laporan_sirkulasi.xlsx'
            ),

            'pdf' => PDF::loadView('reports.sirkulasi-pdf', [
                'headers' => $report->headers(),
                'rows' => $mapped
            ])->download('laporan_sirkulasi.pdf'),

            'print' => view('reports.sirkulasi-print', [
                'headers' => $report->headers(),
                'rows' => $mapped
            ]),
        };
    }
    public function buku()
    {
        return view('reports.buku');
    }

    public function generateBuku(Request $request)
    {
        $report = new BukuReport();
        $rows   = $report->query($request->all())->get();
        $mapped = $rows->map(fn($r) => $report->map($r));

        return match ($request->output) {
            'excel' => Excel::download(
                new GenericReportExport($mapped, $report->headers()),
                'laporan_buku.xlsx'
            ),

            'pdf' => PDF::loadView('reports.buku-pdf', [
                'headers' => $report->headers(),
                'rows' => $mapped
            ])->download('laporan_buku.pdf'),

            'print' => view('reports.buku-print', [
                'headers' => $report->headers(),
                'rows' => $mapped
            ]),
        };
    }
    public function anggota()
    {
        return view('reports.anggota');
    }

    public function generateAnggota(Request $request)
    {
        $report = new AnggotaReport();
        $rows   = $report->query($request->all())->get();
        $mapped = $rows->map(fn($r) => $report->map($r));

        return match ($request->output) {
            'excel' => Excel::download(
                new GenericReportExport($mapped, $report->headers()),
                'laporan_anggota.xlsx'
            ),

            'pdf' => PDF::loadView('reports.anggota-pdf', [
                'headers' => $report->headers(),
                'rows' => $mapped
            ])->download('laporan_anggota.pdf'),

            'print' => view('reports.anggota-print', [
                'headers' => $report->headers(),
                'rows' => $mapped
            ]),
        };
    }
}

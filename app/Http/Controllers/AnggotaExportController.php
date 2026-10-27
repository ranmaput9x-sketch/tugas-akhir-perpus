<?php

namespace App\Http\Controllers;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Illuminate\Http\Request;
use App\Models\Anggota;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AnggotaExportController extends Controller
{
    // public function exportExcelWithImages()
    // {
    //     $spreadsheet = new Spreadsheet();
    //     $sheet = $spreadsheet->getActiveSheet();

    //     // Header
    //     $sheet->fromArray([
    //         ['ID Anggota', 'Nama Lengkap', 'NIS/NIP', 'Status', 'Tingkat', 'Tanggal Lahir', 'Tanggal Registrasi', 'Berlaku Hingga', 'Jenis Kelamin', 'Alamat Lengkap', 'Foto', 'Barcode']
    //     ], null, 'A1');



    //     // Lebar kolom agar gak kepotong
    //     foreach (range('A', 'J') as $col) {
    //         $sheet->getColumnDimension($col)->setAutoSize(true);
    //     }
    //     $sheet->getColumnDimension('K')->setWidth(15); // Lebar kolom foto
    //     $sheet->getColumnDimension('L')->setWidth(20); // Lebar kolom barcode
    //     $row = 2;
    //     foreach (Anggota::all() as $anggota) {
    //         $sheet->setCellValue("A{$row}", $anggota->id_anggota);
    //         $sheet->setCellValue("B{$row}", $anggota->nama_anggota);
    //         $sheet->setCellValue("C{$row}", $anggota->nomor_induk);
    //         $sheet->setCellValue("D{$row}", $anggota->status);
    //         $sheet->setCellValue("E{$row}", $anggota->tingkat);
    //         $sheet->setCellValue("F{$row}", $anggota->tanggal_lahir);
    //         $sheet->setCellValue("G{$row}", $anggota->tanggal_registrasi);
    //         $sheet->setCellValue("H{$row}", $anggota->berlaku_hingga);
    //         $sheet->setCellValue("I{$row}", $anggota->jenis_kelamin);
    //         $sheet->setCellValue("J{$row}", $anggota->alamat);

    //         // Tinggikan baris agar gambar muat
    //         $sheet->getRowDimension($row)->setRowHeight(70);

    //         if ($anggota->foto && Storage::disk('public')->exists($anggota->foto)) {
    //             $drawing = new Drawing();
    //             $drawing->setPath(storage_path('app/public/' . $anggota->foto));
    //             $drawing->setCoordinates("K{$row}"); // Geser ke K
    //             $drawing->setHeight(70);
    //             $drawing->setWorksheet($sheet);
    //         }

    //         // Menambahkan Barcode (Kolom L)
    //         $barcodePath = 'barcode/' . $anggota->id_anggota . '.png';
    //         if (Storage::disk('public')->exists($barcodePath)) {
    //             $drawing = new Drawing();
    //             $drawing->setPath(storage_path('app/public/' . $barcodePath));
    //             $drawing->setCoordinates("L{$row}"); // Geser ke L
    //             $drawing->setHeight(60);
    //             $drawing->setWorksheet($sheet);
    //         }

    //         $row++;
    //     }

    //     $fileName = 'anggota_excel_dengan_gambar_rapi.xlsx';
    //     $writer = new Xlsx($spreadsheet);
    //     $filePath = storage_path('app/' . $fileName);
    //     $writer->save($filePath);

    //     return response()->download($filePath)->deleteFileAfterSend(true);
    // }
    public function exportPDF()
    {
        $anggotas = Anggota::all();

        return Pdf::loadView('anggota.pdf', compact('anggotas'))
            ->setPaper('A4', 'landscape')
            ->stream('anggota.pdf');
    }

    public function printPreview()
    {
        $anggotas = Anggota::all();
        return view('anggota.print', compact('anggotas'));
    }
    public function form()
    {
        return view('anggota.export');
    }

    public function exportFiltered(Request $request)
    {
        $request->validate([
            'type' => 'required|in:excel-img,pdf,print',
            'nama_file' => 'nullable|string|max:255',
        ]);

        $nomorIndukHeader = 'NIS/NIP'; // Judul default
        if ($request->input('tingkat') === 'Siswa') {
            $nomorIndukHeader = 'NIS';
        } elseif ($request->input('tingkat') === 'Guru') {
            $nomorIndukHeader = 'NIP';
        }

        $type = $request->input('type');

        $query = Anggota::query();

        if ($request->filled('gender')) {
            $query->where('jenis_kelamin', $request->gender);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        $anggotas = $query->get();

        // Menyiapkan nama file (custom atau default)
        $defaultFileName = 'laporan_anggota_' . date('Y-m-d');
        $customFileName = $request->filled('nama_file')
            ? Str::slug($request->input('nama_file'), '_')
            : null;

        // === Excel Export with Gambar ===
        if ($type === 'excel-img') {
            $fileName = ($customFileName ?: $defaultFileName) . '.xlsx';
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();

            $sheet->fromArray([
                ['ID Anggota', 'Nama Lengkap', $nomorIndukHeader, 'Status', 'Tingkat', 'Tanggal Lahir', 'Tanggal Registrasi', 'Berlaku Hingga', 'Jenis Kelamin', 'Alamat Lengkap', 'Foto',]
            ], null, 'A1');


            foreach (range('A', 'J') as $col) {
                $sheet->getColumnDimension($col)->setAutoSize(true);
            }
            $sheet->getColumnDimension('K')->setWidth(15);
            $sheet->getColumnDimension('L')->setWidth(20);
            $row = 2;
            foreach ($anggotas as $anggota) {
                $sheet->getRowDimension($row)->setRowHeight(75);

                // Mengisi data sel (TAMBAHKAN $anggota->nomor_induk DI KOLOM C)
                // Perhatikan pergeseran huruf kolom setelahnya
                $sheet->setCellValue("A{$row}", $anggota->id_anggota);
                $sheet->setCellValue("B{$row}", $anggota->nama_anggota);
                $sheet->setCellValue("C{$row}", $anggota->nomor_induk); // <-- DATA BARU
                $sheet->setCellValue("D{$row}", $anggota->status);
                $sheet->setCellValue("E{$row}", $anggota->tingkat);
                $sheet->setCellValue("F{$row}", $anggota->tanggal_lahir);
                $sheet->setCellValue("G{$row}", $anggota->tanggal_registrasi);
                $sheet->setCellValue("H{$row}", $anggota->berlaku_hingga);
                $sheet->setCellValue("I{$row}", $anggota->jenis_kelamin);
                $sheet->setCellValue("J{$row}", $anggota->alamat);

                // Menambahkan Foto (Kolom K)
                if ($anggota->foto && Storage::disk('public')->exists($anggota->foto)) {
                    $drawing = new Drawing();
                    $drawing->setPath(storage_path('app/public/' . $anggota->foto));
                    $drawing->setCoordinates("K{$row}"); // Geser ke K
                    $drawing->setHeight(70);
                    $drawing->setWorksheet($sheet);
                }

                // // Menambahkan Barcode (Kolom L)
                // $barcodePath = 'barcode/' . $anggota->id_anggota . '.png';
                // if (Storage::disk('public')->exists($barcodePath)) {
                //     $drawing = new Drawing();
                //     $drawing->setPath(storage_path('app/public/' . $barcodePath));
                //     $drawing->setCoordinates("L{$row}"); // Geser ke L
                //     $drawing->setHeight(60);
                //     $drawing->setWorksheet($sheet);
                // }

                $row++;
            }

            $writer = new Xlsx($spreadsheet);

            return response()->stream(function () use ($writer) {
                $writer->save('php://output');
            }, 200, [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
            ]);
        }

        // === PDF Export ===
        if ($type === 'pdf') {
            $fileName = ($customFileName ?: $defaultFileName) . '.pdf';
            $pdf = Pdf::loadView('anggota.pdf', compact('anggotas', 'nomorIndukHeader'))->setPaper('A4', 'landscape');
            return $pdf->stream($fileName);
        }

        // === Print Preview ===
        if ($type === 'print') {
            return view('anggota.print', compact('anggotas', 'nomorIndukHeader'));
        }

        return redirect()->back()->with('error', 'Tipe export tidak valid.');
    }
}

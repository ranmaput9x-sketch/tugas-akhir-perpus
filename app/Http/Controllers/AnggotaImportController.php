<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Anggota;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;

class AnggotaImportController extends Controller
{
    public function form()
    {
        return view('anggota.import');
    }

    public function preview(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:xlsx,xls',
            'tingkat' => 'required|in:siswa,guru,admin'
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . $file->getClientOriginalName();
        $filePath = $file->storeAs('imports', $fileName, 'local');

        $spreadsheet = IOFactory::load(Storage::disk('local')->path($filePath));
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray(null, true, true, true);

        // --- LOGIKA VALIDASI DIMULAI DI SINI ---
        $validData = [];
        $invalidData = [];

        foreach ($rows as $index => $row) {
            if ($index == 1) continue; // Skip header

            $namaAnggota = $row['A'] ?? null;
            $nomorInduk = $row['B'] ?? null;

            $rowData = [
                'nama_anggota'   => $namaAnggota,
                'nomor_induk'    => $nomorInduk,
                'alamat'         => $row['C'] ?? '-',
                'jenis_kelamin'  => $row['D'] ?? 'Laki-laki',
                'tanggal_lahir'  => $row['E'] ?? now()->subYears(17)->format('Y-m-d'),
            ];

            // Cek data kosong
            if (empty($namaAnggota) || empty($nomorInduk)) {
                $rowData['reason'] = 'Nama atau Nomor Induk kosong.';
                $invalidData[] = $rowData;
                continue;
            }

            // Cek duplikat
            if (Anggota::where('nomor_induk', $nomorInduk)->exists()) {
                $rowData['reason'] = "Nomor Induk '{$nomorInduk}' sudah ada.";
                $invalidData[] = $rowData;
                continue;
            }

            // Jika lolos, masukkan ke data valid
            $validData[] = $rowData;
        }

        // Simpan hanya data yang valid ke session
        if (!empty($validData)) {
            session(['valid_import_data' => $validData, 'import_tingkat' => $request->tingkat]);
        }

        // Hapus file sementara setelah diproses
        Storage::disk('local')->delete($filePath);

        return view('anggota.preview', [
            'validData' => $validData,
            'invalidData' => $invalidData,
            'tingkat' => $request->tingkat
        ]);
    }

    public function submit(Request $request)
    {
        // Ambil data yang sudah divalidasi dari session
        $validData = session('valid_import_data');
        $tingkat = session('import_tingkat');

        if (empty($validData) || empty($tingkat)) {
            return redirect()->route('anggota.import.form')->with('error', 'Tidak ada data valid untuk diimpor atau sesi telah berakhir.');
        }

        $today = now();
        $importedCount = 0;

        foreach ($validData as $data) {
            $kodeTingkat = ($tingkat === 'guru') ? 'G' : 'S';
            $tahunRegistrasi = $today->format('y');
            $empatDigitTerakhirNIS = substr($data['nomor_induk'], -4);
            $id_anggota = $kodeTingkat . $tahunRegistrasi . '-' . $empatDigitTerakhirNIS;

            $domain = ($tingkat === 'guru') ? 'guru.perpus.com' : 'siswa.perpus.com';
            $email = strtolower(str_replace('-', '', $id_anggota)) . '@' . $domain;
            $password = Hash::make($data['nomor_induk']);

            $berlakuHingga = ($tingkat === 'guru') ? '9999-12-31' : $today->copy()->addYears(3)->format('Y-m-d');

            $anggotaBaru = Anggota::create([
                'id_anggota'        => $id_anggota,
                'nama_anggota'      => $data['nama_anggota'],
                'nomor_induk'       => $data['nomor_induk'],
                'alamat'            => $data['alamat'],
                'jenis_kelamin'     => $data['jenis_kelamin'],
                'tanggal_lahir'     => $data['tanggal_lahir'],
                'tingkat'           => ucfirst($tingkat),
                'status'            => 'aktif',
                'email'             => $email,
                'password'          => $password,
                'barcode'           => $id_anggota,
                'tanggal_registrasi' => $today->format('Y-m-d'),
                'berlaku_hingga'    => $berlakuHingga,
            ]);

            $anggotaBaru->generateBarcodeImage();
            $importedCount++;
        }

        // Hapus session setelah selesai
        session()->forget(['valid_import_data', 'import_tingkat']);

        return redirect()->route('anggota.index')->with('success', "Berhasil mengimpor {$importedCount} data anggota baru.");
    }
}

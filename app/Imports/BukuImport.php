<?php

namespace App\Imports;

use App\Models\Buku;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\Kategori;
use App\Models\Koleksi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BukuImport implements ToCollection, WithHeadingRow
{
    protected int $rakId;
    protected string $sumberPerolehan;
    protected string $tanggalPerolehan;

    public function __construct(int $rakId, string $sumberPerolehan, string $tanggalPerolehan)
    {
        $this->rakId             = $rakId;
        $this->sumberPerolehan   = $sumberPerolehan;
        $this->tanggalPerolehan  = $tanggalPerolehan;
    }

    /**
     * Cari atau buat kategori (nama prioritas, lalu kode, lalu create baru).
     */
    private function findOrCreateKategori(?string $nama, ?string $kode): ?int
    {
        $nama = trim((string)$nama);
        $kode = trim((string)$kode);

        // 1) by nama
        if ($nama !== '' && $eks = Kategori::where('nama_kategori', $nama)->first()) {
            return $eks->id;
        }

        // 2) by kode
        if ($kode !== '' && $eks = Kategori::where('kode_klasifikasi', $kode)->first()) {
            if ($nama !== '' && !$eks->nama_kategori) {
                $eks->nama_kategori = $nama;
                $eks->save();
            }
            return $eks->id;
        }

        // 3) create new
        if ($nama !== '' || $kode !== '') {
            $new = Kategori::create([
                'nama_kategori'    => $nama !== '' ? $nama : null,
                'kode_klasifikasi' => $kode !== '' ? $kode : null,
            ]);
            return $new->id;
        }

        return null;
    }

    /**
     * Cari atau buat model generik (Penulis, Penerbit, Koleksi).
     */
    private function findOrCreate(string $modelClass, string $column, string $value): ?int
    {
        $val = trim((string)$value);
        return $val === '' ? null : $modelClass::firstOrCreate([$column => $val])->id;
    }

    /**
     * Generate nomor panggil: DDC – Tahun – 3‑letter penulis – 1‑letter judul [+ no.X]
     */
    private function generateUniqueCallNumber(
        Kategori $kategori,
        Penulis  $pu,
        string   $judul,
        string   $tahun,
        ?int     $ignoreId = null
    ): string {
        $code = $kategori->kode_klasifikasi ?: '000';
        $abbr = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $pu->nama_penulis), 0, 3));
        $init = strtolower(substr($judul, 0, 1));

        // base always includes year
        $base = sprintf('%s %s %s %s', $code, $tahun, $abbr, $init);

        // helper query
        $query = fn($np) => Buku::where('nomor_panggil', $np)
            ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId));

        if (! $query($base)->exists()) {
            return $base;
        }

        $i = 1;
        while ($query("{$base} no.{$i}")->exists()) {
            $i++;
        }

        return "{$base} no.{$i}";
    }

    /**
     * Process collection of rows from Excel.
     */
    public function collection(Collection $rows)
    {
        foreach ($rows as $row) {
            // skip if required fields missing
            if (empty($row['judul_buku']) || empty($row['penulis']) || empty($row['penerbit'])) {
                continue;
            }

            DB::transaction(function () use ($row) {
                // prepare relations
                $penerbitId = $this->findOrCreate(Penerbit::class, 'nama_penerbit', $row['penerbit']);
                $koleksiId  = $this->findOrCreate(Koleksi::class,  'nama_koleksi',  $row['koleksi'] ?? '');
                $kategoriId = $this->findOrCreateKategori($row['kategori'] ?? null, $row['ddc_code'] ?? null);

                // penulis (multiple)
                $penulisIds = [];
                foreach (explode(',', $row['penulis']) as $p) {
                    if (trim($p) !== '') {
                        $penulisIds[] = $this->findOrCreate(Penulis::class, 'nama_penulis', $p);
                    }
                }

                // buku data
                $data = [
                    'judul_buku'     => $row['judul_buku'],
                    'sub_judul'      => $row['sub_judul']      ?? null,
                    'tahun_terbit'   => $row['tahun_terbit']   ?? null,
                    'jumlah_halaman' => $row['jumlah_halaman'] ?? 0,
                    'penerbit_id'    => $penerbitId,
                    'koleksi_id'     => $koleksiId,
                    'isbn'           => $row['isbn']           ?? null,
                    'deskripsi'      => $row['deskripsi']      ?? null,
                    'tempat_terbit'  => $row['tempat_terbit']  ?? null,
                    'edisi'          => $row['edisi']          ?? null,
                    'rak_id'         => $this->rakId,
                ];

                // updateOrCreate by ISBN if exists
                $buku = !empty($data['isbn'])
                    ? Buku::updateOrCreate(['isbn' => $data['isbn']], $data)
                    : Buku::create($data);

                // sync relations
                $buku->penulis()->sync(array_filter($penulisIds));
                if ($kategoriId) {
                    $buku->kategori()->sync([$kategoriId]);
                }

                // generate call number if empty
                if (!$buku->nomor_panggil && $kategoriId && !empty($penulisIds)) {
                    $pu  = Penulis::find($penulisIds[0]);
                    $kat = Kategori::find($kategoriId);
                    $buku->nomor_panggil = $this->generateUniqueCallNumber(
                        $kat,
                        $pu,
                        $buku->judul_buku,
                        $buku->tahun_terbit
                    );
                    $buku->save();
                }

                // create items with nomor_eksemplar, sumber & tanggal perolehan
                // Ambil raw string dari kolom 'harga', default '0' kalau kosong
                $rawHarga = (string) ($row['harga'] ?? '0');
                // Hapus semua karakter non-digit (termasuk titik/koma ribuan)
                $clean     = preg_replace('/\D/', '', $rawHarga);
                // Cast ke float—jadi 9229032.0 dari "9.229.032"
                $harga     = (float) $clean;
                $offset = $buku->items()->count();
                $jumlah = (int) ($row['jumlah_eksemplar'] ?? 0);
                for ($i = 1; $i <= $jumlah; $i++) {
                    $item = $buku->items()->create([
                        'rak_id'            => $this->rakId,
                        'status'            => 'Tersedia',
                        'tanggal_masuk'     => now(),
                        'sumber_perolehan'  => $this->sumberPerolehan,
                        'tanggal_perolehan' => $this->tanggalPerolehan,
                        'harga'             => $harga,            // ← gunakan hasil preg_replace saja
                        'nomor_eksemplar'   => $offset + $i,
                    ]);
                    $item->update([
                        'kode_barcode' => 'INV-' . str_pad($item->id, 6, '0', STR_PAD_LEFT),
                    ]);
                }
            });
        }
    }
}

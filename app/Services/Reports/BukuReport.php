<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;

class BukuReport
{
    public function headers(): array
    {
        return [
            'Judul Buku',
            'ISBN',
            'Tahun Terbit',
            'Penerbit',
            'Total Eksemplar',
            'Tersedia',
            'Dipinjam',
            'Rusak / Hilang',
        ];
    }

    public function query(array $params)
    {
        return DB::table('buku as b')
            ->leftJoin('item_buku as ib', 'ib.buku_id', '=', 'b.id')
            ->leftJoin('penerbit as p', 'b.penerbit_id', '=', 'p.id')
            ->select([
                'b.judul_buku',
                'b.isbn',
                'b.tahun_terbit',
                'p.nama_penerbit',
                DB::raw('COUNT(ib.id) as total_eksemplar'),
                DB::raw("SUM(CASE WHEN ib.status = 'Tersedia' THEN 1 ELSE 0 END) as tersedia"),
                DB::raw("SUM(CASE WHEN ib.status = 'Dipinjam' THEN 1 ELSE 0 END) as dipinjam"),
                DB::raw("SUM(CASE WHEN ib.status IN ('Rusak','Hilang') THEN 1 ELSE 0 END) as rusak_hilang"),
            ])
            ->groupBy(
                'b.id',
                'b.judul_buku',
                'b.isbn',
                'b.tahun_terbit',
                'p.nama_penerbit'
            )
            ->orderBy('b.judul_buku');
    }

    public function map($row): array
    {
        return [
            $row->judul_buku,
            $row->isbn ?? '-',
            $row->tahun_terbit,
            $row->nama_penerbit ?? '-',
            $row->total_eksemplar,
            $row->tersedia,
            $row->dipinjam,
            $row->rusak_hilang,
        ];
    }
}

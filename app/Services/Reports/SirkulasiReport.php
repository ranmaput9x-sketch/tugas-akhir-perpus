<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;

class SirkulasiReport
{
    public function headers(): array
    {
        return [
            'Nama Anggota',
            'ID Anggota',
            'Judul Buku',
            'Kode Eksemplar',
            'Tanggal Pinjam',
            // Di database, `tanggal_kembali` adalah JATUH TEMPO (due date), bukan tanggal buku benar-benar dikembalikan.
            'Jatuh Tempo',
            'Tanggal Pengembalian Aktual',
            'Status',
            'Kondisi Buku',
        ];
    }



    public function query(array $params)
    {
        return DB::table('peminjaman_item_buku as pib')
            ->join('peminjaman as p', 'pib.peminjaman_id', '=', 'p.id')
            ->join('anggotas as a', 'p.anggota_id', '=', 'a.id')
            ->join('item_buku as ib', 'pib.item_buku_id', '=', 'ib.id')
            ->join('buku as b', 'ib.buku_id', '=', 'b.id')
            ->select([
                'a.nama_anggota',
                'a.id_anggota',
                'b.judul_buku as judul_buku',
                'ib.kode_barcode as kode_item',
                'p.tanggal_pinjam',
                'p.tanggal_kembali',
                'p.tanggal_pengembalian_aktual',
                DB::raw("
                CASE
                    WHEN p.status = 'Kembali' THEN 'DIKEMBALIKAN'
                    WHEN p.status = 'Telat'   THEN 'TELAT'
                    ELSE 'DIPINJAM'
                END as status
            "),
                'pib.kondisi'
            ])
            ->when($params['status'] ?? null, function ($q, $status) {
                if ($status === 'DIPINJAM') {
                    // Dipinjam termasuk yang statusnya Telat (masih belum kembali)
                    $q->whereIn('p.status', ['Dipinjam', 'Telat']);
                } else {
                    $q->where('p.status', 'Kembali');
                }
            })
            ->when(
                $params['anggota_id'] ?? null,
                fn($q, $anggotaId) =>
                $q->where('a.id', $anggotaId)
            )
            ->when(
                $params['from'] ?? null,
                fn($q, $from) =>
                $q->whereDate('p.tanggal_pinjam', '>=', $from)
            )
            ->orderBy('p.tanggal_pinjam', 'desc');
    }




    public function map($row): array
    {
        return [
            $row->nama_anggota,
            $row->id_anggota,
            $row->judul_buku,
            $row->kode_item,
            $row->tanggal_pinjam,
            $row->tanggal_kembali ?? '-',
            $row->tanggal_pengembalian_aktual ?? '-',
            $row->status,
            strtoupper($row->kondisi),
        ];
    }
}

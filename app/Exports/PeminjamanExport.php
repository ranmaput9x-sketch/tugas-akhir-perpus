<?php

namespace App\Exports;

use App\Models\Peminjaman;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PeminjamanExport implements FromCollection, WithHeadings
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to   = $to;
    }

    public function collection()
    {
        return Peminjaman::with('anggota', 'items.buku')
            ->whereBetween('tanggal_pinjam', [$this->from, $this->to])
            ->get()
            ->flatMap(function ($pinjam) {
                return $pinjam->items->map(fn($item) => [
                    'No. Transaksi'   => $pinjam->id,
                    'Tanggal Pinjam'  => $pinjam->tanggal_pinjam,
                    'Anggota'         => $pinjam->anggota->nama_anggota,
                    'Kode Eksemplar'  => $item->kode_barcode,
                    'Judul Buku'      => $item->buku->judul_buku,
                ]);
            });
    }

    public function headings(): array
    {
        return [
            'No. Transaksi',
            'Tanggal Pinjam',
            'Anggota',
            'Kode Eksemplar',
            'Judul Buku',
        ];
    }
}

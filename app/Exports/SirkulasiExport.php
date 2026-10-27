<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SirkulasiExport implements FromCollection, WithHeadings
{
    protected Collection $items;

    public function __construct(Collection $items)
    {
        $this->items = $items;
    }

    /**
     * Flatten data jadi baris per item buku.
     */
    public function collection()
    {
        return $this->items->flatMap(function ($peminjaman) {
            return $peminjaman->items->map(fn($item) => [
                'No. Peminjaman'   => $peminjaman->id,
                'Anggota'          => $peminjaman->anggota->nama_anggota,
                'Tanggal Pinjam'   => $peminjaman->tanggal_pinjam,
                'Jatuh Tempo'      => $peminjaman->tanggal_kembali,
                'Tanggal Kembali'  => $peminjaman->tanggal_pengembalian_aktual ?? '-',
                'Status'           => $peminjaman->status,
                'Buku'             => $item->buku->judul_buku,
                'Barcode'          => $item->kode_barcode,
                'Kondisi'          => ucfirst($item->pivot->kondisi),
                'Pengganti'        => $item->pivot->pengganti_item_buku_id
                    ? \App\Models\ItemBuku::find($item->pivot->pengganti_item_buku_id)?->kode_barcode
                    : '-',
            ]);
        })->values();
    }

    /**
     * Ambil header dari array pertama, tanpa memanggil toArray() pada array.
     */
    public function headings(): array
    {
        $first = $this->collection()->first();
        if (! $first || ! is_array($first)) {
            return [];
        }
        return array_keys($first);
    }
}

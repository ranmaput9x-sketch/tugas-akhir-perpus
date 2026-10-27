<?php

namespace App\Services\Reports;

use Illuminate\Support\Facades\DB;

class AnggotaReport
{
    public function headers(): array
    {
        return [
            'ID Anggota',
            'Nama Anggota',
            'Nomor Induk',
            'Status',
            'Tanggal Registrasi',
            'Berlaku Hingga',
            'Total Peminjaman',
        ];
    }

    public function query(array $params)
    {
        return DB::table('anggotas as a')
            ->leftJoin('peminjaman as p', 'p.anggota_id', '=', 'a.id')
            ->select([
                'a.id_anggota',
                'a.nama_anggota',
                'a.nomor_induk',
                'a.status',
                'a.tanggal_registrasi',
                'a.berlaku_hingga',
                DB::raw('COUNT(p.id) as total_peminjaman'),
            ])
            ->when(
                $params['status'] ?? null,
                fn($q, $status) =>
                $q->where('a.status', $status)
            )
            ->groupBy(
                'a.id',
                'a.id_anggota',
                'a.nama_anggota',
                'a.nomor_induk',
                'a.status',
                'a.tanggal_registrasi',
                'a.berlaku_hingga'
            )
            ->orderBy('a.nama_anggota');
    }

    public function map($row): array
    {
        return [
            $row->id_anggota,
            $row->nama_anggota,
            $row->nomor_induk ?? '-',
            strtoupper($row->status),
            $row->tanggal_registrasi,
            $row->berlaku_hingga,
            $row->total_peminjaman,
        ];
    }
}

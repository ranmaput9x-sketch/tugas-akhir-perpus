<?php

namespace App\Exports;

use App\Models\Anggota;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;


class AnggotaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Anggota::all();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Nama',
            'Tgl Lahir',
            'Tgl Registrasi',
            'Berlaku Hingga',
            'Jenis Kelamin',
            'Alamat',
            'Foto (URL)',
            'Barcode'
        ];
    }

    public function map($item): array
    {
        return [
            $item->id_anggota,
            $item->nama_anggota,
            $item->tanggal_lahir,
            $item->tanggal_registrasi,
            $item->berlaku_hingga,
            $item->jenis_kelamin,
            $item->alamat,
            asset('storage/foto/' . $item->foto),
            $item->barcode,
        ];
    }

    public function drawings()
    {
        $drawings = [];
        $row = 2;
        foreach (Anggota::all() as $anggota) {
            $path = storage_path('app/public/barcode/' . $anggota->id_anggota . '.png');
            if (file_exists($path)) {
                $drawing = new Drawing();
                $drawing->setName('Barcode');
                $drawing->setDescription('Barcode');
                $drawing->setPath($path);
                $drawing->setHeight(50);
                $drawing->setCoordinates('I' . $row);
                $drawings[] = $drawing;
            }
            $row++;
        }
        return $drawings;
    }
}

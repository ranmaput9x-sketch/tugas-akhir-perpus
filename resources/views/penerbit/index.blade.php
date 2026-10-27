{{-- resources/views/penerbit/index.blade.php --}}
@extends('adminlte::page')

@section('title', 'Daftar Penerbit')

@section('content_header')
    <h1 class="m-0 text-dark">Daftar Penerbit</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span>
                <i class="fas fa-building"></i></i> Daftar Penerbit
            </span>
            <a href="{{ route('penerbit.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Penerbit
            </a>
        </div>
        <div class="card-body">
            @php
                // 1. Definisi header kolom
                $heads = [
                    ['label' => 'No', 'width' => 5],
                    'Nama Penerbit',
                    'Dibuat Pada',
                    'Diubah Pada',
                    ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
                ];

                // 2. Konfigurasi DataTable
                $config = [
                    'data' => [],
                    'order' => [[0, 'asc']], // urut berdasarkan kolom “No”
                    'dom' => '<"d-flex justify-content-between mb-3"<l><f>>rtip',
                    'pageLength' => 10,
                    'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                    'columns' => [
                        null,
                        null,
                        null,
                        null,
                        ['orderable' => false, 'searchable' => false, 'className' => 'text-center'],
                    ],
                    'language' => [
                        'search' => 'Cari:',
                        'lengthMenu' => 'Tampilkan _MENU_ entri',
                        'zeroRecords' => 'Tidak ada data yang cocok ditemukan',
                        'info' => 'Menampilkan _START_ sampai _END_ dari _TOTAL_ entri',
                        'infoEmpty' => 'Tidak ada data yang tersedia',
                        'paginate' => [
                            'first' => 'Pertama',
                            'last' => 'Terakhir',
                            'next' => 'Berikutnya',
                            'previous' => 'Sebelumnya',
                        ],
                    ],
                    'responsive' => true,
                    'autoWidth' => false,
                ];

                // 3. Populate data baris
                foreach ($penerbits as $idx => $p) {
                    $no = $idx + 1;
                    $btnEdit =
                        '<a href="' .
                        route('penerbit.edit', $p) .
                        '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                        </a>';
                    $btnDelete =
                        '<form action="' .
                        route('penerbit.destroy', $p) .
                        '" method="POST" class="d-inline">
                            ' .
                        csrf_field() .
                        method_field('DELETE') .
                        '
                            <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" title="Hapus" 
                                onclick="return confirm(\'Yakin mau dihapus?\')">
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </button>
                        </form>';

                    $config['data'][] = [
                        $no,
                        $p->nama_penerbit,
                        $p->created_at->format('d M Y'),
                        $p->updated_at->format('d M Y'),
                        '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
                    ];
                }
            @endphp

            {{-- 4. Render AdminLTE Datatable --}}
            <x-adminlte-datatable id="table_penerbit" :heads="$heads" :config="$config" striped hoverable bordered
                with-buttons />
        </div>

    </div>
@stop
@section('js')
    {{-- Panggil Vite. Semua JS akan di-handle oleh app.js --}}
    @vite(['resources/js/app.js'])
@stop

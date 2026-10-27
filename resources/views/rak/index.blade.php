@extends('adminlte::page')

@section('title', 'Daftar Rak')

@section('content_header')
    <h1>Daftar Rak / Lokasi</h1>
@stop

@section('content')
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif
    @if (session('error'))
        <x-adminlte-alert theme="danger" title="Gagal" dismissable>
            {{ session('error') }}
        </x-adminlte-alert>
    @endif
    <div class="card card-primary card-outline card-outline-tabs fade-in-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-warehouse"></i> Daftar Rak / Lokasi</span>
            <a href="{{ route('rak.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Rak
            </a>
        </div>
        <div class="card-body">
            @php
                // Definisi header kolom
                $heads = [
                    ['label' => 'No', 'width' => 5],
                    'Kode Rak',
                    'Lokasi',
                    ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
                ];

                // Konfigurasi DataTable
                $config = [
                    'data' => [],
                    'order' => [[1, 'asc']],
                    'dom' => '<"d-flex justify-content-between mb-3"<l><f>>rtip',
                    'pageLength' => 10,
                    'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                    'columns' => [
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

                // Isi data baris dari collection $raks
                foreach ($raks as $i => $rak) {
                    $btnEdit =
                        '<a href="' .
                        route('rak.edit', $rak) .
                        '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit"><i class="fa fa-lg fa-fw fa-pen"></i></a>';

                    $btnDelete =
                        '<form action="' .
                        route('rak.destroy', $rak) .
                        '" method="POST" style="display:inline">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="return confirm(\'Yakin mau dihapus?\')" title="Hapus">' .
                        '<i class="fa fa-lg fa-fw fa-trash"></i></button></form>';

                    $config['data'][] = [
                        $i + 1,
                        $rak->kode_rak,
                        $rak->lokasi,
                        '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
                    ];
                }
            @endphp

            {{-- Render DataTable --}}
            <x-adminlte-datatable id="rakTable" :heads="$heads" :config="$config" striped hoverable bordered
                with-buttons />
        </div>
    </div>
@stop
@section('js')
    @vite('resources/js/app.js')
    <script>
        console.log('DataTable script loaded.');
    </script>
@stop
@section('css')
    @vite('resources/css/app.css')
@stop

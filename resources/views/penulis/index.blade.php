@extends('adminlte::page')

@section('title', 'Data Penulis')

@section('content_header')
    <h1 class="m-0 text-dark">Data Penulis</h1>
@stop

@section('content')
    {{-- Alert Error --}}
    @if (session('error'))
        <x-adminlte-alert theme="danger" title="Gagal" dismissable>
            {{ session('error') }}
        </x-adminlte-alert>
    @endif

    {{-- Alert Success --}}
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    {{-- sebelum tabel, setelah alert error --}}
    @if (session('books'))
        <x-adminlte-alert theme="warning" title="Detail Karya" dismissable>
            <ul class="mb-0">
                @foreach (session('books') as $judul)
                    <li>{{ $judul }}</li>
                @endforeach
            </ul>
        </x-adminlte-alert>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-feather-alt"></i> Daftar Penulis</span>
            <a href="{{ route('penulis.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Tambah Penulis
            </a>
        </div>
        <div class="card-body">
            @php
                // 1. Definisi header kolom
                $heads = [
                    ['label' => 'No', 'width' => 5],
                    'Nama Penulis',
                    'Dibuat Pada',
                    'Diubah Pada',
                    ['label' => 'Aksi', 'no-export' => true, 'width' => 10],
                ];

                // 2. Konfigurasi DataTable
                $config = [
                    'data' => [], // nanti diisi di loop
                    'order' => [[0, 'asc']], // urut berdasarkan kolom pertama (No)
                    'dom' => '<"d-flex justify-content-between mb-3"<l><f>>rtip',
                    'pageLength' => 10,
                    'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                    'columns' => [
                        ['orderable' => false, 'searchable' => false], // No
                        null, // Nama Penulis
                        null, // Dibuat Pada
                        null, // Diubah Pada
                        ['orderable' => false, 'searchable' => false, 'className' => 'text-center'], // Aksi
                    ],
                    'language' => [
                        'url' => 'https://cdn.datatables.net/plug-ins/1.13.5/i18n/id.json',
                    ],
                    'responsive' => true,
                    'autoWidth' => false,
                ];

                // 3. Isi data baris
                foreach ($penulis as $i => $item) {
                    $btnEdit =
                        '<a href="' .
                        route('penulis.edit', $item->id) .
                        '" ' .
                        'class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">' .
                        '<i class="fa fa-lg fa-pen"></i></a>';

                    $btnDelete =
                        '<form action="' .
                        route('penulis.destroy', $item->id) .
                        '" method="POST" style="display:inline">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" ' .
                        'onclick="return confirm(\'Yakin mau dihapus?\')" title="Hapus">' .
                        '<i class="fa fa-lg fa-trash"></i></button>' .
                        '</form>';

                    $config['data'][] = [
                        $i + 1,
                        $item->nama_penulis,
                        $item->created_at->format('d M Y'),
                        $item->updated_at->format('d M Y'),
                        '<nobr>' . $btnEdit . $btnDelete . '</nobr>',
                    ];
                }
            @endphp

            {{-- 4. Render DataTable --}}
            <x-adminlte-datatable id="penulisTable" :heads="$heads" :config="$config" striped hoverable bordered
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

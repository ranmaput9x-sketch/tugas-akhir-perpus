@extends('adminlte::page')

@section('title', 'Daftar Kategori')

@section('content_header')
    <h1 class="font-weight-bold">Daftar Kategori</h1>
@stop

@section('content')
    {{-- Notifikasi Sukses --}}
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Card Utama --}}
    <div class="card card-primary card-outline card-outline-tabs shadow-lg fade-in-card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span class="h5 mb-0"><i class="fas fa-tags mr-2"></i>Data Kategori</span>
            <span class="badge {{ $jumlahTampil >= $batasTampilBeranda ? 'badge-danger' : 'badge-success' }}">
                Tampil di Beranda: {{ $jumlahTampil }}/{{ $batasTampilBeranda }}
            </span>
            <a href="{{ route('kategori.create') }}" class="btn btn-primary btn-sm">
                <i class="fas fa-plus mr-1"></i> Tambah Baru
            </a>
        </div>
        <div class="card-body">
            @php
                // 1. Definisi header kolom (TAMBAH KOLOM BARU)
                $heads = [
                    ['label' => '#', 'width' => 3],
                    'Nama Kategori',
                    ['label' => 'Tampil di Beranda?', 'width' => 20, 'class' => 'text-center'], // Kolom baru!
                    ['label' => 'Aksi', 'no-export' => true, 'width' => 15, 'class' => 'text-center'],
                ];

                // 2. Konfigurasi DataTable (tetap sama)
                $config = [
                    'data' => [],
                    'order' => [[1, 'asc']], // Default sort berdasarkan nama kategori
                    'dom' => '<"d-flex justify-content-between mb-3"<l><f>>rtip',
                    'pageLength' => 10,
                    'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                    'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json'],
                    'responsive' => true,
                    'autoWidth' => false,

                    // --- INI DIA BAGIAN YANG DIUBAH ---
                    'columns' => [
                        // Kolom 0: # -> Boleh di-sort, tidak bisa di-search
                        ['orderable' => true, 'searchable' => false],

                        // Kolom 1: Nama Kategori -> Boleh di-sort & di-search
                        ['orderable' => true],

                        // Kolom 2: Tampil di Beranda -> TIDAK bisa di-sort & di-search
                        ['orderable' => false, 'searchable' => false],

                        // Kolom 3: Aksi -> TIDAK bisa di-sort & di-search
                        ['orderable' => false, 'searchable' => false],
                    ],
                    // --- SELESAI PERUBAHAN ---
                ];

                // 3. Isi data baris dengan toggle switch
                foreach ($kategoris as $i => $item) {
                    // Tombol Hapus
                    $btnDelete =
                        '<form action="' .
                        route('kategori.destroy', $item) .
                        '" method="POST" class="d-inline">' .
                        csrf_field() .
                        method_field('DELETE') .
                        '<button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow" onclick="return confirm(\'Yakin mau hapus kategori ini?\')" title="Hapus"><i class="fa fa-lg fa-fw fa-trash"></i></button></form>';
                    // --- LOGIKA UNTUK NONAKTIFKAN TOGGLE ---
                    // Cek: Apakah item ini sedang TIDAK aktif DAN kuota sudah penuh?
                    $isLimitReached = !$item->tampil_di_beranda && $jumlahTampil >= $batasTampilBeranda;
                    // INI DIA TOGGLE SWITCH-NYA
                    $toggleForm =
                        '<form action="' .
                        route('admin.kategori.toggleBeranda', $item->id) .
                        '" method="POST" class="d-flex justify-content-center">' .
                        csrf_field() .
                        '<div class="custom-control custom-switch custom-switch-on-success">' .
                        '<input type="checkbox" class="custom-control-input" id="toggle-' .
                        $item->id .
                        '" ' .
                        ($item->tampil_di_beranda ? 'checked' : '') .
                        ' onchange="this.form.submit()">' .
                        '<label class="custom-control-label" for="toggle-' .
                        $item->id .
                        '"></label>' .
                        '</div>' .
                        '</form>';

                    $config['data'][] = [
                        $i + 1,
                        "<strong>{$item->nama_kategori}</strong><br><small class='text-muted'>Kode: {$item->kode_klasifikasi}</small>",
                        $toggleForm,
                        '<nobr>' . $btnDelete . '</nobr>',
                    ];
                }
            @endphp

            {{-- 4. Render DataTable --}}
            <x-adminlte-datatable id="kategoriTable" :heads="$heads" :config="$config" striped hoverable bordered
                with-buttons />
        </div>
    </div>
@stop

{{-- Tambahkan sedikit CSS custom buat toggle-nya --}}
@section('css')
    <style>
        .custom-control-input:checked~.custom-control-label::before {
            border-color: #28a745;
            background-color: #28a745;
        }

        .custom-switch {
            padding-left: 2.25rem;
            cursor: pointer;
        }
    </style>
@stop

{{-- Biar alert-nya bisa ilang sendiri --}}
@section('js')
    <script>
        // Auto-hide alert setelah 5 detik
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000); // 5000 milliseconds = 5 seconds
    </script>
@stop

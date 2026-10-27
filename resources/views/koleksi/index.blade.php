@extends('adminlte::page')

@section('title', 'Manajemen & Jelajah Koleksi')

@section('content_header')
    <h1>Manajemen & Jelajah Koleksi</h1>
@stop

@section('content')
    {{-- Notifikasi --}}
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses" dismissable>{{ session('success') }}</x-adminlte-alert>
    @endif
    @if (session('error'))
        <x-adminlte-alert theme="danger" title="Gagal" dismissable>{{ session('error') }}</x-adminlte-alert>
    @endif

    {{-- Navigasi Tabs dengan class animasi --}}
    <div class="card card-primary card-outline card-outline-tabs fade-in-card">
        <div class="card-header p-0 border-bottom-0">
            <ul class="nav nav-tabs" id="koleksi-tabs" role="tablist">
                <li class="nav-item">
                    <a class="nav-link active" id="jelajah-tab" data-toggle="pill" href="#jelajah-content" role="tab"
                        aria-controls="jelajah-content" aria-selected="true">
                        <i class="fas fa-search-location mr-2"></i>Jelajah Buku
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" id="manajemen-tab" data-toggle="pill" href="#manajemen-content" role="tab"
                        aria-controls="manajemen-content" aria-selected="false">
                        <i class="fas fa-cogs mr-2"></i>Manajemen Koleksi
                    </a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div class="tab-content" id="koleksi-tabs-content">
                {{-- KONTEN TAB 1: JELAJAH BUKU --}}
                <div class="tab-pane fade show active" id="jelajah-content" role="tabpanel" aria-labelledby="jelajah-tab">
                    <h5>Pilih Jenis Koleksi untuk Menampilkan Buku</h5>
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        @foreach ($koleksis as $koleksi)
                            <a href="{{ route('koleksi.index', ['koleksi_id' => $koleksi->id]) }}"
                                class="btn {{ $selectedKoleksiId == $koleksi->id ? 'btn-primary' : 'btn-outline-primary' }}">
                                {{ $koleksi->nama_koleksi }}
                            </a>
                        @endforeach
                    </div>

                    @if ($selectedKoleksiId)
                        <div class="mt-4">
                            @php
                                $heads = ['No.', 'Judul Buku', 'Penulis', 'Tahun', ['label' => 'Aksi', 'width' => 5]];
                            @endphp
                            <x-adminlte-datatable id="bukuTable" :heads="$heads" striped hoverable bordered with-buttons>
                                @forelse ($bukus as $buku)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $buku->judul_buku }}</td>
                                        <td>{{ $buku->penulis->pluck('nama_penulis')->join(', ') }}</td>
                                        <td>{{ $buku->tahun_terbit }}</td>
                                        <td>
                                            <a href="{{ route('buku.show', $buku) }}"
                                                class="btn btn-xs btn-default text-teal mx-1 shadow" title="Details">
                                                <i class="fa fa-lg fa-fw fa-eye"></i>
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center">Tidak ada buku dalam koleksi ini.</td>
                                    </tr>
                                @endforelse
                            </x-adminlte-datatable>
                        </div>
                    @endif
                </div>

                {{-- KONTEN TAB 2: MANAJEMEN KOLEKSI --}}
                <div class="tab-pane fade" id="manajemen-content" role="tabpanel" aria-labelledby="manajemen-tab">
                    <x-adminlte-button label="Tambah Koleksi Baru" data-toggle="modal" data-target="#modal-create"
                        class="bg-primary mb-3" />

                    @php
                        $heads_manajemen = [
                            'Nama Koleksi',
                            ['label' => 'Aksi', 'width' => 10, 'class' => 'text-center'],
                        ];
                    @endphp
                    <x-adminlte-datatable id="manajemenTable" :heads="$heads_manajemen" striped hoverable bordered>
                        @foreach ($koleksis as $koleksi)
                            <tr>
                                <td>{{ $koleksi->nama_koleksi }}</td>
                                <td>
                                    <nobr class="d-flex justify-content-center">
                                        <a href="{{ route('koleksi.edit', $koleksi) }}"
                                            class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                            <i class="fa fa-lg fa-fw fa-pen"></i>
                                        </a>
                                        <form action="{{ route('koleksi.destroy', $koleksi) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                onclick="return confirm('Yakin?')" title="Hapus">
                                                <i class="fa fa-lg fa-fw fa-trash"></i>
                                            </button>
                                        </form>
                                    </nobr>
                                </td>
                            </tr>
                        @endforeach
                    </x-adminlte-datatable>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL UNTUK TAMBAH KOLEKSI BARU --}}
    <x-adminlte-modal id="modal-create" title="Tambah Jenis Koleksi" theme="primary" icon="fas fa-plus-circle">
        <form action="{{ route('koleksi.store') }}" method="POST">
            @csrf
            <x-adminlte-input name="nama_koleksi" label="Nama Koleksi" required />
            <x-adminlte-button type="submit" label="Simpan" theme="success" class="w-100" />
        </form>
    </x-adminlte-modal>
@stop

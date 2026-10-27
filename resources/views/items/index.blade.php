@extends('adminlte::page')

@section('title', 'Daftar Semua Eksemplar')

@section('content_header')
    <h1>Daftar Semua Eksemplar</h1>
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

    <div class="card fade-in-card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-box"></i> Daftar Eksemplar Buku
            </h3>
        </div>
        <div class="card-body">
            @php
                use Illuminate\Support\Str;

                // Ambil semua kolom asli dari item_buku (termasuk created_at, updated_at, dll)
                $columns = $items->first() ? array_keys($items->first()->getAttributes()) : [];

                // Header untuk DataTable: semua kolom item_buku + kolom relasi tambahan + aksi
                $heads = array_map(fn($col) => Str::headline($col), $columns);

                // Kalau kamu juga mau tetap nampilin info relasi (biar jelas dibaca manusia)
                $heads[] = 'Judul Buku (relasi)';
                $heads[] = 'Lokasi Rak (relasi)';

                $heads[] = ['label' => 'Aksi', 'no-export' => true, 'width' => 5];

                $config = [
                    'dom' => '<"d-flex justify-content-between mb-3"<l><f>>rtip',
                    'pageLength' => 100,
                    'lengthMenu' => [[10, 25, 50, -1], [10, 25, 50, 'Semua']],
                    'language' => ['url' => '//cdn.datatables.net/plug-ins/1.13.5/i18n/id.json'],
                ];
            @endphp

            <x-adminlte-datatable id="itemsTable" :heads="$heads" :config="$config" striped hoverable bordered
                with-buttons>
                @forelse ($items as $item)
                    <tr>
                        {{-- tampilkan semua kolom asli item_buku --}}
                        @foreach ($columns as $col)
                            <td>{{ data_get($item, $col) }}</td>
                        @endforeach

                        {{-- tambahan dari relasi --}}
                        <td>{{ $item->buku->judul_buku ?? '-' }}</td>
                        <td>{{ $item->rak->kode_rak ?? '-' }}</td>

                        {{-- aksi --}}
                        <td>
                            <nobr>
                                <a href="{{ route('items.edit', $item) }}"
                                    class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                    <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>

                                <form action="{{ route('items.destroy', $item) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-default text-danger mx-1 shadow"
                                        onclick="return confirm('Yakin mau dihapus?')" title="Hapus">
                                        <i class="fa fa-lg fa-fw fa-trash"></i>
                                    </button>
                                </form>
                            </nobr>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($heads) }}" class="text-center">Data tidak ditemukan.</td>
                    </tr>
                @endforelse
            </x-adminlte-datatable>
        </div>
    </div>
@stop

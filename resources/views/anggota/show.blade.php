@extends('adminlte::page')

@section('title', 'Detail Anggota: ' . $anggota->nama_anggota)

@section('content_header')
    <h1>Anggota: {{ $anggota->nama_anggota }} ({{ $anggota->id_anggota }})</h1>
@stop

@section('content')
    <div class="row">
        {{-- Kolom Profil --}}
        <div class="col-md-4">
            <div class="card card-primary">
                <div class="card-body text-center">
                    <img src="{{ $anggota->foto ? asset('storage/' . $anggota->foto) : asset('images/default-user.png') }}"
                        class="img-fluid img-circle mb-3" style="width:120px;height:120px;">
                    <h4>{{ $anggota->nama_anggota }}</h4>
                    <p class="text-muted">{{ $anggota->id_anggota }}</p>
                    <hr>
                    <p><strong>Status:</strong>
                        <span class="badge badge-{{ $anggota->status === 'aktif' ? 'success' : 'warning' }}">
                            {{ ucfirst($anggota->status) }}
                        </span>
                    </p>
                    <p><strong>Tingkat:</strong> {{ $anggota->tingkat }}</p>
                    <p><strong>Bergabung Sejak:</strong>
                        {{ \Carbon\Carbon::parse($anggota->tanggal_registrasi)->translatedFormat('d M Y') }}
                    </p>
                    <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-primary btn-block mt-3">Edit
                        Profil</a>
                </div>
            </div>
        </div>

        {{-- Kolom Riwayat --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Riwayat Peminjaman & Pengembalian</h3>
                </div>
                <div class="card-body p-0">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th style="width:40px">#</th>
                                <th>Buku</th>
                                <th>Barcode</th>
                                <th>Pinjam</th>
                                <th>Tempo</th>
                                <th>Kembali</th>
                                <th>Status</th>
                                <th>Late Days</th>
                                <th>Kondisi</th>
                                <th>Pengganti</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $no = 1; @endphp

                            @forelse($anggota->peminjaman as $pinj)
                                {{-- Header per transaksi --}}
                                <tr class="bg-light">
                                    <td colspan="10">
                                        <strong>Transaksi #{{ $pinj->id }}</strong> —
                                        Dipinjam: {{ $pinj->tanggal_pinjam }} |
                                        Jatuh Tempo: {{ $pinj->tanggal_kembali }} |
                                        Aktual Kembali: {{ $pinj->tanggal_pengembalian_aktual ?? '-' }}
                                    </td>
                                </tr>

                                {{-- Detail tiap item --}}
                                @foreach ($pinj->items as $item)
                                    <tr>
                                        <td>{{ $no++ }}.</td>
                                        <td>{{ $item->buku->judul_buku }}</td>
                                        <td>{{ $item->kode_barcode }}</td>
                                        <td>{{ $pinj->tanggal_pinjam }}</td>
                                        <td>{{ $pinj->tanggal_kembali }}</td>
                                        <td>{{ $pinj->tanggal_pengembalian_aktual ?? '-' }}</td>
                                        <td>
                                            <span
                                                class="badge badge-{{ $pinj->status === 'Dipinjam' ? 'warning' : 'success' }}">
                                                {{ $pinj->status }}
                                            </span>
                                        </td>
                                        <td>{{ $pinj->late_days ?? 0 }}</td>
                                        <td>{{ ucfirst($item->pivot->kondisi) }}</td>
                                        <td>
                                            @if ($item->pivot->pengganti_item_buku_id)
                                                @php
                                                    $pg = \App\Models\ItemBuku::find(
                                                        $item->pivot->pengganti_item_buku_id,
                                                    );
                                                @endphp
                                                {{ $pg?->kode_barcode ?? '-' }}
                                            @else
                                                -
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            @empty
                                <tr>
                                    <td colspan="10" class="text-center">
                                        Belum ada riwayat peminjaman.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop

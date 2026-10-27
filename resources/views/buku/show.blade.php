{{-- resources/views/admin/buku/show.blade.php --}}
@extends('adminlte::page')

@section('title', 'Detail Buku')

@section('content_header')
    <h1 class="m-0 text-dark">Detail: {{ $buku->judul_buku }}</h1>
@stop

@section('content')
    @php
        $koleksiNama = strtolower(optional($buku->koleksi)->nama_koleksi ?? '');
        $isDigital =
            str_contains($koleksiNama, 'digital') ||
            str_contains($koleksiNama, 'ebook') ||
            str_contains($koleksiNama, 'e-book');

        // item fisik pertama (untuk sumber/tanggal/harga)
        $firstItem = $buku->items->first();

        // Kalau controller tidak mengirim $raks, fallback aman (tanpa error)
        $raks = $raks ?? \App\Models\Rak::orderBy('kode_rak')->get();
    @endphp

    <div class="row">
        <div class="col-12">

            {{-- Notifikasi --}}
            @if (session('success'))
                <x-adminlte-alert theme="success" title="Sukses" dismissable>
                    {{ session('success') }}
                </x-adminlte-alert>
            @endif

            {{-- Card Informasi Utama --}}
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informasi Utama</h3>

                    <div class="card-tools d-flex align-items-center">

                        {{-- Tombol fisik hanya untuk buku fisik --}}
                        @if (!$isDigital)
                            {{-- FORM CETAK LABEL via AJAX --}}
                            <form id="form-cetak-label" class="d-flex align-items-center">
                                <div class="input-group input-group-sm mr-2" style="width: 120px;">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text">Jumlah</span>
                                    </div>
                                    <input type="number" name="jumlah_cetak" class="form-control" value="1"
                                        min="1">
                                </div>
                                <button id="btn-cetak-label" type="submit" class="btn btn-sm btn-info">
                                    <i class="fas fa-fw fa-tag"></i> Cetak Label
                                </button>
                            </form>

                            {{-- Tombol Cetak Barcode --}}
                            <a href="{{ route('buku.cetak-barcode', $buku) }}" target="_blank"
                                class="btn btn-sm btn-dark ml-2">
                                <i class="fas fa-fw fa-barcode"></i> Cetak Barcode
                            </a>
                        @endif

                        <a href="{{ route('buku.edit', $buku) }}" class="btn btn-sm btn-primary ml-2">Edit Buku</a>
                        <a href="{{ route('buku.index') }}" class="btn btn-sm btn-secondary ml-2">Kembali ke Daftar</a>
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        {{-- Kolom Kiri untuk Cover --}}
                        <div class="col-md-4 text-center">
                            @if ($buku->url_cover)
                                <img src="{{ asset('storage/' . $buku->url_cover) }}" alt="Cover Buku"
                                    class="img-fluid rounded shadow-sm" style="max-height: 400px;">
                            @else
                                <div class="bg-light d-flex justify-content-center align-items-center rounded shadow-sm"
                                    style="height: 400px;">
                                    <span class="text-muted">Tidak Ada Cover</span>
                                </div>
                            @endif

                            {{-- Info koleksi badge --}}
                            <div class="mt-3">
                                <span class="badge {{ $isDigital ? 'badge-info' : 'badge-success' }}">
                                    {{ optional($buku->koleksi)->nama_koleksi ?? 'N/A' }}
                                </span>
                            </div>
                        </div>

                        {{-- Kolom Kanan untuk Detail Teks --}}
                        <div class="col-md-8">
                            <h3>{{ $buku->judul_buku }}</h3>
                            @if ($buku->sub_judul)
                                <h5 class="text-muted">{{ $buku->sub_judul }}</h5>
                            @endif

                            <hr>

                            <dl class="row">
                                <dt class="col-sm-4">Penulis</dt>
                                <dd class="col-sm-8">{{ $buku->penulis->pluck('nama_penulis')->join(', ') ?: '-' }}</dd>

                                <dt class="col-sm-4">Penerbit</dt>
                                <dd class="col-sm-8">{{ optional($buku->penerbit)->nama_penerbit ?? 'N/A' }}</dd>

                                <dt class="col-sm-4">Tahun & Tempat Terbit</dt>
                                <dd class="col-sm-8">
                                    {{ $buku->tahun_terbit }}
                                    @if ($buku->tempat_terbit)
                                        , {{ $buku->tempat_terbit }}
                                    @endif
                                </dd>

                                <dt class="col-sm-4">ISBN</dt>
                                <dd class="col-sm-8">{{ $buku->isbn ?? '-' }}</dd>

                                <dt class="col-sm-4">Kategori</dt>
                                <dd class="col-sm-8">{{ $buku->kategori->pluck('nama_kategori')->join(', ') ?: '-' }}</dd>

                                <dt class="col-sm-4">Nomor Panggil</dt>
                                <dd class="col-sm-8">
                                    <span class="badge badge-info">{{ $buku->nomor_panggil }}</span>
                                </dd>

                                <dt class="col-sm-4">Jumlah Halaman</dt>
                                <dd class="col-sm-8">{{ $buku->jumlah_halaman }} halaman</dd>

                                <dt class="col-sm-4">Deskripsi</dt>
                                <dd class="col-sm-8">{!! nl2br(e($buku->deskripsi)) !!}</dd>

                                {{-- Data perolehan hanya masuk akal untuk fisik --}}
                                @if (!$isDigital)
                                    <dt class="col-sm-4">Sumber Perolehan</dt>
                                    <dd class="col-sm-8">
                                        {{ optional($firstItem)->sumber_perolehan ?? '-' }}
                                    </dd>

                                    <dt class="col-sm-4">Tanggal Perolehan</dt>
                                    <dd class="col-sm-8">
                                        {{ optional($firstItem)->tanggal_perolehan?->format('Y-m-d') ?? '-' }}
                                    </dd>

                                    <dt class="col-sm-4">Harga</dt>
                                    <dd class="col-sm-8">
                                        {{ $firstItem && $firstItem->harga !== null ? 'Rp ' . number_format($firstItem->harga, 0, ',', '.') : '-' }}
                                    </dd>
                                @endif
                            </dl>

                            {{-- SECTION EBOOK --}}
                            @if ($isDigital)
                                <div class="alert alert-info mt-3 mb-0">
                                    <div class="d-flex align-items-start">
                                        <i class="fas fa-file-alt mt-1 mr-2"></i>
                                        <div class="w-100">
                                            <div class="font-weight-bold mb-1">Buku Digital</div>

                                            @if ($buku->file_ebook)
                                                <div class="d-flex flex-wrap gap-2">
                                                    <a class="btn btn-sm btn-primary" target="_blank"
                                                        href="{{ asset('storage/' . $buku->file_ebook) }}">
                                                        <i class="fas fa-eye"></i> Lihat / Download eBook
                                                    </a>

                                                    @if ($buku->format_ebook)
                                                        <span class="badge badge-secondary align-self-center">
                                                            Format: {{ strtoupper($buku->format_ebook) }}
                                                        </span>
                                                    @endif
                                                </div>
                                            @else
                                                <div class="text-danger">
                                                    File eBook belum tersedia. Silakan upload di menu Edit Buku.
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif

                        </div>
                    </div>
                </div>
            </div>

            {{-- AREA FISIK: Tambah eksemplar + tabel stok hanya untuk buku fisik --}}
            @if (!$isDigital)

                {{-- Card Tambah Eksemplar --}}
                <x-adminlte-card title="Tambah Eksemplar (Stok) Baru" theme="success" icon="fas fa-plus-circle" collapsible>

                    <form action="{{ route('items.store', $buku) }}" method="POST">
                        @csrf

                        <div class="row">
                            <div class="col-md-6">
                                <x-adminlte-input name="jumlah" label="Jumlah Eksemplar" type="number" min="1"
                                    placeholder="Masukkan jumlah..." fgroup-class="mb-3" required />
                            </div>

                            <div class="col-md-6">
                                <x-adminlte-select name="rak_id" label="Lokasi Rak" fgroup-class="mb-3" required>
                                    <option value="" disabled selected>-- Pilih kode_Rak --</option>
                                    @foreach ($raks as $rak)
                                        <option value="{{ $rak->id }}">{{ $rak->kode_rak }} - {{ $rak->lokasi }}
                                        </option>
                                    @endforeach
                                </x-adminlte-select>
                            </div>
                        </div>

                        <x-adminlte-button label="Generate Stok & Barcode" theme="primary" icon="fas fa-barcode"
                            type="submit" />
                    </form>
                </x-adminlte-card>

                {{-- Tabel Daftar Eksemplar --}}
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Daftar Eksemplar (Stok)</h3>
                    </div>

                    <div class="card-body p-0">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style="width: 10px">No.</th>
                                    <th>Nomor Eksemplar</th>
                                    <th>Kode Barcode</th>
                                    <th>Kode Rak</th>
                                    <th>Lokasi Rak</th>
                                    <th>Status</th>
                                    <th style="width: 110px;">Aksi</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse($buku->items as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}.</td>
                                        <td>{{ $item->nomor_eksemplar }}</td>
                                        <td>{{ $item->kode_barcode }}</td>
                                        <td>{{ optional($item->rak)->kode_rak ?? 'N/A' }}</td>
                                        <td>{{ optional($item->rak)->lokasi ?? 'N/A' }}</td>
                                        <td>
                                            <span
                                                class="badge {{ $item->status == 'Tersedia' ? 'badge-success' : 'badge-warning' }}">
                                                {{ $item->status }}
                                            </span>
                                        </td>
                                        <td class="d-flex gap-1">
                                            <a href="{{ route('items.edit', $item->id) }}"
                                                class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                            </a>

                                            {{-- Ganti action ini dengan route destroy item kamu yang benar --}}
                                            <form action="{{ route('items.destroy', $item->id) }}" method="POST">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="btn btn-xs btn-default text-danger mx-1 shadow"
                                                    onclick="return confirm('Yakin hapus item ini?')" title="Hapus">
                                                    <i class="fa fa-lg fa-fw fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center">
                                            Belum ada eksemplar untuk buku ini.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            @endif

        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Cetak label (fisik saja)
            const form = document.getElementById('form-cetak-label');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    const jumlah = this.querySelector('input[name="jumlah_cetak"]').value;
                    const url = `{{ route('buku.cetak-label', $buku->id) }}?jumlah_cetak=${jumlah}`;

                    fetch(url, {
                            headers: {
                                'Accept': 'application/pdf'
                            }
                        })
                        .then(res => {
                            if (!res.ok) throw new Error('Request gagal');
                            return res.blob();
                        })
                        .then(blob => {
                            const blobUrl = URL.createObjectURL(blob);
                            const a = document.createElement('a');
                            a.href = blobUrl;
                            a.download =
                                `{{ \Illuminate\Support\Str::slug($buku->judul_buku) }}-label.pdf`;
                            document.body.appendChild(a);
                            a.click();
                            document.body.removeChild(a);
                            URL.revokeObjectURL(blobUrl);
                        })
                        .catch(err => {
                            console.error(err);
                            alert('Gagal mengunduh label. Coba lagi.');
                        });
                });
            }
        });
    </script>
@stop

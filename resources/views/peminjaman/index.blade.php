@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Transaksi Peminjaman</h3>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-striped table-hover">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Anggota</th>
                        <th>Item Buku</th>
                        <th>Tgl Pinjam</th>
                        <th>Tgl Kembali</th>
                        <th>Status</th>
                        <th>Late Days</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($peminjamans as $p)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $p->anggota->nama_anggota }}</td>
                            <td>
                                @foreach ($p->items as $item)
                                    <div class="mb-1">
                                        <strong>{{ $item->buku->judul_buku }}</strong><br>
                                        <small>({{ $item->kode_barcode }})</small><br>
                                        <span
                                            class="badge badge-sm badge-{{ $item->pivot->kondisi === 'baik' ? 'success' : ($item->pivot->kondisi === 'rusak' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($item->pivot->kondisi) }}
                                        </span>
                                        @if ($item->pivot->pengganti_item_buku_id)
                                            @php
                                                $pg = \App\Models\ItemBuku::with('buku')->find(
                                                    $item->pivot->pengganti_item_buku_id,
                                                );
                                            @endphp

                                            @if ($pg)
                                                <br><small class="text-info">
                                                    Diganti: {{ $pg->kode_barcode }} ({{ $pg->buku->judul_buku }})
                                                </small>
                                            @else
                                                <br><small class="text-warning">Pengganti tidak ditemukan</small>
                                            @endif
                                        @endif

                                    </div>
                                @endforeach
                            </td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('Y-m-d') }}</td>
                            <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali)->format('Y-m-d') }}</td>
                            <td>{{ $p->status }}</td>
                            <td>{{ $p->late_days ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

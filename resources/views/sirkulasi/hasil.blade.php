{{-- resources/views/hasil.blade.php --}}
@extends('adminlte::page')

{{-- Aktifkan plugin DataTables + Buttons --}}
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('title', 'Hasil Sirkulasi')

@section('content_header')
    <h1><i class="fas fa-list-alt mr-1"></i> Hasil Sirkulasi</h1>
@stop


@section('content')
    <div class="card shadow-sm">
        <div class="card-header bg-white">
            <h3 class="card-title"><i class="fas fa-table mr-1"></i> Daftar Transaksi</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table id="hasil-table" class="table table-bordered table-hover text-nowrap">
                    <thead class="thead-dark">
                        <tr>
                            <th style="width:40px">No.</th>
                            <th>Anggota</th>
                            <th>Buku</th>
                            <th>Pinjam</th>
                            <th>Tempo</th>
                            <th>Kembali</th>
                            <th>Status</th>
                            <th>Kondisi</th>
                            <th>Buku Pengganti</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no=1; @endphp
                        @foreach ($peminjamans as $pinjam)
                            @foreach ($pinjam->items as $item)
                                <tr>
                                    <td class="text-center">{{ $no++ }}.</td>
                                    <td>{{ $pinjam->anggota->nama_anggota }}</td>
                                    <td>{{ $item->buku->judul_buku }} <br><small>({{ $item->kode_barcode }})</small></td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') }}</td>
                                    <td class="text-center">
                                        {{ \Carbon\Carbon::parse($pinjam->tanggal_kembali)->format('d M Y') }}</td>
                                    <td class="text-center">
                                        @if ($pinjam->tanggal_pengembalian_aktual)
                                            {{ \Carbon\Carbon::parse($pinjam->tanggal_pengembalian_aktual)->format('d M Y') }}
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        @php
                                            $lateDays = (int) ($pinjam->late_days ?? 0);
                                            $isLateReturn = $pinjam->status === 'Kembali' && $lateDays > 0;
                                        @endphp

                                        @if ($isLateReturn)
                                            <span class="badge badge-danger">Telat ({{ $lateDays }} hari)</span>
                                        @elseif ($pinjam->status === 'Dipinjam')
                                            <span class="badge badge-warning">{{ $pinjam->status }}</span>
                                        @else
                                            <span class="badge badge-success">{{ $pinjam->status }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">{{ ucfirst($item->pivot->kondisi) }}</td>
                                    <td>
                                        @if ($item->pivot->pengganti_item_buku_id)
                                            @php
                                                $pg = \App\Models\ItemBuku::find($item->pivot->pengganti_item_buku_id);
                                            @endphp
                                            <a href="{{ route('buku.show', $pg->buku->id) }}" class="text-info">
                                                {{ $pg->buku->judul_buku }}<br>
                                                <small>({{ $pg->kode_barcode }})</small>
                                            </a>
                                        @else
                                            <span class="text-muted">—</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(function() {
            $('#hasil-table').DataTable({
                responsive: true,
                autoWidth: false,
                pageLength: 20,
                lengthMenu: [
                    [10, 25, 50, -1],
                    [10, 25, 50, "All"]
                ],
                dom: "<'row mb-2'<'col-sm-6'B><'col-sm-6'f>>" +
                    "<'row'<'col-sm-12'tr>>" +
                    "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",
                buttons: [{
                        extend: 'excel',
                        text: '<i class="fas fa-file-excel"></i> Excel'
                    },
                    {
                        extend: 'pdf',
                        text: '<i class="fas fa-file-pdf"></i> PDF'
                    },
                    {
                        extend: 'print',
                        text: '<i class="fas fa-print"></i> Print'
                    }
                ],
                language: {
                    searchPlaceholder: "Cari transaksi...",
                    search: ""
                },
                columnDefs: [{
                    targets: [0, 3, 4, 5, 6, 7],
                    className: 'text-center'
                }],
                rowCallback: function(row, data) {
                    const statusText = $('td:eq(6)', row).text().trim(); // kolom status
                    const tempo = new Date(data[4]);
                    const now = new Date();

                    if (statusText.startsWith('Telat')) {
                        $(row).addClass('table-danger');
                        return;
                    }

                    if (statusText === 'Dipinjam' && tempo < now) {
                        $(row).addClass('table-danger');
                    }
                }
            });
        });
    </script>
@stop

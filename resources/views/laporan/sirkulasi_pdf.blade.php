{{-- resources/views/laporan/sirkulasi_pdf.blade.php --}}
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Laporan Sirkulasi</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 1em;
        }

        th,
        td {
            border: 1px solid #444;
            padding: 4px;
            text-align: left;
        }

        th {
            background: #eee;
        }

        .text-center {
            text-align: center;
        }
    </style>
</head>

<body>
    <h2 class="text-center">Laporan Sirkulasi</h2>
    <p>
        Periode: {{ \Illuminate\Support\Str::limit($req->tanggal_mulai, 10) }}
        s/d {{ \Illuminate\Support\Str::limit($req->tanggal_selesai, 10) }}<br>
        Tipe: {{ ucfirst($req->tipe) }}
    </p>

    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>No. Pinjam</th>
                <th>Anggota</th>
                <th>Tgl Pinjam</th>
                <th>Jatuh Tempo</th>
                <th>Tgl Kembali</th>
                <th>Status</th>
                <th>Buku</th>
                <th>Barcode</th>
                <th>Kondisi</th>
                <th>Pengganti</th>
            </tr>
        </thead>
        <tbody>
            @php $no=1; @endphp
            @foreach ($data as $pinj)
                @foreach ($pinj->items as $item)
                    <tr>
                        <td>{{ $no++ }}</td>
                        <td>{{ $pinj->id }}</td>
                        <td>{{ $pinj->anggota->nama_anggota }}</td>
                        <td>{{ $pinj->tanggal_pinjam }}</td>
                        <td>{{ $pinj->tanggal_kembali }}</td>
                        <td>{{ $pinj->tanggal_pengembalian_aktual ?? '-' }}</td>
                        <td>{{ $pinj->status }}</td>
                        <td>{{ $item->buku->judul_buku }}</td>
                        <td>{{ $item->kode_barcode }}</td>
                        <td>{{ ucfirst($item->pivot->kondisi) }}</td>
                        <td>
                            @if ($item->pivot->pengganti_item_buku_id)
                                {{ \App\Models\ItemBuku::find($item->pivot->pengganti_item_buku_id)->kode_barcode }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            @endforeach
        </tbody>
    </table>

    <p class="text-center">Dicetak: {{ now()->format('d-m-Y H:i') }}</p>
</body>

</html>

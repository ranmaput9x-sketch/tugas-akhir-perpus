<!DOCTYPE html>
<html>

<head>
    <title>Cetak Barcode</title>
    <style>
        body {
            font-family: 'Helvetica', sans-serif;
        }

        .timestamp {
            position: absolute;
            top: 0;
            left: 0;
            font-size: 10px;
            color: #555;
        }

        .header {
            text-align: center;
        }

        .header .title {
            font-size: 24px;
            font-weight: bold;
            margin: 0;
        }

        .header .subtitle {
            font-size: 14px;
            margin: 0;
        }

        .header .book-title {
            font-size: 12px;
            margin-top: 5px;
            font-style: italic;
        }

        .container {
            margin-top: 15px;
            display: flex;
            flex-wrap: wrap;
        }

        .barcode-item {
            width: 45%;
            margin: 10px 2%;
            border-radius: 5px;
            border: 1px solid #ddd;
            overflow: hidden;
            display: flex;
            /* Tambahan */
            flex-direction: column;
            /* Tambahan */
        }

        .item-header {
            background-color: #f2f2f2;
            padding: 5px;
            text-align: center;
        }

        .item-header .main-title {
            font-size: 9px;
            font-weight: bold;
            text-transform: uppercase;
        }

        .item-header .sub-title {
            font-size: 8px;
            color: #333;
        }

        .item-body {
            flex-grow: 1;
            /* Tambahan */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 10px;
            text-align: center;
        }

        .barcode-wrapper {
            padding-left: 20px;
        }

        .item-body .code-text {
            font-size: 10px;
            margin-top: 8px;
            /* Sedikit spasi biar gak nempel */
        }
    </style>
</head>

<body>
    <div class="timestamp">
        Dicetak pada: {{ \Carbon\Carbon::now()->translatedFormat('l, d F Y') }}
    </div>

    <div class="header">
        <p class="title">HASIL CETAK BARCODE</p>
        <p class="subtitle">Perpustakaan SMPN 01 Keling</p>
        <p class="book-title">{{ $buku->judul_buku }}</p>
    </div>
    <hr>
    <div class="container">
        @forelse ($items as $item)
            <div class="barcode-item">
                <div class="item-header">
                    <div class="main-title">PERPUSTAKAAN SEKOLAH SMPN 01 KELING</div>
                    <div class="sub-title">{{ $item->buku->kategori->pluck('nama_kategori')->join(', ') }}</div>
                </div>
                <div class="item-body">
                    <div class="barcode-wrapper">
                        {!! DNS1D::getBarcodeHTML($item->kode_barcode, 'C128', 2.2, 50) !!}
                    </div>
                    <div class="code-text">{{ $item->kode_barcode }}</div>
                </div>
            </div>
        @empty
            <p>Tidak ada eksemplar untuk dicetak.</p>
        @endforelse
    </div>
</body>

</html>

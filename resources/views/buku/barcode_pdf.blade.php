<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Cetak Barcode Buku</title>
    <style>
        @page {
            margin: 10px;
        }

        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        .label {
            width: 180px;
            height: 90px;
            border: 1px solid #000;
            margin: 5px;
            padding: 5px;
            display: inline-block;
            text-align: center;
        }

        .barcode {
            margin-top: 5px;
        }

        .judul {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 3px;
        }

        .penulis {
            font-size: 10px;
            margin-bottom: 5px;
        }
    </style>
</head>

<body>
    @foreach ($items as $item)
        <div class="label">
            <div class="judul">{{ Str::limit($item->judul_buku, 25) }}</div>
            <div class="penulis">{{ Str::limit($item->penulis, 20) }}</div>
            <div class="barcode">
                <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($item->kode_barcode, 'C128') }}" height="30">
                <div style="font-size: 9px;">{{ $item->kode_barcode }}</div>
            </div>
        </div>
    @endforeach
</body>

</html>

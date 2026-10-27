<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        body {
            font-family: sans-serif;
            margin: 0;
            padding: 0;
        }

        .label-container {
            width: 210mm;
            display: flex;
            flex-wrap: wrap;
            padding: 10mm;
        }

        .label {
            text-align: center;
            border: 1px dashed #999;
            margin: 5mm;
            padding: 3mm;
            box-sizing: border-box;
        }

        .small {
            width: 40mm;
            height: 20mm;
            font-size: 8px;
        }

        .medium {
            width: 60mm;
            height: 30mm;
            font-size: 10px;
        }

        .large {
            width: 80mm;
            height: 40mm;
            font-size: 12px;
        }

        .barcode-img {
            width: 100%;
            height: auto;
        }
    </style>
</head>

<body>
    <div class="label-container">
        @foreach ($bukus as $buku)
            @for ($i = 0; $i < $jumlah; $i++)
                <div class="label {{ $ukuran }}">
                    <img src="data:image/png;base64,{{ DNS1D::getBarcodePNG($buku['barcode'], 'C128') }}"
                        class="barcode-img" alt="barcode">
                    <div>{{ $buku['barcode'] }}</div>
                </div>
            @endfor
        @endforeach
    </div>
</body>

</html>s

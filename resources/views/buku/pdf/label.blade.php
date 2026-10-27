{{-- resources/views/buku/pdf/label.blade.php --}}
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Label {{ Str::slug($buku->judul_buku, '-') }}</title>
    <style>
        /* 1) Box‑model semua elemen jadi border-box */
        *,
        *::before,
        *::after {
            box-sizing: border-box;
        }

        /* 2) Ukuran page */
        @page {
            size: 30mm 40mm;
            margin: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: 30mm;
            height: 40mm;
            overflow: hidden;
            font-family: 'Helvetica', sans-serif;
        }

        /* 3) Label full‑bleed pakai absolute */
        .label {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            box-sizing: border-box;
            border: 1mm solid #000;
            /* border full‑bleed */
            padding-top: 4mm;
            /* geser konten turun 2 mm */
            display: flex;
            flex-direction: column;
        }


        /* 4) Header */
        .label-header {
            flex: none;
            text-align: center;
            font-size: 7pt;
            font-weight: bold;
            padding: 1mm 0;
        }

        /* 5) Body panggil, bener‑bener center */
        .label-body {
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .label-body .text-block {
            text-align: center;
        }

        .label-body .text-block div {
            margin: 0;
            font-size: 12pt;
            font-weight: bold;
            line-height: 1;
        }
    </style>
</head>

<body>
    <div class="label">
        <div class="label-header">
            PERPUSTAKAAN SMPN 01 KELING
        </div>
        <div class="label-body">
            <div class="text-block">
                @foreach (preg_split('/\s+/', $buku->nomor_panggil) as $part)
                    <div>{{ $part }}</div>
                @endforeach
            </div>
        </div>
    </div>
</body>

</html>

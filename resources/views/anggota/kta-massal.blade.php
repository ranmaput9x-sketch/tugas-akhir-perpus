<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Cetak Kartu Anggota Massal (Bolak-Balik)</title>
    {{-- Ikonik: Menggunakan Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* -- PENGATURAN WARNA & BRANDING -- */
        :root {
            --primary-color: #3BB8EE;
            /* Aksen Warna: Biru tua, ganti dengan warna sekolah lo */
            --secondary-color: #DBEF19;
            --text-color-dark: #343a40;
            --text-color-light: #ffffff;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f6f9;
            padding: 20px;
        }

        .page-container {
            display: flex;
            flex-direction: column;
            gap: 20px;
            align-items: center;
        }

        .card-wrapper {
            display: flex;
            gap: 20px;
            page-break-inside: avoid;
            /* Mencegah pasangan kartu terpotong saat print */
        }

        .card-container {
            width: 330px;
            height: 230px;
            background: var(--text-color-light);
            border-radius: 15px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 15px;
            display: flex;
            flex-direction: column;
            border: 1px solid #ddd;
            position: relative;
            color: var(--text-color-dark);
            box-sizing: border-box;
        }

        /* -- KODE ANTI-PEMALSUAN -- */
        .card-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-image: url("{{ asset('images/watermark.png') }}");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 60%;
            opacity: 0.1;
            z-index: 1;
        }

        .card-content {
            position: relative;
            z-index: 2;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        /* -- SISI DEPAN KARTU -- */
        .card-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            text-align: center;
            font-weight: bold;
            font-size: 11px;
            padding-bottom: 5px;
            border-bottom: 2px solid var(--primary-color);
            margin-bottom: 5px;
        }

        .header-logo {
            width: 40px;
            height: 40px;
            object-fit: contain;
        }

        .header-text {
            line-height: 1.2;
        }

        .card-body {
            display: flex;
            flex-grow: 1;
        }

        .photo-section {
            width: 80px;
            flex-shrink: 0;
            margin-right: 10px;
        }

        .photo-section img {
            width: 80px;
            height: 100px;
            object-fit: cover;
            border: 1px solid #eee;
        }

        .info-section .name {
            font-weight: bold;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 2px;
        }

        .info-section .id-anggota {
            font-size: 12px;
        }

        .info-section .tingkat {
            background-color: var(--primary-color);
            color: var(--text-color-light);
            padding: 2px 6px;
            border-radius: 5px;
            display: inline-block;
            margin-top: 4px;
            font-size: 10px;
        }

        .info-section .validity {
            font-size: 9px;
            margin-top: 4px;
            line-height: 1.4;
        }

        .card-footer {
            margin-top: auto;
            display: flex;
            justify-content: space-between;
            align-items: flex-end;
        }

        .signature-strip {
            width: 120px;
            height: 30px;
            background-color: #f7f7f7;
            border-top: 1px solid #ccc;
            font-size: 8px;
            color: #999;
            text-align: center;
            padding-top: 2px;
        }

        .barcode-section {
            text-align: right;
        }

        .barcode {
            height: 30px;
            object-fit: contain;
        }

        .barcode-text {
            font-size: 9px;
            letter-spacing: 1px;
            margin-top: 1px;
        }

        /* -- SISI BELAKANG KARTU -- */
        .card-back .card-body {
            display: flex;
            justify-content: space-between;
            font-size: 9pt;
        }

        .card-back .rule-col {
            width: 48%;
        }

        .card-back h4 {
            font-size: 10pt;
            margin-top: 0;
            margin-bottom: 8px;
            color: var(--primary-color);
            border-bottom: 1px solid #eee;
            padding-bottom: 4px;
        }

        .card-back .rule-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 8px;
        }

        .card-back .rule-icon {
            width: 15px;
            text-align: center;
            margin-right: 5px;
            margin-top: 1px;
            color: var(--primary-color);
        }

        .card-back .rule-text {
            flex: 1;
        }

        .card-back .footer-info {
            margin-top: auto;
            font-size: 8px;
            color: #777;
            text-align: center;
            padding-top: 5px;
            border-top: 1px dotted #ccc;
        }

        /* Tombol Print */
        .print-button {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 10px 20px;
            background-color: #28a745;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            z-index: 1000;
        }

        .print-button i {
            margin-right: 8px;
        }

        @media print {
            body {
                background-color: white;
                padding: 0;
                margin: 0;
            }

            .print-button {
                display: none;
            }

            .card-container {
                box-shadow: none;
                border: 1px solid #000;
                margin: 5px;
            }

            .page-container,
            .card-wrapper {
                gap: 0;
            }
        }
    </style>
</head>

<body>
    <button class="print-button" onclick="window.print()">
        <i class="fas fa-print"></i> Cetak Semua Kartu
    </button>

    <div class="page-container">
        @foreach ($anggotas as $anggota)
            <div class="card-wrapper">
                {{-- ================= SISI DEPAN KARTU ================= --}}
                <div class="card-container card-front">
                    <div class="card-content">
                        <div class="card-header">
                            <img src="{{ asset('images/logo_sekolah.png') }}" alt="Logo Sekolah" class="header-logo">
                            <div class="header-text">KARTU TANDA ANGGOTA<br>PERPUSTAKAAN SEKOLAH</div>
                            <img src="{{ asset('images/logo_perpustakaan.png') }}" alt="Logo Perpus"
                                class="header-logo">
                        </div>
                        <div class="card-body">
                            <div class="photo-section">
                                <img src="{{ $anggota->foto ? asset('storage/' . $anggota->foto) : asset('images/default-user.png') }}"
                                    alt="Foto Anggota Asli">
                            </div>
                            <div class="info-section">
                                <div class="name">{{ $anggota->nama_anggota }}</div>
                                <div class="id-anggota">{{ $anggota->id_anggota }}</div>
                                <div class="tingkat">{{ ucfirst($anggota->tingkat) }}</div>
                                <div class="validity">
                                    <strong>Terbit:</strong>
                                    {{ \Carbon\Carbon::parse($anggota->tanggal_registrasi)->format('d/m/Y') }}<br>
                                    <strong>Berlaku s.d.:</strong>
                                    {{ $anggota->tingkat == 'Guru' ? 'Seumur Hidup' : \Carbon\Carbon::parse($anggota->berlaku_hingga)->format('d/m/Y') }}
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="signature-strip">Tanda Tangan Anggota</div>
                            <div class="barcode-section">
                                @php $barcodePath = 'barcode/' . $anggota->id_anggota . '.png'; @endphp
                                @if (Storage::disk('public')->exists($barcodePath))
                                    <img src="{{ asset('storage/' . $barcodePath) }}" class="barcode" alt="Barcode">
                                    <div class="barcode-text">{{ $anggota->id_anggota }}</div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= SISI BELAKANG KARTU ================= --}}
                <div class="card-container card-back">
                    <div class="card-content">
                        <div class="card-body">
                            {{-- KOLOM KIRI --}}
                            <div class="rule-col">
                                <h4>Aturan Pinjam–Kembali</h4>
                                <div class="rule-item">
                                    <span class="rule-icon"><i class="fas fa-book"></i></span>
                                    <span class="rule-text"><strong>Maks. 2 buku</strong> / 7 hari.</span>
                                </div>
                                <div class="rule-item">
                                    <span class="rule-icon"><i class="fas fa-undo-alt"></i></span>
                                    <span class="rule-text">Perpanjangan <strong>maks. 1 kali</strong>.</span>
                                </div>
                                <div class="rule-item">
                                    <span class="rule-icon"><i class="fas fa-clock"></i></span>
                                    <span class="rule-text">Denda keterlambatan berlaku.</span>
                                </div>
                            </div>
                            {{-- KOLOM KANAN --}}
                            <div class="rule-col">
                                <h4>Tata Tertib</h4>
                                <div class="rule-item">
                                    <span class="rule-icon"><i class="fas fa-id-card"></i></span>
                                    <span class="rule-text"><strong>Bawa kartu</strong> tiap kunjungan.</span>
                                </div>
                                <div class="rule-item">
                                    <span class="rule-icon"><i class="fas fa-utensils"></i></span>
                                    <span class="rule-text">Dilarang <strong>makan/minum</strong>.</span>
                                </div>
                                <div class="rule-item">
                                    <span class="rule-icon"><i class="fas fa-volume-mute"></i></span>
                                    <span class="rule-text">Jaga <strong>ketenangan</strong> & kebersihan.</span>
                                </div>
                            </div>
                        </div>
                        <div class="footer-info">
                            Jika menemukan kartu ini, harap hubungi: <strong>0812-3456-7890</strong> (Helpdesk Perpus)
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</body>

</html>

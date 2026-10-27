@extends('adminlte::page')

@section('title', 'Peminjaman Buku')

@section('content_header')
    <h1>Peminjaman Buku</h1>
@stop

@section('content')
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses">
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    @if ($errors->any())
        <x-adminlte-alert theme="danger" title="Error">
            <ul>
                @foreach ($errors->all() as $err)
                    <li>{{ $err }}</li>
                @endforeach
            </ul>
        </x-adminlte-alert>
    @endif

    <div class="row">
        <div class="col-md-6">
            <form id="form-peminjaman" method="POST" action="{{ route('peminjaman.store') }}">
                @csrf

                <x-adminlte-input name="anggota_input" label="Scan ID Anggota" id="anggota-input" placeholder="AGT-..."
                    autofocus />
                <div id="anggota-nama" class="text-success font-weight-bold mb-2">[Nama belum muncul]</div>
                <input type="hidden" name="anggota_id" id="anggota-id">

                <x-adminlte-input name="barcode_input" label="Scan Barcode Buku" id="barcode-input"
                    placeholder="Kode barcode buku..." />
                <input type="hidden" name="items[]" id="dummy-item">

                <button type="submit" class="btn btn-primary mt-3" id="btn-submit">Proses Peminjaman</button>
            </form>

            <div class="mt-4">
                <h5>Scan Kamera (Support OBS / Webcam)</h5>
                <button id="toggle-camera" class="btn btn-outline-secondary btn-sm mb-2">Nyalakan Kamera</button>
                <span id="camera-status" class="text-muted small">Kamera Mati</span>
                <div id="scanner-container" style="width:100%; max-width:400px; border:1px dashed #ccc;"></div>
            </div>
        </div>

        <div class="col-md-6">
            <h5>Ringkasan Buku yang Dipinjam</h5>
            <div class="table-responsive">
                <table class="table table-bordered table-striped" id="daftar-buku-table">
                    <thead class="thead-dark">
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Penulis</th>
                            <th>Penerbit</th>
                            <th>Kategori</th>
                            <th>ISBN</th>
                            <th>Tahun</th>
                            <th>Halaman</th>
                            <th>Deskripsi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="daftar-buku-body"></tbody>
                </table>
            </div>
        </div>
    </div>

    <audio id="bip-audio" src="{{ asset('sound/beep.mp3') }}" preload="auto"></audio>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/quagga/0.12.1/quagga.min.js"></script>
    <script>
        let daftarBuku = [];
        let anggotaScanned = false;
        let scannerAktif = false;
        let scanCooldown = false;
        let lastScannedCode = '';

        function playBip() {
            const bip = document.getElementById('bip-audio');
            if (bip) bip.play().catch(() => {});
        }

        async function validasiAnggota(kode) {
            try {
                const res = await fetch(`/api/anggota/${kode}`);
                if (!res.ok) throw new Error('Gagal fetch anggota');
                const data = await res.json();

                if (data && data.id) {
                    document.getElementById('anggota-id').value = data.id;
                    document.getElementById('anggota-input').value = data.id_anggota;
                    document.getElementById('anggota-nama').innerText = data.id_anggota + ' - ' + data.nama_anggota;
                    document.getElementById('anggota-input').readOnly = true;
                    anggotaScanned = true;
                    playBip();
                    document.getElementById('barcode-input').focus();
                } else {
                    alert("Anggota tidak ditemukan");
                }
            } catch (err) {
                alert("Gagal ambil data anggota.");
            }
        }

        function hapusBaris(kode) {
            daftarBuku = daftarBuku.filter(k => k !== kode);
            const input = document.querySelector(`input[name='items[]'][value='${kode}']`);
            if (input) input.remove();
            const row = document.getElementById(`row-${kode}`);
            if (row) row.remove();
        }

        async function validasiBuku(kode) {
            if (daftarBuku.includes(kode)) return alert("Item sudah ditambahkan!");
            if (daftarBuku.length >= 3) return alert("Maksimal 3 buku!");

            const res = await fetch(`/api/item/${kode}`);
            const data = await res.json();

            if (data && data.status === 'Tersedia') {
                daftarBuku.push(kode);

                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'items[]';
                input.value = kode;
                document.getElementById('form-peminjaman').appendChild(input);

                const barcodeInput = document.getElementById('barcode-input');
                barcodeInput.value = `${data.kode_barcode} - ${data.buku?.judul_buku || ''}`;

                const tbody = document.getElementById('daftar-buku-body');
                const row = document.createElement('tr');
                row.id = `row-${kode}`;

                row.innerHTML = `
                    <td>${tbody.children.length + 1}</td>
                    <td>${data.buku?.judul_buku || '-'}</td>
                    <td>${data.buku?.pengarang || '-'}</td>
                    <td>${data.buku?.penerbit || '-'}</td>
                    <td>${data.buku?.kategori || '-'}</td>
                    <td>${data.buku?.isbn || '-'}</td>
                    <td>${data.buku?.tahun_terbit || '-'}</td>
                    <td>${data.buku?.jumlah_halaman || '-'}</td>
                    <td>${data.buku?.deskripsi || '-'}</td>
                    <td><button type="button" class="btn btn-danger btn-sm" onclick="hapusBaris('${kode}')">❌</button></td>
                `;
                tbody.appendChild(row);

                playBip();
                setTimeout(() => {
                    barcodeInput.value = '';
                }, 1000);

                if (daftarBuku.length === 3 && anggotaScanned) {
                    setTimeout(() => document.getElementById('form-peminjaman').submit(), 800);
                }
            } else {
                alert("Item tidak tersedia atau tidak ditemukan.");
            }
        }

        document.getElementById('anggota-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                validasiAnggota(this.value.trim());
                this.value = '';
            }
        });

        document.getElementById('barcode-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                validasiBuku(this.value.trim());
                this.value = '';
            }
        });

        function initScannerWithDevice(deviceId) {
            Quagga.init({
                inputStream: {
                    name: "Live",
                    type: "LiveStream",
                    target: document.querySelector('#scanner-container'),
                    constraints: {
                        deviceId: deviceId
                    }
                },
                decoder: {
                    readers: ["code_128_reader", "ean_reader", "ean_8_reader", "upc_reader"]
                }
            }, function(err) {
                if (err) return alert("Gagal akses kamera");
                Quagga.start();
                scannerAktif = true;
                document.getElementById('camera-status').textContent = 'Kamera Aktif';
            });

            Quagga.onDetected(function(data) {
                const kode = data.codeResult.code;
                if (!kode || scanCooldown || kode === lastScannedCode) return;
                scanCooldown = true;
                lastScannedCode = kode;

                if (/^20\d{6}$/.test(kode)) {
                    validasiAnggota(kode);
                } else if (/^25\d{9}$/.test(kode) || /^\d{12}$/.test(kode)) {
                    validasiBuku(kode);
                }

                setTimeout(() => {
                    scanCooldown = false;
                    lastScannedCode = '';
                }, 1500);
            });
        }

        async function startScanner() {
            if (scannerAktif) return;
            const devices = await navigator.mediaDevices.enumerateDevices();
            const obsCam = devices.find(d => d.kind === "videoinput" && d.label.includes("OBS")) || devices[0];
            if (!obsCam) return alert("Kamera tidak ditemukan.");
            initScannerWithDevice(obsCam.deviceId);
        }

        function stopScanner() {
            if (scannerAktif) {
                Quagga.stop();
                scannerAktif = false;
                document.getElementById('camera-status').textContent = 'Kamera Mati';
            }
        }

        document.getElementById('toggle-camera').addEventListener('click', function() {
            if (scannerAktif) {
                stopScanner();
                this.textContent = 'Nyalakan Kamera';
            } else {
                startScanner();
                this.textContent = 'Matikan Kamera';
            }
        });

        window.onload = function() {
            document.getElementById('anggota-input').focus();
        };

        document.body.addEventListener('click', function enableAudio() {
            const bip = document.getElementById('bip-audio');
            if (bip) bip.play().catch(() => {});
            document.body.removeEventListener('click', enableAudio);
        });
    </script>
@stop

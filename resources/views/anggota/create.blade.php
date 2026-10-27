@extends('adminlte::page')

@section('title', 'Tambah Anggota Baru')

@section('content_header')
    <h1 class="m-0 text-dark">Tambah Data Anggota</h1>
@stop

@section('content')
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses" id="successAlert" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Formulir Pendaftaran Anggota Baru</h3>
        </div>
        <div class="card-body">
            <form id="formAnggota" method="POST" action="{{ route('anggota.store') }}" enctype="multipart/form-data"
                data-check-duplicate-url="{{ route('anggota.cek-duplicate') }}"
                data-default-image-url="{{ asset('images/default-user.png') }}">
                @csrf
                <div class="row">
                    {{-- KOLOM KIRI: INPUT DATA --}}
                    <div class="col-md-8">

                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Data Diri</h3>
                            </div>
                            <div class="card-body">
                                <x-adminlte-input name="nama_anggota" label="Nama Lengkap"
                                    placeholder="Masukkan nama sesuai akta" value="{{ old('nama_anggota') }}" required>
                                    <x-slot name="bottomSlot">
                                        <small id="namaWarning" class="form-text text-danger" style="display:none;">Nama ini
                                            sudah terdaftar.</small>
                                    </x-slot>
                                </x-adminlte-input>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input name="nomor_induk" id="nomor_induk" label="No. Induk (NIS/NIP)"
                                            placeholder="Wajib diisi" value="{{ old('nomor_induk') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input name="no_telepon" label="No. Telepon / HP"
                                            placeholder="Contoh: 08123456789" value="{{ old('no_telepon') }}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input name="tanggal_lahir" label="Tanggal Lahir" type="date"
                                            value="{{ old('tanggal_lahir') }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-select name="jenis_kelamin" label="Jenis Kelamin" required>
                                            <option @if (old('jenis_kelamin') == 'Laki-laki') selected @endif>Laki-laki</option>
                                            <option @if (old('jenis_kelamin') == 'Perempuan') selected @endif>Perempuan</option>
                                        </x-adminlte-select>
                                    </div>
                                </div>
                                <x-adminlte-textarea name="alamat" label="Alamat" rows="2"
                                    placeholder="Masukkan alamat lengkap...">{{ old('alamat') }}</x-adminlte-textarea>
                            </div>
                        </div>

                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Akun & Keanggotaan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input name="password" label="Password Akun" type="password"
                                            placeholder="Minimal 6 karakter" required />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input name="email" id="email_preview" label="Email Akun (Otomatis)"
                                            readonly>
                                            <x-slot name="prependSlot">
                                                <div class="input-group-text bg-gradient-blue"><i class="fas fa-at"></i>
                                                </div>
                                            </x-slot>
                                        </x-adminlte-input>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <x-adminlte-select name="tingkat" id="tingkat" label="Tingkat" required>
                                            <option @if (old('tingkat') == 'Siswa') selected @endif>Siswa</option>
                                            <option @if (old('tingkat') == 'Guru') selected @endif>Guru</option>
                                        </x-adminlte-select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-adminlte-input name="tanggal_registrasi" id="tanggal_registrasi"
                                            label="Tanggal Registrasi" type="date"
                                            value="{{ old('tanggal_registrasi', date('Y-m-d')) }}" required />
                                    </div>
                                    <div class="col-md-4">
                                        <x-adminlte-input name="berlaku_hingga" id="berlaku_hingga" label="Berlaku Hingga"
                                            type="date" readonly />
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KOLOM KANAN: FOTO --}}
                    <div class="col-md-4">
                        <div class="card card-outline card-secondary sticky-top">
                            <div class="card-header">
                                <h3 class="card-title">Foto Anggota</h3>
                            </div>
                            <div class="card-body text-center">
                                <img id="previewFoto" src="{{ asset('images/default-user.png') }}" alt="Preview Foto"
                                    class="img-fluid img-thumbnail mb-2" style="max-height: 250px; cursor: pointer;"
                                    data-toggle="modal" data-target="#zoomModal">

                                <x-adminlte-input-file name="foto" id="foto" igroup-size="sm"
                                    placeholder="Pilih sebuah file..." />

                                <button type="button" id="resetFotoBtn" class="btn btn-sm btn-default mt-2">Reset
                                    Foto</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- TOMBOL AKSI --}}
                <div class="mt-2 d-flex justify-content-end">
                    <a href="{{ route('anggota.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <x-adminlte-button id="submitBtn" label="Simpan Data" theme="primary" type="submit" />
                </div>
            </form>
        </div>
    </div>

    <div class="modal fade" id="zoomModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-0">
                    <img id="zoomImage" src="" class="w-100" style="border-radius: .25rem;">
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    <script>
        // Menunggu semua elemen HTML dimuat sebelum menjalankan JavaScript
        document.addEventListener('DOMContentLoaded', () => {
            const formAnggota = document.getElementById('formAnggota');
            if (formAnggota) {
                // --- 1. Ambil semua elemen form ---
                const checkDuplicateUrl = formAnggota.dataset.checkDuplicateUrl;
                const defaultImageUrl = formAnggota.dataset.defaultImageUrl;

                const namaInput = document.querySelector('[name="nama_anggota"]');
                const nomorIndukInput = document.querySelector('#nomor_induk');
                const tingkatSelect = document.querySelector('#tingkat');
                const tanggalRegInput = document.querySelector('#tanggal_registrasi');
                const berlakuHinggaInput = document.querySelector('#berlaku_hingga');
                const emailPreviewInput = document.querySelector('#email_preview');
                const namaWarning = document.querySelector('#namaWarning');
                const submitBtn = document.querySelector('#submitBtn');

                const fotoInput = document.querySelector('#foto');
                const previewFoto = document.querySelector('#previewFoto');
                const resetFotoBtn = document.querySelector('#resetFotoBtn');
                const zoomImage = document.getElementById('zoomImage');

                // --- 2. Kumpulan Fungsi Helper ---
                const updateAccountPreviews = () => {
                    const tingkat = tingkatSelect.value;
                    const nomorInduk = nomorIndukInput.value;
                    if (nomorInduk.length >= 4) {
                        const kodeTingkat = (tingkat === 'Guru') ? 'G' : 'S';
                        const tahun = new Date().getFullYear().toString().substr(-2);
                        const empatDigit = nomorInduk.substr(-4);
                        const nomorUrut = 'XXXX';
                        const idAnggotaPreview = `${kodeTingkat}${tahun}-${empatDigit}-${nomorUrut}`;
                        const domain = (tingkat === 'Guru') ? 'guru.perpus.com' : 'siswa.perpus.com';
                        emailPreviewInput.value = `${idAnggotaPreview.toLowerCase()}@${domain}`;
                    } else {
                        emailPreviewInput.value = '';
                    }
                };

                const setTanggalBerlaku = () => {
                    if (!tanggalRegInput.value) return;
                    const regDate = new Date(tanggalRegInput.value);
                    if (tingkatSelect.value === 'Guru') {
                        berlakuHinggaInput.value = '9999-12-31';
                    } else {
                        regDate.setFullYear(regDate.getFullYear() + 3);
                        berlakuHinggaInput.value = regDate.toISOString().split('T')[0];
                    }
                };

                const checkDuplicate = () => {
                    if (!namaInput.value) return;
                    fetch(checkDuplicateUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            nama_anggota: namaInput.value
                        })
                    }).then(res => res.json()).then(data => {
                        namaWarning.style.display = data.nama_duplicate ? 'block' : 'none';
                        submitBtn.disabled = data.nama_duplicate;
                    });
                };

                // --- 3. Pasang Event Listeners ---
                nomorIndukInput.addEventListener('keyup', updateAccountPreviews);
                tanggalRegInput.addEventListener('change', setTanggalBerlaku);
                tingkatSelect.addEventListener('change', () => {
                    setTanggalBerlaku();
                    updateAccountPreviews();
                });
                namaInput.addEventListener('blur', checkDuplicate);

                if (fotoInput) {
                    fotoInput.addEventListener('change', (e) => {
                        const file = e.target.files[0];
                        if (file) {
                            previewFoto.src = URL.createObjectURL(file);
                            const label = e.target.nextElementSibling;
                            if (label) label.innerText = file.name;
                        }
                    });
                }

                if (resetFotoBtn) {
                    resetFotoBtn.addEventListener('click', () => {
                        fotoInput.value = null;
                        previewFoto.src = defaultImageUrl;
                        const label = fotoInput.nextElementSibling;
                        if (label) label.innerText = 'Pilih sebuah file...';
                    });
                }

                if (previewFoto && zoomImage) {
                    previewFoto.addEventListener('click', () => {
                        zoomImage.src = previewFoto.src;
                    });
                }

                // --- 4. Panggil Fungsi saat halaman pertama kali dimuat ---
                setTanggalBerlaku();
            }
        });
    </script>
@stop

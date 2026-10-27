@extends('adminlte::page')

@section('title', 'Edit Data Anggota')

@section('content_header')
    <h1 class="m-0 text-dark">Edit Data Anggota</h1>
@stop

@section('content')
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses" id="successAlert" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    <div class="card card-primary">
        <div class="card-header">
            <h3 class="card-title">Formulir Edit Anggota</h3>
        </div>
        <div class="card-body">
            <x-adminlte-alert theme="info" title="Info">
                Data untuk <strong>{{ $anggota->nama_anggota }}</strong> terakhir diubah pada:
                {{ $anggota->updated_at->isoFormat('dddd, D MMMM YYYY [pukul] HH:mm') }}
            </x-adminlte-alert>
            <form id="formAnggota" method="POST" action="{{ route('anggota.update', $anggota->id) }}"
                enctype="multipart/form-data" data-check-duplicate-url="{{ route('anggota.cek-duplicate') }}"
                data-default-image-url="{{ asset('images/default-user.png') }}">
                @csrf
                @method('PUT')

                <div class="row">
                    {{-- KOLOM KIRI: DATA DIRI --}}
                    <div class="col-md-8">
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Data Diri</h3>
                            </div>
                            <div class="card-body">
                                <x-adminlte-input name="id_anggota" label="ID Anggota"
                                    value="{{ old('id_anggota', $anggota->id_anggota) }}" readonly />

                                <x-adminlte-input name="nama_anggota" label="Nama Lengkap"
                                    placeholder="Masukkan nama sesuai akta"
                                    value="{{ old('nama_anggota', $anggota->nama_anggota) }}" required>
                                    <x-slot name="bottomSlot">
                                        <small id="namaWarning" class="form-text text-danger" style="display:none;">Nama ini
                                            sudah terdaftar.</small>
                                    </x-slot>
                                </x-adminlte-input>

                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input name="nomor_induk" id="nomor_induk" label="No. Induk (NIS/NIP)"
                                            placeholder="Wajib diisi"
                                            value="{{ old('nomor_induk', $anggota->nomor_induk) }}" required readonly />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input name="no_telepon" label="No. Telepon / HP"
                                            placeholder="Contoh: 08123456789"
                                            value="{{ old('no_telepon', $anggota->no_telepon) }}" />
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input name="tanggal_lahir" label="Tanggal Lahir" type="date"
                                            value="{{ old('tanggal_lahir', $anggota->tanggal_lahir) }}" required />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-select name="jenis_kelamin" label="Jenis Kelamin" required>
                                            <option value="Laki-laki" @if (old('jenis_kelamin', $anggota->jenis_kelamin) == 'Laki-laki') selected @endif>
                                                Laki-laki
                                            </option>
                                            <option value="Perempuan" @if (old('jenis_kelamin', $anggota->jenis_kelamin) == 'Perempuan') selected @endif>
                                                Perempuan
                                            </option>
                                        </x-adminlte-select>
                                    </div>
                                </div>
                                <x-adminlte-textarea name="alamat" label="Alamat" rows="2"
                                    placeholder="Masukkan alamat lengkap.">{{ old('alamat', $anggota->alamat) }}</x-adminlte-textarea>
                            </div>
                        </div>

                        {{-- AKUN & KEANGGOTAAN --}}
                        <div class="card card-outline card-secondary">
                            <div class="card-header">
                                <h3 class="card-title">Akun & Keanggotaan</h3>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <x-adminlte-input name="password" label="Password Akun" type="password"
                                            placeholder="Kosongkan jika tidak diubah" />
                                    </div>
                                    <div class="col-md-6">
                                        <x-adminlte-input name="email" id="email_preview" label="Email Akun" readonly
                                            value="{{ old('email', $anggota->email) }}">
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
                                            <option value="Siswa" @if (old('tingkat', $anggota->tingkat) == 'Siswa') selected @endif>Siswa
                                            </option>
                                            <option value="Guru" @if (old('tingkat', $anggota->tingkat) == 'Guru') selected @endif>Guru
                                            </option>
                                        </x-adminlte-select>
                                    </div>
                                    <div class="col-md-4">
                                        <x-adminlte-input name="tanggal_registrasi" id="tanggal_registrasi"
                                            label="Tanggal Registrasi" type="date"
                                            value="{{ old('tanggal_registrasi', $anggota->tanggal_registrasi) }}"
                                            required />
                                    </div>
                                    <div class="col-md-4">
                                        <x-adminlte-input name="berlaku_hingga" id="berlaku_hingga" label="Berlaku Hingga"
                                            type="date" value="{{ old('berlaku_hingga', $anggota->berlaku_hingga) }}" />
                                        <x-adminlte-select name="status" label="Status Keanggotaan" required>
                                            <option value="aktif" @if (old('status', $anggota->getRawOriginal('status')) == 'aktif') selected @endif>Aktif
                                            </option>
                                            <option value="non-aktif" @if (old('status', $anggota->getRawOriginal('status')) == 'non-aktif') selected @endif>
                                                Non-Aktif</option>
                                            <option value="alumni" @if (old('status', $anggota->getRawOriginal('status')) == 'alumni') selected @endif>Alumni
                                            </option>
                                        </x-adminlte-select>

                                        {{-- Tambahkan notifikasi visual jika statusnya kadaluwarsa --}}
                                        @if ($anggota->status === 'kadaluwarsa')
                                            <div class="mt-1">
                                                <span class="badge badge-warning"><i
                                                        class="fas fa-exclamation-triangle mr-1"></i>Kartu Sudah
                                                    Kadaluwarsa</span>
                                            </div>
                                        @endif
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
                                <img id="previewFoto"
                                    src="{{ $anggota->foto ? asset('storage/' . $anggota->foto) : asset('images/default-user.png') }}"
                                    alt="Preview Foto" class="img-fluid img-thumbnail mb-2"
                                    style="max-height: 250px; cursor: pointer;" data-toggle="modal"
                                    data-target="#zoomModal">
                                <x-adminlte-input-file name="foto" id="foto" igroup-size="sm"
                                    placeholder="Pilih file jika ingin ganti" />
                                <button type="button" id="resetFotoBtn" class="btn btn-sm btn-default mt-2">Reset
                                    Foto</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tombol aksi --}}
                <div class="mt-2 d-flex justify-content-end">
                    <a href="{{ route('anggota.index') }}" class="btn btn-secondary mr-2">Batal</a>
                    <x-adminlte-button id="submitBtn" label="Perbarui Data" theme="primary" type="submit" />
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Zoom Gambar --}}
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
        document.addEventListener('DOMContentLoaded', () => {
            const formAnggota = document.getElementById('formAnggota');
            if (formAnggota) {
                // --- 1. Ambil elemen-elemen yang dibutuhkan ---
                const checkDuplicateUrl = formAnggota.dataset.checkDuplicateUrl;
                const defaultImageUrl = formAnggota.dataset.defaultImageUrl;
                const actionUrl = formAnggota.getAttribute('action');
                const anggotaId = actionUrl.substring(actionUrl.lastIndexOf('/') + 1);

                const namaInput = document.querySelector('[name="nama_anggota"]');
                const namaWarning = document.querySelector('#namaWarning');
                const submitBtn = document.querySelector('#submitBtn');

                const fotoInput = document.querySelector('#foto');
                const previewFoto = document.querySelector('#previewFoto');
                const resetFotoBtn = document.querySelector('#resetFotoBtn');
                const zoomImage = document.getElementById('zoomImage');

                // --- 2. Fungsi-fungsi ---

                // Fungsi cek duplikat nama (mengirim ID anggota saat ini)
                const checkDuplicate = () => {
                    if (!namaInput.value) return;
                    fetch(checkDuplicateUrl, {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": document.querySelector('input[name="_token"]').value
                        },
                        body: JSON.stringify({
                            id: anggotaId, // <-- Penting: kirim ID agar tidak dianggap duplikat oleh dirinya sendiri
                            nama_anggota: namaInput.value
                        })
                    }).then(res => res.json()).then(data => {
                        namaWarning.style.display = data.nama_duplicate ? 'block' : 'none';
                        submitBtn.disabled = data.nama_duplicate;
                    });
                };

                // --- 3. Pasang Event Listeners (Hanya yang diperlukan untuk edit) ---
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
                        // Di halaman edit, reset kembali ke gambar default dari server, bukan gambar 'user' default
                        previewFoto.src = formAnggota.querySelector('#previewFoto').getAttribute('src');
                        const label = fotoInput.nextElementSibling;
                        if (label) label.innerText = 'Pilih file jika ingin ganti';
                    });
                }

                if (previewFoto && zoomImage) {
                    previewFoto.addEventListener('click', () => {
                        zoomImage.src = previewFoto.src;
                    });
                }
            }
        });
    </script>
@stop

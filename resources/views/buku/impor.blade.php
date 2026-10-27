@extends('adminlte::page')

@section('title', 'Impor Data Buku')

@section('content_header')
    <h1 class="m-0 text-dark">Impor Data Buku dari Excel/CSV</h1>
@stop

@section('content')
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <span><i class="fas fa-file-import"></i> Import Data Buku</span>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-8">
                    <x-adminlte-card title="Upload File & Pilih Opsi" theme="primary" icon="fas fa-file-upload">
                        <form id="import-form" action="{{ route('buku.impor.process') }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="file-import-input">Pilih File (.xlsx, .xls, .csv)</label>
                                <input type="file" id="file-import-input" name="file_import" accept=".xlsx,.xls,.csv"
                                    class="form-control" required />
                            </div>

                            {{-- Notifikasi validasi akan muncul di sini --}}
                            <div id="validation-feedback"></div>

                            <div id="preview-container" class="mt-4" style="display:none;">
                                <h5>Preview Isi File (10 baris pertama)</h5>
                                <div class="table-responsive">
                                    <table id="preview-table" class="table table-bordered table-sm">
                                        <thead></thead>
                                        <tbody></tbody>
                                    </table>
                                </div>
                            </div>

                            <div class="mt-4">
                                <x-adminlte-select name="rak_id" label="Simpan Eksemplar ke Rak" required>
                                    <option value="" disabled selected>-- Pilih Rak Tujuan --</option>
                                    @foreach ($raks as $r)
                                        <option value="{{ $r->id }}">{{ $r->kode_rak }} — {{ $r->lokasi }}
                                        </option>
                                    @endforeach
                                </x-adminlte-select>
                                <x-adminlte-select name="sumber_perolehan" label="Sumber Perolehan" required>
                                    <option value="" disabled selected>-- Pilih Sumber --</option>
                                    <option value="Beli">Beli</option>
                                    <option value="Donasi">Donasi</option>
                                    <option value="APBD">APBD</option>
                                    <option value="Hibah">Hibah</option>
                                    <option value="Lainnya">Lainnya</option>
                                </x-adminlte-select>
                                <x-adminlte-input name="tanggal_perolehan" type="date" label="Tanggal Perolehan"
                                    :value="old('tanggal_perolehan', date('Y-m-d'))" required />
                            </div>
                            <x-adminlte-button id="import-submit" label="Proses Impor" theme="success" icon="fas fa-play"
                                class="mt-4" />
                        </form>
                    </x-adminlte-card>
                </div>
                <div class="col-md-4">
                    <x-adminlte-card title="Instruksi & Template" theme="info" icon="fas fa-info-circle">
                        {{-- PERBAIKAN TYPO DI SINI --}}
                        <p class="mb-3">Gunakan template di bawah untuk mengimpor data. Pastikan kolom
                            <strong>judul_buku</strong> dan <strong>penerbit</strong> ada.</p>
                        <a href="{{-- asset('template/template_buku.xlsx') --}}" class="btn btn-info mb-3">
                            <i class="fas fa-file-download"></i> Unduh Template
                        </a>
                    </x-adminlte-card>
                </div>
            </div>
        </div>
    </div>
@stop

@section('js')
    @vite(['resources/js/app.js'])
    <script src="https://unpkg.com/xlsx/dist/xlsx.full.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const form = document.getElementById('import-form');
            const fileInput = document.getElementById('file-import-input');
            const submitBtn = document.getElementById('import-submit');
            const previewContainer = document.getElementById('preview-container');
            const previewTable = document.getElementById('preview-table');
            const validationFeedback = document.getElementById('validation-feedback');
            const checkContentUrl = "{{ route('buku.impor.checkContent') }}";

            submitBtn.type = 'button';

            fileInput.addEventListener('change', async e => {
                submitBtn.disabled = true;
                validationFeedback.innerHTML = '';
                previewContainer.style.display = 'none';
                const file = e.target.files[0];
                if (!file) return;

                let sheetData;
                try {
                    const data = await file.arrayBuffer();
                    const wb = XLSX.read(data);
                    sheetData = XLSX.utils.sheet_to_json(wb.Sheets[wb.SheetNames[0]]);
                } catch (err) {
                    alert('Gagal membaca file. Pastikan formatnya benar.');
                    return;
                }

                if (!sheetData.length) {
                    alert('File kosong atau tidak ada data.');
                    return;
                }

                const dataToCheck = sheetData.map(row => ({
                    judul_buku: row.judul_buku?.trim() || null,
                    penerbit: row.penerbit?.trim() || null
                })).filter(item => item.judul_buku);

                if (dataToCheck.length === 0) {
                    alert("Kolom 'judul_buku' tidak ditemukan atau kosong di dalam file.");
                    return;
                }

                let duplicateItems = [];
                try {
                    const response = await fetch(checkContentUrl, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content
                        },
                        body: JSON.stringify({
                            data: dataToCheck
                        })
                    });
                    const result = await response.json();
                    if (result.duplicates) duplicateItems = result.duplicates;
                } catch (err) {
                    alert('Gagal melakukan validasi ke server.');
                    return;
                }

                renderPreview(sheetData, duplicateItems);
                previewContainer.style.display = 'block';

                if (duplicateItems.length > 0) {
                    validationFeedback.innerHTML = `
                        <div class="alert alert-danger">
                            <h5><i class="icon fas fa-ban"></i> Ditemukan ${duplicateItems.length} Data Duplikat!</h5>
                            <p>Kombinasi Judul & Penerbit berikut sudah ada di database. Baris duplikat ditandai merah.</p>
                            <ul>${duplicateItems.map(item => `<li>${item.judul_buku} - <i>${item.penerbit || 'Tanpa Penerbit'}</i></li>`).join('')}</ul>
                        </div>`;
                    submitBtn.disabled = true;
                } else {
                    // --- PENAMBAHAN NOTIFIKASI SUKSES SEMENTARA ---
                    validationFeedback.innerHTML = `
                        <div class="alert alert-success">
                            <h5><i class="icon fas fa-check"></i> Validasi Berhasil!</h5>
                            <p>Semua data di file ini aman untuk diimpor.</p>
                        </div>`;
                    submitBtn.disabled = false;
                    // Hilangkan notifikasi setelah 3 detik
                    setTimeout(() => {
                        validationFeedback.innerHTML = '';
                    }, 3000);
                }
            });

            function renderPreview(data, duplicates = []) {
                const tHead = previewTable.tHead;
                const tBody = previewTable.querySelector('tbody') || previewTable.createTBody();
                tHead.innerHTML = '';
                tBody.innerHTML = '';
                const headers = data.length > 0 ? Object.keys(data[0]) : [];
                if (headers.length === 0) return;

                const headerRow = tHead.insertRow();
                headers.forEach(h => {
                    const th = headerRow.insertCell();
                    th.textContent = h;
                });

                data.slice(0, 10).forEach(rowData => {
                    const tr = tBody.insertRow();
                    const judul = rowData.judul_buku?.trim();
                    const penerbit = rowData.penerbit?.trim();
                    const isDuplicate = duplicates.some(dup => dup.judul_buku === judul && dup.penerbit ===
                        penerbit);
                    if (isDuplicate) tr.classList.add('table-danger');
                    headers.forEach(header => {
                        const td = tr.insertCell();
                        td.textContent = rowData[header] ?? '';
                    });
                });
            }

            submitBtn.addEventListener('click', () => {
                if (!submitBtn.disabled) form.submit();
            });
        });
    </script>
@stop

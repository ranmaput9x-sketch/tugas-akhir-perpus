@extends('adminlte::page')

@section('title', 'Tambah Buku')

@section('css')
    @vite(['resources/css/app.css'])
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3>Tambah Buku</h3>
        </div>
        <div class="card-body">
            <form id="form-buku-create" action="{{ route('buku.store') }}" method="POST" enctype="multipart/form-data"
                novalidate>
                @csrf

                {{-- Stepper --}}
                <div id="stepper" class="bs-stepper">
                    {{-- Header Stepper --}}
                    <div class="bs-stepper-header" role="tablist">
                        <div class="step" data-target="#step-1"><button type="button" class="step-trigger"><span
                                    class="bs-stepper-circle">1</span><span class="bs-stepper-label">Info
                                    Buku</span></button></div>
                        <div class="line"></div>
                        <div class="step" data-target="#step-2"><button type="button" class="step-trigger"><span
                                    class="bs-stepper-circle">2</span><span
                                    class="bs-stepper-label">Metadata</span></button></div>
                        <div class="line"></div>
                        <div class="step" data-target="#step-3" id="header-step-3"><button type="button"
                                class="step-trigger"><span class="bs-stepper-circle">3</span><span
                                    class="bs-stepper-label">Stok Fisik</span></button></div>
                        <div class="line"></div>
                        <div class="step" data-target="#step-4"><button type="button" class="step-trigger"><span
                                    class="bs-stepper-circle">4</span><span class="bs-stepper-label">Review</span></button>
                        </div>
                    </div>

                    <div class="bs-stepper-content">
                        {{-- STEP 1: Info Buku --}}
                        <div id="step-1" class="content" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12"><x-adminlte-input name="judul_buku" id="judul_buku"
                                        label="Judul Buku" required /></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><x-adminlte-input name="sub_judul" id="sub_judul"
                                        label="Sub Judul (Opsional)" /></div>
                                <div class="col-md-6"><x-adminlte-input name="isbn" label="ISBN (Opsional)" /></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><x-adminlte-input name="tahun_terbit" id="tahun_terbit"
                                        label="Tahun Terbit" type="number" :value="old('tahun_terbit', date('Y'))" required /></div>
                                <div class="col-md-6"><x-adminlte-input name="jumlah_halaman" label="Jumlah Halaman"
                                        type="number" min="1" required /></div>
                            </div>
                            <div class="row">
                                <div class="col-md-12"><x-adminlte-textarea name="deskripsi" label="Deskripsi" /></div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <x-adminlte-input-file name="url_cover" id="url_cover" label="Cover Buku (Opsional)"
                                        accept="image/*" />
                                    <div id="cover-preview-card" class="card shadow-sm mt-2 d-none">
                                        <div class="card-header p-2">
                                            <h6 class="card-title mb-0">Preview Cover</h6>
                                        </div>
                                        <div class="card-body text-center p-2"><img id="preview-cover-step1"
                                                class="preview-img-step" alt="Cover Preview"></div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <x-adminlte-select name="koleksi_id" id="koleksi_id" label="Jenis Koleksi" required>
                                        <option value="" disabled selected>-- Pilih Jenis Koleksi --</option>
                                        @foreach ($koleksis as $k)
                                            <option value="{{ $k->id }}" data-nama="{{ $k->nama_koleksi }}">
                                                {{ $k->nama_koleksi }}</option>
                                        @endforeach
                                    </x-adminlte-select>
                                    <div id="ebook-upload-wrapper" class="d-none">
                                        <x-adminlte-input-file name="file_ebook" id="file_ebook"
                                            label="Upload eBook (PDF/EPUB)" accept=".pdf,.epub" />
                                        <div id="ebook-preview-card" class="card shadow-sm mt-2 d-none">
                                            <div class="card-header p-2">
                                                <h6 class="card-title mb-0">File eBook Terpilih</h6>
                                            </div>
                                            <div class="card-body p-2">
                                                <p id="ebook-filename" class="mb-1 font-weight-bold text-truncate"></p>
                                                <p class="mb-0"><small>Format: <span id="ebook-ext"></span></small></p>
                                            </div>
                                        </div>
                                        @error('file_ebook')
                                            <small class="text-danger d-block mt-1">{{ $message }}</small>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>
                        </div>

                        {{-- STEP 2: Metadata --}}
                        <div id="step-2" class="content" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4">
                                    <x-adminlte-select name="kategori_id" id="kategori_id" label="Kategori" required>
                                        <option value="" disabled selected>-- Pilih/Ketik untuk buat baru --</option>
                                        @foreach ($kategori as $kat)
                                            <option value="{{ $kat->id }}" data-kode="{{ $kat->kode_klasifikasi }}">
                                                {{ $kat->nama_kategori }}</option>
                                        @endforeach
                                    </x-adminlte-select>
                                </div>
                                <div class="col-md-4">
                                    <x-adminlte-select name="penulis_id[]" id="penulis_id" label="Penulis" multiple
                                        required>
                                        <option value="" disabled>-- Pilih/Ketik untuk buat baru --</option>
                                        @foreach ($penulis as $pen)
                                            <option value="{{ $pen->id }}">{{ $pen->nama_penulis }}</option>
                                        @endforeach
                                    </x-adminlte-select>
                                </div>
                                <div class="col-md-4">
                                    <x-adminlte-select name="penerbit_id" id="penerbit_id" label="Penerbit" required>
                                        <option value="" disabled selected>-- Pilih/Ketik untuk buat baru --</option>
                                        @foreach ($penerbit as $p)
                                            <option value="{{ $p->id }}">{{ $p->nama_penerbit }}</option>
                                        @endforeach
                                    </x-adminlte-select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6"><x-adminlte-input name="tempat_terbit" id="tempat_terbit"
                                        label="Tempat Terbit" /></div>
                                <div class="col-md-6"><x-adminlte-input name="edisi" id="edisi" label="Edisi" />
                                </div>
                            </div>
                            <div id="call-number-section" class="row mt-3">
                                <div class="col-md-12">
                                    <x-adminlte-input name="nomor_panggil" id="nomor_panggil"
                                        label="Nomor Panggil (Call Number)" placeholder="Akan ter-generate otomatis"
                                        readonly />
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="stepper.previous()">Back</button>
                                <button type="button" id="btn-step2-next" class="btn btn-primary">Next</button>
                            </div>
                        </div>

                        {{-- STEP 3: Stok Fisik --}}
                        <div id="step-3" class="content" role="tabpanel">
                            <div class="row">
                                <div class="col-md-4"><x-adminlte-input name="jumlah_eksemplar" id="jumlah_eksemplar"
                                        label="Jumlah Eksemplar" type="number" min="1" /></div>
                                <div class="col-md-8"><x-adminlte-select name="rak_id" id="rak_id"
                                        label="Lokasi Rak Buku">
                                        <option value="" disabled selected>-- Pilih Rak --</option>
                                        @foreach ($raks as $r)
                                            <option value="{{ $r->id }}">{{ $r->kode_rak }} —
                                                {{ $r->lokasi }}</option>
                                        @endforeach
                                    </x-adminlte-select></div>
                            </div>
                            <div class="row">
                                <div class="col-md-4"><x-adminlte-select name="sumber_perolehan" id="sumber_perolehan"
                                        label="Sumber Perolehan">
                                        <option value="" disabled selected>-- Pilih Sumber --</option>
                                        <option value="Beli">Beli</option>
                                        <option value="Donasi">Donasi</option>
                                        <option value="APBD">APBD</option>
                                    </x-adminlte-select></div>
                                <div class="col-md-4"><x-adminlte-input name="tanggal_perolehan" id="tanggal_perolehan"
                                        label="Tanggal Perolehan" type="date" :value="old('tanggal_perolehan', date('Y-m-d'))" /></div>
                                <div class="col-md-4"><x-adminlte-input name="harga" id="harga" label="Harga (Rp)"
                                        type="number" min="0" placeholder="Opsional" /></div>
                            </div>
                            <div class="table-responsive mt-3">
                                <p class="font-weight-bold">Preview Eksemplar</p>
                                <table class="table table-sm table-bordered">
                                    <thead class="thead-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Nomor Eksemplar</th>
                                            <th>Kode Barcode (Contoh)</th>
                                        </tr>
                                    </thead>
                                    <tbody id="preview-eksemplar-body"></tbody>
                                </table>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" class="btn btn-secondary"
                                    onclick="stepper.previous()">Back</button>
                                <button type="button" class="btn btn-primary" onclick="stepper.next()">Next</button>
                            </div>
                        </div>

                        {{-- STEP 4: Review --}}
                        <div id="step-4" class="content" role="tabpanel">
                            <h4 class="text-center mb-4">Review dan Konfirmasi Data Buku</h4>
                            <div class="row">
                                <div class="col-md-3 text-center">
                                    <img id="review-cover" class="preview-img-review" alt="Cover Buku">
                                </div>
                                <div class="col-md-9">
                                    <div class="card shadow-sm">
                                        <div class="card-body">
                                            <h4 id="review-judul" class="font-weight-bold"></h4>
                                            <p id="review-sub_judul" class="text-muted"></p>
                                            <hr>
                                            <p><strong>Penulis:</strong> <span id="review-penulis"></span></p>
                                            <p><strong>Penerbit:</strong> <span id="review-penerbit"></span>, <span
                                                    id="review-tahun_terbit"></span></p>
                                            <p><strong>Tempat Terbit:</strong> <span id="review-tempat_terbit"></span></p>
                                            <p><strong>Edisi:</strong> <span id="review-edisi"></span></p>
                                            <p><strong>Kategori:</strong> <span id="review-kategori"></span></p>
                                            <p><strong>Jenis Koleksi:</strong> <span id="review-koleksi"></span></p>
                                            <p id="review-call-number-wrapper"><strong>Nomor Panggil:</strong> <span
                                                    id="review-nomor_panggil"
                                                    class="font-weight-bold text-monospace"></span></p>
                                            <div id="review-ebook-wrapper" class="d-none">
                                                <p><strong>File eBook:</strong> <a href="#" id="review-ebook-link"
                                                        target="_blank"></a></p>
                                            </div>
                                            <p><strong>Deskripsi:</strong> <span id="review-deskripsi"></span></p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div id="review-eksemplar-section" class="card shadow-sm mt-4">
                                <div class="card-header font-weight-bold">Daftar Eksemplar</div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table class="table table-sm table-striped mb-0">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th>#</th>
                                                    <th>Nomor Eksemplar</th>
                                                    <th>Kode Barcode (Final)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="review-eksemplar-body"></tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex justify-content-between mt-4">
                                <button type="button" id="btn-step4-back" class="btn btn-secondary">Back</button>
                                <button type="submit" class="btn btn-success">Simpan Buku</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
            @if ($errors->any())
                <div class="alert alert-danger alert-dismissible">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <h5 class="font-weight-bold"><i class="icon fas fa-ban"></i> Validasi Gagal!</h5>
                    <p>Sepertinya ada beberapa data yang perlu diperbaiki:</p>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>
    </div>
@stop

@section('js')
    @vite(['resources/js/app.js'])
@stop

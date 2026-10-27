@extends('adminlte::page')

@section('title', 'Edit Buku')

@section('content_header')
    <div class="d-flex align-items-center justify-content-between">
        <h1 class="m-0">Edit Buku</h1>
        <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        {{-- ALERT GLOBAL --}}
        @if ($errors->any())
            <div class="alert alert-danger">
                <div class="d-flex align-items-start">
                    <i class="icon fas fa-ban mt-1 mr-2"></i>
                    <div>
                        <h5 class="mb-1">Validasi Gagal</h5>
                        <div class="text-sm">Periksa kolom yang ditandai merah di bawah.</div>
                    </div>
                </div>
            </div>
        @endif

        @if (session('success'))
            <div class="alert alert-success">
                <i class="icon fas fa-check"></i> {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('buku.update', $buku->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="row">
                {{-- LEFT: FORM UTAMA --}}
                <div class="col-lg-8">

                    {{-- CARD 1: IDENTITAS --}}
                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-book mr-1"></i> Informasi Buku</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-group">
                                <label>Judul Buku <span class="text-danger">*</span></label>
                                <input type="text" name="judul_buku"
                                    class="form-control @error('judul_buku') is-invalid @enderror"
                                    value="{{ old('judul_buku', $buku->judul_buku) }}"
                                    placeholder="Contoh: Sistem Informasi Perpustakaan">
                                @error('judul_buku')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label>Sub Judul</label>
                                <input type="text" name="sub_judul"
                                    class="form-control @error('sub_judul') is-invalid @enderror"
                                    value="{{ old('sub_judul', $buku->sub_judul) }}" placeholder="(Opsional)">
                                @error('sub_judul')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>ISBN</label>
                                    <input type="text" name="isbn"
                                        class="form-control @error('isbn') is-invalid @enderror"
                                        value="{{ old('isbn', $buku->isbn) }}" placeholder="(Opsional)">
                                    @error('isbn')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Tahun Terbit <span class="text-danger">*</span></label>
                                    <input type="number" name="tahun_terbit"
                                        class="form-control @error('tahun_terbit') is-invalid @enderror"
                                        value="{{ old('tahun_terbit', $buku->tahun_terbit) }}" min="1800"
                                        max="{{ now()->year }}">
                                    @error('tahun_terbit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-3">
                                    <label>Jumlah Halaman <span class="text-danger">*</span></label>
                                    <input type="number" name="jumlah_halaman"
                                        class="form-control @error('jumlah_halaman') is-invalid @enderror"
                                        value="{{ old('jumlah_halaman', $buku->jumlah_halaman) }}" min="1">
                                    @error('jumlah_halaman')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Tempat Terbit</label>
                                    <input type="text" name="tempat_terbit"
                                        class="form-control @error('tempat_terbit') is-invalid @enderror"
                                        value="{{ old('tempat_terbit', $buku->tempat_terbit) }}" placeholder="(Opsional)">
                                    @error('tempat_terbit')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label>Edisi</label>
                                    <input type="text" name="edisi"
                                        class="form-control @error('edisi') is-invalid @enderror"
                                        value="{{ old('edisi', $buku->edisi) }}" placeholder="(Opsional)">
                                    @error('edisi')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group mb-0">
                                <label>Deskripsi</label>
                                <textarea name="deskripsi" rows="4" class="form-control @error('deskripsi') is-invalid @enderror"
                                    placeholder="Ringkas isi buku...">{{ old('deskripsi', $buku->deskripsi) }}</textarea>
                                @error('deskripsi')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: KLASIFIKASI --}}
                    <div class="card card-outline card-primary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-tags mr-1"></i> Klasifikasi & Metadata</h3>
                        </div>

                        <div class="card-body">
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Jenis Koleksi <span class="text-danger">*</span></label>
                                    <select name="koleksi_id" id="koleksi_id"
                                        class="form-control @error('koleksi_id') is-invalid @enderror">
                                        <option value="">-- Pilih Koleksi --</option>
                                        @foreach ($koleksis as $k)
                                            <option value="{{ $k->id }}" data-nama="{{ $k->nama_koleksi }}"
                                                {{ old('koleksi_id', $buku->koleksi_id) == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_koleksi }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('koleksi_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">
                                        Pilih <b>eBook/Digital</b> jika buku punya file PDF/EPUB.
                                    </small>
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Penerbit <span class="text-danger">*</span></label>
                                    <select name="penerbit_id"
                                        class="form-control @error('penerbit_id') is-invalid @enderror">
                                        <option value="">-- Pilih Penerbit --</option>
                                        @foreach ($penerbits as $p)
                                            <option value="{{ $p->id }}"
                                                {{ (string) old('penerbit_id', $buku->penerbit_id) === (string) $p->id ? 'selected' : '' }}>
                                                {{ $p->nama_penerbit }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('penerbit_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label>Kategori <span class="text-danger">*</span></label>
                                    <select name="kategori_id"
                                        class="form-control @error('kategori_id') is-invalid @enderror">
                                        <option value="">-- Pilih Kategori --</option>
                                        @foreach ($kategoris as $kat)
                                            <option value="{{ $kat->id }}"
                                                {{ (string) old('kategori_id', optional($buku->kategori->first())->id) === (string) $kat->id ? 'selected' : '' }}>
                                                {{ $kat->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('kategori_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="form-group col-md-6">
                                    <label>Penulis <span class="text-danger">*</span></label>
                                    @php
                                        $selectedPenulis = old(
                                            'penulis_id',
                                            $buku->penulis ? $buku->penulis->pluck('id')->toArray() : [],
                                        );
                                    @endphp
                                    <select name="penulis_id[]"
                                        class="form-control @error('penulis_id') is-invalid @enderror select2" multiple
                                        data-placeholder="Pilih penulis...">
                                        @foreach ($penulis as $pn)
                                            <option value="{{ $pn->id }}"
                                                {{ in_array($pn->id, $selectedPenulis) ? 'selected' : '' }}>
                                                {{ $pn->nama_penulis }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('penulis_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    @error('penulis_id.*')
                                        <div class="text-danger text-sm mt-1">{{ $message }}</div>
                                    @enderror
                                    <small class="text-muted d-block mt-1">Boleh pilih lebih dari satu.</small>
                                </div>
                            </div>

                            {{-- Nomor panggil (read-only by default) --}}
                            <div class="form-group mb-0">
                                <label>Nomor Panggil</label>
                                <div class="input-group">
                                    <input type="text" id="nomor_panggil_edit" name="nomor_panggil"
                                        class="form-control @error('nomor_panggil') is-invalid @enderror"
                                        value="{{ old('nomor_panggil', $buku->nomor_panggil) }}" readonly>
                                    <div class="input-group-append">
                                        <button type="button" id="toggle-nomor-panggil"
                                            class="btn btn-outline-secondary" title="Edit manual nomor panggil">
                                            <i class="fas fa-lock"></i>
                                        </button>
                                    </div>
                                    @error('nomor_panggil')
                                        <div class="invalid-feedback d-block">{{ $message }}</div>
                                    @enderror
                                </div>
                                <small class="text-muted d-block mt-1">
                                    Default: sistem generate ulang saat simpan. Klik ikon kunci jika ingin edit manual.
                                </small>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: SIDEBAR STICKY --}}
                <div class="col-lg-4">
                    <div class="card card-success sticky-top" style="top: 1rem;">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-check-circle mr-1"></i> Aksi</h3>
                        </div>
                        <div class="card-body">
                            <button type="submit" class="btn btn-success btn-block">
                                <i class="fas fa-save mr-1"></i> Simpan Perubahan
                            </button>
                            <a href="{{ route('buku.show', $buku->id) }}" class="btn btn-secondary btn-block">
                                Batal
                            </a>

                            <hr class="my-3">

                            {{-- Ringkasan status biar user ngerti --}}
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Tipe koleksi</span>
                                <span class="badge badge-info" id="koleksi-badge">-</span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="text-muted">Cover</span>
                                @if ($buku->url_cover)
                                    <span class="badge badge-success">Ada</span>
                                @else
                                    <span class="badge badge-secondary">Belum</span>
                                @endif
                            </div>

                            <div class="d-flex justify-content-between align-items-center">
                                <span class="text-muted">File eBook</span>
                                @if ($buku->file_ebook)
                                    <span class="badge badge-success">Ada</span>
                                @else
                                    <span class="badge badge-secondary">Belum</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- COVER --}}
                    <div class="card card-secondary">
                        <div class="card-header">
                            <h3 class="card-title"><i class="far fa-image mr-1"></i> Cover Buku</h3>
                        </div>
                        <div class="card-body text-center">
                            <img id="cover-preview"
                                src="{{ $buku->url_cover ? asset('storage/' . $buku->url_cover) : 'https://via.placeholder.com/180x240.png?text=No+Cover' }}"
                                alt="Cover" class="img-fluid img-thumbnail mb-3"
                                style="max-height: 260px; object-fit: cover;">

                            <div class="custom-file text-left">
                                <input type="file" class="custom-file-input @error('url_cover') is-invalid @enderror"
                                    id="url_cover_edit" name="url_cover" accept="image/*">
                                <label class="custom-file-label" for="url_cover_edit">Pilih cover baru...</label>
                            </div>
                            @error('url_cover')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror
                            <small class="text-muted d-block mt-2">Disarankan: JPG/PNG, ukuran kecil agar cepat.</small>
                        </div>
                    </div>

                    {{-- EBOOK (SIBLING, BUKAN NESTED) --}}
                    <div class="card card-secondary" id="ebook-card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-file-pdf mr-1"></i> File eBook (PDF/EPUB)</h3>
                        </div>
                        <div class="card-body">
                            @if ($buku->file_ebook)
                                <p class="mb-2">
                                    File saat ini:
                                    <a href="{{ asset('storage/' . $buku->file_ebook) }}" target="_blank">
                                        Download / Lihat
                                    </a>
                                    @if ($buku->format_ebook)
                                        <small class="text-muted">(Format: {{ $buku->format_ebook }})</small>
                                    @endif
                                </p>
                            @else
                                <p class="text-danger mb-2">Belum ada file eBook untuk buku ini.</p>
                            @endif

                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('file_ebook') is-invalid @enderror"
                                    id="file_ebook_edit" name="file_ebook" accept=".pdf,.epub">
                                <label class="custom-file-label text-left" for="file_ebook_edit">
                                    Pilih file eBook...
                                </label>
                            </div>

                            @error('file_ebook')
                                <small class="text-danger d-block mt-1">{{ $message }}</small>
                            @enderror

                            <small class="text-muted d-block mt-2">
                                Upload hanya jika ingin mengganti / menambahkan.
                            </small>
                        </div>
                    </div>

                </div>
            </div>
        </form>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Custom file input label
            if (window.bsCustomFileInput) {
                bsCustomFileInput.init();
            } else {
                document.querySelectorAll('.custom-file-input').forEach(input => {
                    input.addEventListener('change', function() {
                        const name = this.files && this.files.length ? this.files[0].name :
                            'Pilih file...';
                        const label = this.nextElementSibling;
                        if (label) label.textContent = name;
                    });
                });
            }

            // Select2 (kalau tersedia di AdminLTE kamu)
            if (window.$ && $.fn.select2) {
                $('.select2').select2({
                    width: '100%'
                });
            }

            const koleksiSelect = document.getElementById('koleksi_id');
            const ebookCard = document.getElementById('ebook-card');
            const ebookInput = document.getElementById('file_ebook_edit');
            const badge = document.getElementById('koleksi-badge');
            const hasEbook = @json((bool) $buku->file_ebook);

            const isDigital = () => {
                const nama = (koleksiSelect?.selectedOptions?.[0]?.dataset?.nama || '').toLowerCase();
                return nama.includes('digital') || nama.includes('ebook') || nama.includes('e-book');
            };

            const syncUI = () => {
                const digital = isDigital();
                const koleksiText = koleksiSelect?.selectedOptions?.[0]?.textContent?.trim() || '-';

                if (badge) {
                    badge.textContent = koleksiText;
                    badge.classList.toggle('badge-info', true);
                }

                if (ebookCard) ebookCard.style.display = digital ? '' : 'none';

                // Required hanya jika digital dan belum ada file ebook sebelumnya
                if (ebookInput) {
                    ebookInput.required = digital && !hasEbook;
                    if (!digital) ebookInput.value = '';
                }
            };

            if (koleksiSelect) koleksiSelect.addEventListener('change', syncUI);
            syncUI();

            // Toggle nomor panggil readonly
            const callInput = document.getElementById('nomor_panggil_edit');
            const toggleBtn = document.getElementById('toggle-nomor-panggil');
            if (callInput && toggleBtn) {
                toggleBtn.addEventListener('click', () => {
                    const locked = callInput.hasAttribute('readonly');
                    if (locked) {
                        callInput.removeAttribute('readonly');
                        toggleBtn.innerHTML = '<i class="fas fa-unlock"></i>';
                        toggleBtn.title = 'Kunci kembali';
                    } else {
                        callInput.setAttribute('readonly', 'readonly');
                        toggleBtn.innerHTML = '<i class="fas fa-lock"></i>';
                        toggleBtn.title = 'Edit manual nomor panggil';
                    }
                });
            }

            // Cover preview
            const coverInput = document.getElementById('url_cover_edit');
            const coverPreview = document.getElementById('cover-preview');
            if (coverInput && coverPreview) {
                coverInput.addEventListener('change', () => {
                    if (coverInput.files && coverInput.files[0]) {
                        coverPreview.src = URL.createObjectURL(coverInput.files[0]);
                    }
                });
            }
        });
    </script>
@stop

{{-- resources/views/buku/_form.blade.php --}}
@csrf
@if (isset($edit) && $edit)
    @method('PUT')
@endif

<div class="row">
    <div class="col-md-6">
        <x-adminlte-input name="judul_buku" label="Judul Buku" value="{{ old('judul_buku', $buku->judul_buku ?? '') }}"
            fgroup-class="mb-3" />
        <x-adminlte-input name="penulis" label="Penulis" value="{{ old('penulis', $buku->penulis ?? '') }}"
            fgroup-class="mb-3" />
        <x-adminlte-input name="penerbit" label="Penerbit" value="{{ old('penerbit', $buku->penerbit ?? '') }}"
            fgroup-class="mb-3" />
        <x-adminlte-input name="tahun_terbit" label="Tahun Terbit" type="number"
            value="{{ old('tahun_terbit', $buku->tahun_terbit ?? '') }}" min="1900" max="{{ date('Y') }}"
            fgroup-class="mb-3" />
        <x-adminlte-input name="isbn" label="ISBN (Opsional)" id="isbn"
            value="{{ old('isbn', $buku->isbn ?? '') }}" fgroup-class="mb-3" />
    </div>

    <div class="col-md-6">
        <x-adminlte-select name="kategori" label="Kategori Buku" fgroup-class="mb-3">
            <option disabled selected>Pilih Kategori...</option>
            @foreach ($kategoriList as $kategori)
                <option value="{{ $kategori }}"
                    {{ old('kategori', $buku->kategori ?? '') == $kategori ? 'selected' : '' }}>
                    {{ $kategori }}
                </option>
            @endforeach
        </x-adminlte-select>

        <x-adminlte-select2 name="rak" label="Rak Buku" data-placeholder="Pilih Rak Buku..." fgroup-class="mb-3">
            <option disabled selected>Tempatkan Dirak Apa?</option>
            @foreach ($raks as $rak)
                <option value="{{ $rak->kode }}" {{ old('rak', $buku->rak ?? '') == $rak->kode ? 'selected' : '' }}>
                    {{ $rak->kode }} - {{ $rak->deskripsi }}
                </option>
            @endforeach
        </x-adminlte-select2>

        <x-adminlte-input name="stok" label="Stok Buku" type="number" min="0"
            value="{{ old('stok', $buku->stok ?? '') }}" fgroup-class="mb-3" />
        <x-adminlte-textarea name="deskripsi" label="Deskripsi Buku" rows=4
            fgroup-class="mb-3">{{ old('deskripsi', $buku->deskripsi ?? '') }}</x-adminlte-textarea>

        <x-adminlte-input name="barcode" label="Barcode" id="barcode"
            value="{{ old('barcode', $buku->barcode ?? '') }}" readonly fgroup-class="mb-3" />

        <div class="mb-3">
            <label>Preview Barcode:</label>
            <div class="bg-white p-2 rounded border text-center">
                <svg id="barcodeCanvas"></svg>
            </div>
        </div>
    </div>
</div>

<div class="mb-3">
    <label>Cover Buku</label>
    <div class="d-flex flex-column flex-md-row align-items-start align-items-md-center">
        <input type="file" name="cover" id="cover" accept="image/*" onchange="previewCover(this)"
            class="form-control mb-2 mb-md-0">
        <button type="button" onclick="resetCover()" class="btn btn-sm btn-outline-danger ml-md-3">Hapus</button>
    </div>
    <div class="input-group mt-2">
        <input type="url" id="coverUrl" placeholder="Atau masukkan URL gambar..." class="form-control">
        <div class="input-group-append">
            <button type="button" onclick="loadCoverFromUrl()" class="btn btn-outline-info">Gunakan URL</button>
        </div>
    </div>
    <div class="mt-3 text-center">
        <img id="coverPreview"
            src="{{ isset($buku) && $buku->cover ? asset('storage/' . $buku->cover) : 'https://via.placeholder.com/120x160?text=Preview' }}"
            width="120" class="img-thumbnail" style="cursor: pointer;" onclick="openCoverPopup()">
    </div>
    <div id="coverModal"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(0,0,0,0.6); z-index:1050; align-items:center; justify-content:center;">
        <span onclick="closeCoverPopup()"
            style="position:absolute;top:10px;right:20px;font-size:30px;color:white;cursor:pointer;">&times;</span>
        <img id="modalImage" src="" style="max-height:90%; max-width:90%; border:5px solid white;">
    </div>
</div>

<div class="d-flex justify-content-end">
    <x-adminlte-button class="btn-flat" type="submit" label="{{ $edit ? 'Update Buku' : 'Simpan Buku' }}"
        theme="success" icon="fas fa-save" />
</div>

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/jsbarcode@3.11.5/dist/JsBarcode.all.min.js"></script>
    <script>
        function generateBarcode() {
            const judul = document.querySelector('[name=judul_buku]').value;
            const isbn = document.getElementById('isbn').value;
            const kode = judul.trim().substring(0, 10).replace(/\s+/g, '_') + '-' + (isbn.trim() || 'GEN');
            document.getElementById('barcode').value = kode;
            JsBarcode("#barcodeCanvas", kode, {
                format: "CODE128",
                displayValue: true,
                fontSize: 14
            });
        }

        function previewCover(input) {
            const file = input.files[0];
            const preview = document.getElementById('coverPreview');
            if (file) {
                const reader = new FileReader();
                reader.onload = e => preview.src = e.target.result;
                reader.readAsDataURL(file);
                document.getElementById('coverUrl').value = '';
            }
        }

        function loadCoverFromUrl() {
            const url = document.getElementById('coverUrl').value;
            const preview = document.getElementById('coverPreview');
            if (url) {
                preview.src = url;
                document.getElementById('cover').value = "";
            }
        }

        function resetCover() {
            document.getElementById('cover').value = "";
            document.getElementById('coverUrl').value = "";
            document.getElementById('coverPreview').src = 'https://via.placeholder.com/120x160?text=Preview';
        }

        function openCoverPopup() {
            const img = document.getElementById('coverPreview');
            const modal = document.getElementById('coverModal');
            const modalImg = document.getElementById('modalImage');
            if (!img.src.includes('placeholder.com')) {
                modalImg.src = img.src;
                modal.style.display = 'flex';
            }
        }

        function closeCoverPopup() {
            document.getElementById('coverModal').style.display = 'none';
        }

        document.addEventListener('DOMContentLoaded', function() {
            generateBarcode();
            document.querySelector('[name=judul_buku]').addEventListener('input', generateBarcode);
            document.querySelector('[name=isbn]').addEventListener('input', generateBarcode);
        });
    </script>
@endpush

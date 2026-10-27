@extends('adminlte::page')

@section('title', 'Proses Buku Hilang')

@section('content_header')
    <h1><i class="fas fa-exclamation-triangle text-danger mr-1"></i> Proses Buku Hilang</h1>
@stop

@section('content')
    <div class="card card-danger">
        <div class="card-header">
            <h3 class="card-title"><i class="fas fa-list mr-1"></i> Daftar Buku Hilang</h3>
        </div>
        <div class="card-body p-0">
            <table class="table table-bordered table-striped mb-0" id="hilang-table">
                <thead>
                    <tr>
                        <th>Judul (Kode)</th>
                        <th style="width:240px">Buku Pengganti</th>
                        <th style="width:180px" class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($lost as $row)
                        <tr data-pivot="{{ $row->pivot_id }}">
                            <td>
                                <a href="{{ route('buku.show', $row->buku_id) }}" class="text-primary">
                                    {{ $row->judul_buku }} ({{ $row->kode_barcode }})
                                </a>
                            </td>
                            <td>
                                <select class="form-control form-control-sm select-pengganti">
                                    <option value="" selected disabled>— Pilih / Tambah —</option>
                                    <option value="new">+ Tambah Baru…</option>
                                </select>

                            </td>
                            <td class="text-center">
                                <span class="status-info badge badge-secondary"></span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modal Quick-Create Buku Pengganti --}}
    <div class="modal fade" id="modal-baru" tabindex="-1" aria-labelledby="modalBaruLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <form id="form-baru" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBaruLabel">
                        <i class="fas fa-plus-circle text-danger mr-1"></i> Tambah Buku Pengganti
                    </h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pivot-baru">

                    {{-- Penulis --}}
                    <div class="form-group">
                        <label for="new-penulis-select">Penulis</label>
                        <select id="new-penulis-select" class="form-control">
                            <option value="">— Pilih Penulis —</option>
                            @foreach ($penulisList as $pen)
                                <option value="pen:{{ $pen->id }}">{{ $pen->nama_penulis }}</option>
                            @endforeach
                            <option value="new">+ Tambah Baru…</option>
                        </select>
                    </div>
                    <div class="form-group" id="group-new-penulis" style="display:none">
                        <label for="new-penulis-name">Penulis Baru</label>
                        <input type="text" id="new-penulis-name" class="form-control" placeholder="Nama penulis baru">
                    </div>

                    {{-- Judul --}}
                    <div class="form-group">
                        <label for="new-judul">Judul Buku</label>
                        <input type="text" id="new-judul" class="form-control" placeholder="Judul buku" required>
                    </div>

                    {{-- Kategori --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="new-kategori-select">Kategori</label>
                            <select id="new-kategori-select" class="form-control">
                                <option value="">— Pilih Kategori —</option>
                                @foreach ($kategoriList as $k)
                                    <option value="kat:{{ $k->id }}" data-kode="{{ $k->kode_klasifikasi }}">
                                        {{ $k->nama_kategori }}</option>
                                @endforeach
                                <option value="new">+ Tambah Baru…</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="group-new-kategori" style="display:none">
                            <label for="new-kategori-name">Kategori Baru</label>
                            <input type="text" id="new-kategori-name" class="form-control" placeholder="Kategori baru">
                        </div>
                    </div>

                    {{-- Koleksi --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="new-koleksi-select">Jenis Koleksi</label>
                            <select id="new-koleksi-select" class="form-control">
                                <option value="">— Pilih Koleksi —</option>
                                @foreach ($koleksiList as $c)
                                    <option value="kol:{{ $c->id }}">{{ $c->nama_koleksi }}</option>
                                @endforeach
                                <option value="new">+ Tambah Baru…</option>
                            </select>
                        </div>
                        <div class="form-group col-md-6" id="group-new-koleksi" style="display:none">
                            <label for="new-koleksi-name">Koleksi Baru</label>
                            <input type="text" id="new-koleksi-name" class="form-control" placeholder="Koleksi baru">
                        </div>
                    </div>

                    {{-- Penerbit --}}
                    <div class="form-group">
                        <label for="new-penerbit-select">Penerbit</label>
                        <select id="new-penerbit-select" class="form-control">
                            <option value="">— Pilih Penerbit —</option>
                            @foreach ($penerbitList as $p)
                                <option value="pub:{{ $p->id }}">{{ $p->nama_penerbit }}</option>
                            @endforeach
                            <option value="new">+ Tambah Baru…</option>
                        </select>
                    </div>
                    <div class="form-group" id="group-new-penerbit" style="display:none">
                        <label for="new-penerbit-name">Penerbit Baru</label>
                        <input type="text" id="new-penerbit-name" class="form-control" placeholder="Penerbit baru">
                    </div>

                    {{-- Metadata lain --}}
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="new-tahun">Tahun Terbit</label>
                            <input type="number" id="new-tahun" class="form-control" min="1900"
                                max="{{ date('Y') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="new-halaman">Jumlah Halaman</label>
                            <input type="number" id="new-halaman" class="form-control" min="1">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="new-rak">Rak</label>
                        <select id="new-rak" class="form-control" required>
                            <option value="">— Pilih Rak —</option>
                            @foreach ($rakList as $r)
                                <option value="{{ $r->id }}">
                                    {{ $r->kode_rak }} @if ($r->lokasi)
                                        ({{ $r->lokasi }})
                                    @endif
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="new-sumber">Sumber Perolehan</label>
                        <input type="text" id="new-sumber" class="form-control" placeholder="Sumber perolehan">
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="new-tgl-perolehan">Tanggal Perolehan</label>
                            <input type="date" id="new-tgl-perolehan" class="form-control"
                                value="{{ date('Y-m-d') }}">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="new-harga">Harga</label>
                            <input type="number" id="new-harga" class="form-control" min="0"
                                placeholder="Harga">
                        </div>
                    </div>
                </div>
                <div class="modal-footer p-2">
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-plus mr-1"></i> Tambah
                    </button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modalBaru = $('#modal-baru');
            let lastSelect = null;

            // toggle field visibility
            ['penulis', 'kategori', 'koleksi', 'penerbit'].forEach(type => {
                document.getElementById(`new-${type}-select`)
                    .addEventListener('change', e => {
                        const show = e.target.value === 'new';
                        document.getElementById(`group-new-${type}`)
                            .style.display = show ? 'block' : 'none';
                        if (!show) document.getElementById(`new-${type}-name`).value = '';
                    });
            });

            // dropdown pengganti & tombol Simpan
            document.querySelectorAll('.select-pengganti').forEach(sel => {
                const row = sel.closest('tr');
                const info = row.querySelector('.status-info');

                sel.addEventListener('change', async () => {
                    info.textContent = '';

                    if (sel.value === 'new') {
                        lastSelect = sel;
                        document.getElementById('pivot-baru').value = sel.closest('tr').dataset
                            .pivot;
                        modalBaru.modal('show');
                    } else if (sel.value.startsWith('buku:')) {
                        // cek ketersediaan eksemplar
                        const bukuId = sel.value.split(':')[1];
                        const res = await fetch(
                            `{{ url('api/item-buku/cek-status') }}/${bukuId}`);
                        const json = await res.json();
                        if (!json.available) {
                            alert('Eksemplar tidak tersedia: ' + json.message);
                            sel.value = '';
                            return;
                        }
                        // langsung proses updatePivot tanpa tombol
                        const pivot = row.dataset.pivot;
                        try {
                            const resp = await fetch(
                                "{{ route('sirkulasi.hilang.updatePivot') }}", {
                                    method: 'POST',
                                    headers: {
                                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                        'Content-Type': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        pivot_id: pivot,
                                        pengganti_id: bukuId
                                    })
                                });
                            const d = await resp.json();
                            if (!d.success) throw new Error(d.message);

                            // tampilkan status lengkap
                            const origText = row.querySelector('td:first-child a').textContent
                                .trim();
                            const replText = sel.selectedOptions[0].text + ` (${d.kode || ''})`;
                            info.className = 'badge badge-success';
                            info.textContent =
                                `Buku ${origText} telah digantikan oleh Buku ${replText}`;

                            // disable dropdown biar user nggak ubah lagi
                            sel.disabled = true;
                        } catch (err) {
                            alert('Gagal simpan: ' + err.message);
                        }
                    }
                });
            });

            // reset dropdown bila modal ditutup
            modalBaru.on('hidden.bs.modal', () => {
                if (lastSelect) {
                    lastSelect.value = '';
                    lastSelect.selectedIndex = 0;
                }
                lastSelect = null;
            });


            // submit “Tambah Buku” di modal
            document.getElementById('form-baru').addEventListener('submit', async function(e) {
                e.preventDefault(); // cegah reload

                // 1) Ambil semua nilai dari modal
                const pid = document.getElementById('pivot-baru').value;
                const penV = document.getElementById('new-penulis-select').value;
                const penName = document.getElementById('new-penulis-name').value.trim();
                const katV = document.getElementById('new-kategori-select').value;
                const katName = document.getElementById('new-kategori-name').value.trim();
                const kolV = document.getElementById('new-koleksi-select').value;
                const kolName = document.getElementById('new-koleksi-name').value.trim();
                const pubV = document.getElementById('new-penerbit-select').value;
                const pubName = document.getElementById('new-penerbit-name').value.trim();
                const judul = document.getElementById('new-judul').value.trim();
                const tahun = document.getElementById('new-tahun').value || null;
                const halaman = document.getElementById('new-halaman').value || null;
                const rakId = document.getElementById('new-rak').value;
                const sumber = document.getElementById('new-sumber').value.trim() || null;
                const tglPer = document.getElementById('new-tgl-perolehan').value || null;
                const harga = document.getElementById('new-harga').value || null;

                // 2) Validasi minimal
                if (!judul || !rakId) {
                    return alert('Judul & Rak wajib diisi.');
                }

                // 3) Parse foreign keys / new names
                let penulis_id = null,
                    nama_penulis = null;
                if (penV.startsWith('pen:')) penulis_id = penV.split(':')[1];
                else if (penV === 'new') nama_penulis = penName;

                let kategori_id = null,
                    nama_kategori = null;
                if (katV.startsWith('kat:')) kategori_id = katV.split(':')[1];
                else if (katV === 'new') nama_kategori = katName;

                let koleksi_id = null,
                    nama_koleksi = null;
                if (kolV.startsWith('kol:')) koleksi_id = kolV.split(':')[1];
                else if (kolV === 'new') nama_koleksi = kolName;

                let penerbit_id = null,
                    nama_penerbit = null;
                if (pubV.startsWith('pub:')) penerbit_id = pubV.split(':')[1];
                else if (pubV === 'new') nama_penerbit = pubName;

                // 4) Susun payload
                const payload = {
                    pivot_id: pid,
                    buku_id: null,
                    penulis_id,
                    nama_penulis,
                    kategori_id,
                    nama_kategori,
                    koleksi_id,
                    nama_koleksi,
                    penerbit_id,
                    nama_penerbit,
                    judul_baru: judul,
                    tahun_terbit: tahun,
                    jumlah_halaman: halaman,
                    rak_id: rakId,
                    sumber_perolehan: sumber,
                    tanggal_perolehan: tglPer,
                    harga
                };

                try {
                    // 5) Kirim ke server
                    const res = await fetch("{{ route('sirkulasi.hilang.createBuku') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify(payload)
                    });
                    const d = await res.json();
                    if (!d.success) throw new Error(d.message);

                    // 6) Ambil elemen row & status badge SEKALI saja
                    const sel = lastSelect;
                    const row = sel.closest('tr');
                    const info = row.querySelector('.status-info');

                    // ——— Hitung Nomor Panggil ———
                    // a) Kode klasifikasi dari kategori
                    const kodeKlas = document
                        .getElementById('new-kategori-select')
                        .selectedOptions[0]
                        .dataset.kode || 'XXX';
                    // b) 3 huruf awal penulis
                    const penText = document
                        .getElementById('new-penulis-select')
                        .selectedOptions[0]
                        .text.replace(/[^A-Za-z]/g, '') || '';
                    const tigaHuruf = penText.substr(0, 3).toUpperCase() || 'PEN';
                    // c) huruf pertama judul
                    const firstHuruf = judul.charAt(0).toLowerCase() || 'j';
                    // d) susun call number
                    const callNumber = `${kodeKlas} ${tigaHuruf} ${firstHuruf}`;
                    // ambil judul + kode eksemplar original dari kolom pertama
                    const origCell = row.querySelector('td:first-child a');
                    const origText = origCell.textContent
                        .trim(); // e.g. "Kisah yang Hilang (INV-001248)"

                    // ambil judul + kode pengganti dari option text
                    const replText = sel.selectedOptions[0].text + ` (${d.kode || ''})`;
                    // jika kamu telah mengembalikan `kode` di JSON respon, gunakan d.kode
                    // e) tampilkan di badge
                    info.className = 'badge badge-success';
                    info.textContent = `Buku ${origText} telah digantikan oleh Buku ${replText}`;

                    // ——— Tambah opsi pengganti baru di <select> ———
                    const opt = document.createElement('option');
                    opt.value = `buku:${d.item_id}`;
                    opt.text = `${d.judul} (${d.kode})`;
                    sel.insertBefore(opt, sel.querySelector('option[value="new"]'));
                    sel.value = opt.value;

                    // 7) Bersihkan dan tutup modal
                    lastSelect = null;
                    modalBaru.modal('hide');

                } catch (err) {
                    alert(err.message || 'Gagal menambahkan buku.');
                }
            });
        });
    </script>
@stop

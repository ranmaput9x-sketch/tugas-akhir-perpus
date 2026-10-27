@extends('adminlte::page')

@section('title', 'Proses Buku Hilang')

@section('content_header')
    <h1>Proses Buku Hilang</h1>
@stop

@section('content')
    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">{{ $errors->first() }}</div>
    @endif

    <form action="{{ route('sirkulasi.hilang.store') }}" method="POST">
        @csrf

        <table class="table table-bordered" id="hilang-table">
            <thead>
                <tr>
                    <th>Judul (Kode)</th>
                    <th>Buku Pengganti</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody id="hilang-list">
                @foreach ($lost as $row)
                    <tr data-pivot="{{ $row->pivot_id }}">
                        <td>{{ $row->judul_buku }} ({{ $row->kode_barcode }})</td>
                        <td>
                            <select name="pengganti[{{ $row->pivot_id }}]"
                                class="form-control form-control-sm select-pengganti">
                                <option value="">— Pilih / Tambah —</option>
                                @foreach ($bukuList as $b)
                                    <option value="buku:{{ $b->id }}">{{ $b->judul_buku }}</option>
                                @endforeach
                                <option value="new">+ Tambah Baru…</option>
                            </select>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-success status-info"></span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <button type="submit" class="btn btn-primary">Simpan Pengganti</button>
    </form>

    <!-- Modal Quick-Create Buku -->
    <div class="modal fade" id="modal-baru" tabindex="-1" role="dialog" aria-labelledby="modalBaruLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <form id="form-baru" class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBaruLabel">Tambah Buku Pengganti</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="pivot-baru">

                    <!-- Judul -->
                    <div class="form-group">
                        <label for="new-judul">Judul Buku</label>
                        <input type="text" id="new-judul" class="form-control" required>
                    </div>
                    <!-- Pengarang -->
                    <div class="form-group">
                        <label for="new-pengarang">Pengarang</label>
                        <input type="text" id="new-pengarang" class="form-control">
                    </div>
                    <!-- Penerbit -->
                    <div class="form-group">
                        <label for="new-penerbit-select">Penerbit</label>
                        <select id="new-penerbit-select" class="form-control" required>
                            <option value="">— Pilih Penerbit —</option>
                            @foreach ($penerbitList as $p)
                                <option value="pub:{{ $p->id }}">{{ $p->nama_penerbit }}</option>
                            @endforeach
                            <option value="new">+ Tambah Baru…</option>
                        </select>
                    </div>
                    <div id="group-new-penerbit" class="form-group" style="display:none;">
                        <label for="new-penerbit-name">Nama Penerbit Baru</label>
                        <input type="text" id="new-penerbit-name" class="form-control">
                    </div>
                    <!-- Tahun Terbit -->
                    <div class="form-group">
                        <label for="new-tahun">Tahun Terbit</label>
                        <input type="number" id="new-tahun" class="form-control" min="1900" max="{{ date('Y') }}">
                    </div>
                    <!-- Jumlah Halaman -->
                    <div class="form-group">
                        <label for="new-halaman">Jumlah Halaman</label>
                        <input type="number" id="new-halaman" class="form-control" min="1">
                    </div>
                    <!-- Rak -->
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
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Simpan & Pilih</button>
                </div>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // toggle field Nama Penerbit baru
            $('#new-penerbit-select').on('change', function() {
                $('#group-new-penerbit').toggle(this.value === 'new');
            });

            // handler select-pengganti
            $('.select-pengganti').on('change', function() {
                const val = this.value,
                    tr = $(this).closest('tr'),
                    pid = tr.data('pivot'),
                    info = tr.find('.status-info').text('');
                if (val === 'new') {
                    $('#pivot-baru').val(pid);
                    $('#modal-baru').modal('show');
                } else if (val.startsWith('buku:')) {
                    const bid = val.split(':')[1];
                    info.text(`↪ OK (ID ${bid})`);
                }
            });

            // quick-create submit
            $('#form-baru').on('submit', async function(e) {
                e.preventDefault();
                const pid = $('#pivot-baru').val(),
                    payload = {
                        pivot_id: pid,
                        judul_baru: $('#new-judul').val().trim(),
                        pengarang: $('#new-pengarang').val().trim(),
                        penerbit_id: $('#new-penerbit-select').val().startsWith('pub:') ?
                            +$('#new-penerbit-select').val().split(':')[1] : null,
                        nama_penerbit: $('#new-penerbit-select').val() === 'new' ?
                            $('#new-penerbit-name').val().trim() : null,
                        tahun_terbit: $('#new-tahun').val() || null,
                        jumlah_halaman: $('#new-halaman').val() || null,
                        rak_id: +$('#new-rak').val()
                    };
                const res = await fetch("{{ route('sirkulasi.hilang.createBuku') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(payload)
                });
                const d = await res.json();
                if (!d.success) return alert(d.message);
                // insert & select baru
                const sel = $(`tr[data-pivot="${pid}"] .select-pengganti`);
                $('<option>')
                    .attr('value', `buku:${d.item_id}`)
                    .text(d.judul)
                    .insertBefore(sel.find('option[value="new"]'));
                sel.val(`buku:${d.item_id}`)
                    .trigger('change');
                $('#modal-baru').modal('hide');
            });
        });
    </script>
@stop

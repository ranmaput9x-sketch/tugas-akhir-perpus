@extends('adminlte::page')

@section('title', 'Daftar Anggota')

@section('content_header')
    <h1>Daftar Anggota</h1>
@stop

@section('content')
    {{-- Notifikasi Sukses/Gagal --}}
    @if (session('success'))
        <x-adminlte-alert theme="success" title="Sukses" dismissable>
            {{ session('success') }}
        </x-adminlte-alert>
    @endif
    @if (session('warning'))
        <x-adminlte-alert theme="warning" title="Peringatan" dismissable>
            {{ session('warning') }}
        </x-adminlte-alert>
    @endif
    @if (session('error'))
        <x-adminlte-alert theme="danger" title="Gagal" dismissable>
            {{ session('error') }}
        </x-adminlte-alert>
    @endif

    <div class="card">
        <div class="card-header">
            <a href="{{ route('anggota.create') }}" class="btn btn-primary">
                <i class="fas fa-plus mr-1"></i> Tambah Anggota
            </a>
        </div>
        <div class="card-body">
            {{-- Form untuk Aksi Massal --}}
            <form id="bulk-action-form" action="{{ route('anggota.bulk-action') }}" method="POST">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-5 col-lg-4">
                        <div class="input-group">
                            <select name="action" class="form-control" required>
                                <option value="">-- Pilih Aksi Massal --</option>
                                <option value="activate">Ubah Status jadi Aktif</option>
                                <option value="deactivate">Ubah Status jadi Non-Aktif</option>
                                <option value="delete">Hapus Anggota Terpilih</option>
                            </select>
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">Terapkan</button>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tabel Anggota --}}
                <table id="anggotaTable" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px"><input type="checkbox" id="selectAll"></th>
                            <th>Nama Anggota</th>
                            <th>ID Anggota</th>
                            <th>Tingkat</th>
                            <th>Status</th>
                            <th style="width: 15%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($anggotas as $anggota)
                            <tr>
                                <td><input type="checkbox" name="selected_ids[]" value="{{ $anggota->id }}"></td>
                                <td>{{ $anggota->nama_anggota }}</td>
                                <td>{{ $anggota->id_anggota }}</td>
                                <td>{{ $anggota->tingkat }}</td>
                                <td class="text-center">
                                    {{-- Badge Status Dinamis --}}
                                    @php
                                        $status = $anggota->status;
                                        $badgeClass = 'secondary';
                                        if ($status == 'aktif') {
                                            $badgeClass = 'success';
                                        } elseif ($status == 'non-aktif') {
                                            $badgeClass = 'danger';
                                        } elseif ($status == 'kadaluwarsa') {
                                            $badgeClass = 'warning';
                                        } elseif ($status == 'alumni') {
                                            $badgeClass = 'info';
                                        }
                                    @endphp
                                    <span class="badge badge-{{ $badgeClass }}">{{ ucfirst($status) }}</span>
                                </td>
                                <td class="text-center">
                                    {{-- Tombol Aksi Satuan --}}
                                    <a href="{{ route('anggota.show', $anggota->id) }}" class="btn btn-xs btn-primary"
                                        title="Lihat Detail"><i class="fa fa-eye"></i></a>
                                    <a href="{{ route('anggota.edit', $anggota->id) }}" class="btn btn-xs btn-warning"
                                        title="Edit"><i class="fa fa-edit"></i></a>
                                    {{-- <a href="{{ route('anggota.cetak-kta', $anggota->id) }}" class="btn btn-xs btn-info"
                                        title="Cetak KTA" target="_blank"><i class="fa fa-id-card"></i></a> --}}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            $('#anggotaTable').DataTable({
                responsive: true,
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_ entri",
                    zeroRecords: "Tidak ada data yang cocok ditemukan",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ entri",
                    infoEmpty: "Tidak ada data yang tersedia",
                    infoFiltered: "(disaring dari _MAX_ total entri)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    }
                },
                // Menonaktifkan auto-sort di kolom pertama (checkbox)
                "columnDefs": [{
                    "targets": 0,
                    "orderable": false
                }]
            });

            // Fungsi untuk checkbox "Pilih Semua"
            $('#selectAll').on('click', function() {
                // Cek semua checkbox di baris tabel
                const rows = $('#anggotaTable').DataTable().rows({
                    'search': 'applied'
                }).nodes();
                $('input[type="checkbox"]', rows).prop('checked', this.checked);
            });

            // Meng-handle state checkbox "Pilih Semua" jika user memilih/batal memilih satu per satu
            $('#anggotaTable tbody').on('change', 'input[type="checkbox"]', function() {
                if (!this.checked) {
                    const el = $('#selectAll').get(0);
                    if (el && el.checked && ('indeterminate' in el)) {
                        el.indeterminate = true;
                    }
                }
            });

            // Konfirmasi sebelum submit aksi massal
            $('#bulk-action-form').on('submit', function(e) {
                const form = this;
                const action = $('select[name="action"]').val();

                if (!action) {
                    alert('Silakan pilih aksi terlebih dahulu.');
                    e.preventDefault();
                    return;
                }
                if ($('input[name="selected_ids[]"]:checked').length === 0) {
                    alert('Silakan pilih setidaknya satu anggota.');
                    e.preventDefault();
                    return;
                }

                e.preventDefault(); // Mencegah form submit langsung

                let confirmationText =
                    'Apakah Anda yakin ingin menerapkan aksi ini pada data yang dipilih?';
                if (action === 'delete') {
                    confirmationText =
                        'PERINGATAN: Aksi ini akan menghapus data anggota secara permanen. Anda yakin?';
                }

                if (confirm(confirmationText)) {
                    form.submit();
                }
            });
        });
    </script>
@stop

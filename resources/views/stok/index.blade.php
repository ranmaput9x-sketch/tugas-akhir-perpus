@extends('adminlte::page')

@section('title', 'Stok & Kondisi Buku')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Stok & Kondisi Buku </h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Stok & Kondisi Buku</li>
        </ol>
    </div>
@endsection

@section('content')
    <x-adminlte-card title="Filter Rak & Cari Buku" theme="info" icon="fas fa-filter">
        <form method="GET" action="{{ route('stok.index') }}" class="form-inline mb-3">
            <x-adminlte-select name="rak" label="Pilih Rak" igroup-size="sm" fgroup-class="mr-3">
                <option value="">Semua Rak</option>
                @foreach ($raks as $rak)
                    <option value="{{ $rak }}" {{ request('rak') == $rak ? 'selected' : '' }}>{{ $rak }}
                    </option>
                @endforeach
            </x-adminlte-select>

            <x-adminlte-input name="search" label="Cari Judul" igroup-size="sm" placeholder="Judul buku"
                value="{{ request('search') }}" fgroup-class="mr-3" />

            <x-adminlte-button type="submit" label="Filter" theme="primary" icon="fas fa-search" />
        </form>
    </x-adminlte-card>

    @if (session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: '{{ session('success') }}',
                    showConfirmButton: false,
                    timer: 2000
                });
            });
        </script>
    @endif

    <x-adminlte-card title="Daftar Buku" theme="primary" icon="fas fa-book">
        <div class="table-responsive">
            <table class="table table-bordered table-striped table-sm">
                <thead class="bg-light">
                    <tr>
                        <th>Judul</th>
                        <th>Rak</th>
                        <th>Stok</th>
                        <th>Rusak</th>
                        <th>Ditarik</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($bukus as $buku)
                        <tr>
                            <td>{{ $buku->judul_buku }}</td>
                            <td>{{ $buku->rak ?? '-' }}</td>
                            <td>
                                <form action="{{ route('stok.update', $buku->id) }}" method="POST" class="form-inline">
                                    @csrf
                                    <input type="number" name="stok" value="{{ $buku->stok }}"
                                        class="form-control form-control-sm" min="0" style="width: 80px">
                                    <input type="hidden" name="stok_rusak" value="{{ $buku->stok_rusak }}">
                                    <input type="hidden" name="stok_ditarik" value="{{ $buku->stok_ditarik }}">
                                    <button class="btn btn-sm btn-success ml-1"><i class="fas fa-save"></i></button>
                                </form>
                            </td>
                            <td>
                                <button
                                    class="btn btn-sm toggle-btn {{ $buku->stok_rusak == 1 ? 'btn-danger' : 'btn-secondary' }}"
                                    data-id="{{ $buku->id }}" data-field="stok_rusak">
                                    <span class="label-text">
                                        <i
                                            class="fas {{ $buku->stok_rusak == 1 ? 'fa-check-circle text-white' : 'fa-times-circle text-white' }} mr-1"></i>
                                        {{ $buku->stok_rusak == 1 ? 'Ya' : 'Tidak' }}
                                    </span>
                                </button>
                            </td>
                            <td>
                                <button
                                    class="btn btn-sm toggle-btn {{ $buku->stok_ditarik == 1 ? 'btn-warning' : 'btn-secondary' }}"
                                    data-id="{{ $buku->id }}" data-field="stok_ditarik">
                                    <span class="label-text">
                                        <i
                                            class="fas {{ $buku->stok_ditarik == 1 ? 'fa-check-circle text-white' : 'fa-times-circle text-white' }} mr-1"></i>
                                        {{ $buku->stok_ditarik == 1 ? 'Ya' : 'Tidak' }}
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Tidak ada data buku</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </x-adminlte-card>
@endsection

@push('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.toggle-btn').forEach(function(btn) {
                btn.addEventListener('click', function() {
                    const id = this.dataset.id;
                    const field = this.dataset.field;
                    const token = '{{ csrf_token() }}';
                    const button = this;

                    fetch(`/stok/toggle/${id}`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token
                            },
                            body: JSON.stringify({
                                field: field
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                const label = button.querySelector('.label-text');
                                const newValue = data.value == 1;

                                label.innerHTML =
                                    `<i class="fas ${newValue ? 'fa-check-circle text-white' : 'fa-times-circle text-white'} mr-1"></i> ${newValue ? 'Ya' : 'Tidak'}`;

                                if (field === 'stok_rusak') {
                                    button.className = 'btn btn-sm toggle-btn ' + (newValue ?
                                        'btn-danger' : 'btn-secondary');
                                } else if (field === 'stok_ditarik') {
                                    button.className = 'btn btn-sm toggle-btn ' + (newValue ?
                                        'btn-warning' : 'btn-secondary');
                                }

                                Swal.fire({
                                    toast: true,
                                    position: 'top-end',
                                    icon: 'success',
                                    title: `Status berhasil diubah jadi ${newValue ? 'Ya' : 'Tidak'}`,
                                    showConfirmButton: false,
                                    timer: 1500
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Gagal toggle kondisi:', error);
                            alert('Gagal mengubah status. Silakan coba lagi.');
                        });
                });
            });
        });
    </script>
@endpush

@extends('adminlte::page')

@section('title', 'Preview Data Anggota')

@section('content_header')
    <h1>Preview Data Import untuk Tingkat: {{ ucfirst($tingkat) }}</h1>
@stop

@section('content')

    {{-- BAGIAN DATA YANG VALID --}}
    @if (!empty($validData))
        <div class="card">
            <div class="card-header bg-success text-white">
                <h3 class="card-title">{{ count($validData) }} Data Siap untuk Diimpor</h3>
            </div>
            <div class="card-body">
                <form action="{{ route('anggota.import.submit') }}" method="POST">
                    @csrf
                    <table class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Nama Lengkap</th>
                                <th>{{ $tingkat == 'siswa' ? 'NIS' : 'NIP' }}</th>
                                <th>Alamat</th>
                                <th>Jenis Kelamin</th>
                                <th>Tanggal Lahir</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($validData as $item)
                                <tr>
                                    <td>{{ $item['nama_anggota'] }}</td>
                                    <td>{{ $item['nomor_induk'] }}</td>
                                    <td>{{ $item['alamat'] }}</td>
                                    <td>{{ $item['jenis_kelamin'] }}</td>
                                    <td>{{ $item['tanggal_lahir'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-3">
                        <button type="submit" class="btn btn-success">
                            <i class="fas fa-check"></i> Import {{ count($validData) }} Data Ini
                        </button>
                        <a href="{{ route('anggota.import.form') }}" class="btn btn-secondary ml-2">
                            <i class="fas fa-times"></i> Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- BAGIAN DATA YANG DITOLAK --}}
    @if (!empty($invalidData))
        <div class="card">
            <div class="card-header bg-danger text-white">
                <h3 class="card-title">{{ count($invalidData) }} Data Ditolak (Tidak Akan Diimpor)</h3>
            </div>
            <div class="card-body">
                <p>Data berikut tidak akan diimpor karena duplikat atau kolom wajib tidak diisi. Silakan perbaiki file Excel
                    Anda dan upload ulang.</p>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>Nama Lengkap</th>
                            <th>{{ $tingkat == 'siswa' ? 'NIS' : 'NIP' }}</th>
                            <th>Alasan Ditolak</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($invalidData as $item)
                            <tr>
                                <td>{{ $item['nama_anggota'] }}</td>
                                <td>{{ $item['nomor_induk'] }}</td>
                                <td><span class="text-danger">{{ $item['reason'] }}</span></td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    @endif

    {{-- JIKA TIDAK ADA DATA SAMA SEKALI --}}
    @if (empty($validData) && empty($invalidData))
        <div class="card">
            <div class="card-body">
                <x-adminlte-alert theme="warning" title="File Kosong">
                    Tidak ada data yang bisa diproses dari file yang Anda upload.
                    <a href="{{ route('anggota.import.form') }}" class="btn btn-sm btn-primary ml-2">Upload Ulang</a>
                </x-adminlte-alert>
            </div>
        </div>
    @endif

@stop

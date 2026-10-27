@extends('adminlte::page')

@section('title', 'Print Data Anggota')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1>Print Preview Anggota</h1>
        <button class="btn btn-primary btn-sm no-print" onclick="window.print()">
            <i class="fas fa-print"></i> Cetak Halaman
        </button>
    </div>
@stop

@section('content')
    <div class="card">
        <div class="card-header bg-success text-white">
            <h3 class="card-title">Tabel Anggota</h3>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-sm">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>ID Anggota</th>
                            <th>Nama Lengkap</th>
                            <th>{{ $nomorIndukHeader }}</th>
                            <th>Status</th>
                            <th>Tingkat</th>
                            <th>Tanggal Lahir</th>
                            <th>Tanggal Registrasi</th>
                            <th>Berlaku Hingga</th>
                            <th>Jenis Kelamin</th>
                            <th>Alamat Lengkap</th>
                            <th>Foto</th>
                            <th>Barcode</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">
                        @foreach ($anggotas as $a)
                            <tr>
                                <td>{{ $a->id_anggota }}</td>
                                <td>{{ $a->nama_anggota }}</td>
                                <td>{{ $a->nomor_induk }}</td>
                                <td>{{ $a->status }}</td>
                                <td>{{ $a->tingkat }}</td>
                                <td>{{ $a->tanggal_lahir }}</td>
                                <td>{{ $a->tanggal_registrasi }}</td>
                                <td>{{ $a->berlaku_hingga }}</td>
                                <td>{{ $a->jenis_kelamin }}</td>
                                <td>{{ $a->alamat }}</td>
                                <td>
                                    @if ($a->foto && file_exists(public_path('storage/foto/' . $a->foto)))
                                        <img src="{{ asset('storage/foto/' . $a->foto) }}" class="img-thumbnail"
                                            style="height: 60px;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $barcodePath = 'storage/barcode/' . $a->id_anggota . '.png';
                                    @endphp
                                    @if (file_exists(public_path($barcodePath)))
                                        <img src="{{ asset($barcodePath) }}" class="img-thumbnail" style="height: 60px;">
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            .content-header,
            .main-footer,
            .sidebar {
                display: none !important;
            }

            .content-wrapper {
                margin: 0 !important;
                padding: 0 !important;
            }
        }
    </style>
@stop

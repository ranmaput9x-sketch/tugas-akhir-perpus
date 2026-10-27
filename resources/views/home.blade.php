@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <div class="row mb-2">
        <div class="col-sm-6">
            <h1 class="m-0">Dashboard</h1>
        </div>
        <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
                <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                <li class="breadcrumb-item active">Dashboard</li>
            </ol>
        </div>
    </div>
@stop

@section('content')
    <div class="container-fluid">

        {{-- KPI Cards --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ number_format($stats['total_buku'], 0, ',', '.') }}</h3>
                        <p>Total Buku</p>
                    </div>
                    <div class="icon"><i class="fas fa-book"></i></div>
                    <a href="{{ route('buku.index') }}" class="small-box-footer">
                        Lihat semua <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ number_format($stats['total_anggota'], 0, ',', '.') }}</h3>
                        <p>Total Anggota</p>
                    </div>
                    <div class="icon"><i class="fas fa-users"></i></div>
                    <a href="{{ route('anggota.index') }}" class="small-box-footer">
                        Lihat semua <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ number_format($stats['pinjaman_aktif'], 0, ',', '.') }}</h3>
                        <p>Pinjaman Aktif</p>
                    </div>
                    <div class="icon"><i class="fas fa-book-reader"></i></div>
                    <a href="{{ route('sirkulasi.hasil', ['status' => 'Dipinjam']) }}" class="small-box-footer">
                        Kelola pinjaman <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ number_format($buku_hilang ?? 0, 0, ',', '.') }}</h3>
                        <p>Buku Hilang</p>
                    </div>
                    <div class="icon"><i class="fas fa-exclamation-triangle"></i></div>
                    <a href="{{ route('sirkulasi.hilang.index') }}" class="small-box-footer">
                        Tinjau <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>

        {{-- Bulan ini --}}
        <div class="row">
            <div class="col-md-6">
                <div class="info-box bg-primary">
                    <span class="info-box-icon"><i class="fas fa-arrow-circle-up"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Peminjaman Bulan Ini</span>
                        <span class="info-box-number">{{ number_format($pinjaman_bulan_ini ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-box bg-success">
                    <span class="info-box-icon"><i class="fas fa-arrow-circle-down"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Pengembalian Bulan Ini</span>
                        <span class="info-box-number">{{ number_format($pengembalian_bulan_ini ?? 0, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Chart --}}
        <div class="row">
            <div class="col-12">
                <div class="card card-outline card-primary">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-line mr-1"></i> Ringkasan Bulanan
                        </h3>
                    </div>
                    <div class="card-body" style="height:360px">
                        <canvas id="chartSirkulasi"></canvas>
                    </div>
                </div>
            </div>
        </div>

        {{-- Pinjaman terbaru + sidebar --}}
        <div class="row">
            <div class="col-lg-8">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-history mr-1"></i> Pinjaman Terbaru
                        </h3>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-sm table-hover mb-0">
                            <thead class="thead-light">
                                <tr>
                                    <th style="width: 140px;">Tanggal</th>
                                    <th>Anggota</th>
                                    <th>Judul Buku</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($pinjaman_terbaru as $p)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($p->created_at)->translatedFormat('d M Y') }}</td>
                                        <td>
                                            <a href="{{ route('anggota.show', $p->anggota_id ?? 0) }}">
                                                {{ $p->nama_anggota }}
                                            </a>
                                        </td>
                                        <td>
                                            <a href="{{ route('buku.show', $p->buku_id ?? 0) }}">
                                                {{ $p->judul_buku }}
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center text-muted py-3">
                                            Belum ada pinjaman terbaru.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('sirkulasi.hasil', ['status' => 'Dipinjam']) }}" class="btn btn-sm btn-primary">
                            Lihat semua transaksi <i class="fas fa-arrow-circle-right ml-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card card-outline card-secondary">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-bolt mr-1"></i> Aksi Cepat</h3>
                    </div>
                    <div class="card-body">
                        <a href="{{ route('buku.create') }}" class="btn btn-outline-primary btn-block">
                            <i class="fas fa-plus mr-1"></i> Tambah Buku
                        </a>
                        <a href="{{ route('anggota.create') }}" class="btn btn-outline-success btn-block">
                            <i class="fas fa-user-plus mr-1"></i> Tambah Anggota
                        </a>
                        <a href="{{ route('sirkulasi.index') }}" class="btn btn-outline-warning btn-block">
                            <i class="fas fa-exchange-alt mr-1"></i> Transaksi Sirkulasi
                        </a>
                        <a href="{{ route('sirkulasi.hilang.index') }}" class="btn btn-outline-danger btn-block">
                            <i class="fas fa-exclamation-triangle mr-1"></i> Laporan Buku Hilang
                        </a>
                    </div>
                </div>

                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title"><i class="fas fa-info-circle mr-1"></i> Ringkas</h3>
                    </div>
                    <div class="card-body p-0">
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Peminjaman bulan ini
                                <span class="badge badge-primary badge-pill">
                                    {{ number_format($pinjaman_bulan_ini ?? 0, 0, ',', '.') }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Pengembalian bulan ini
                                <span class="badge badge-success badge-pill">
                                    {{ number_format($pengembalian_bulan_ini ?? 0, 0, ',', '.') }}
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Pinjaman aktif
                                <span class="badge badge-warning badge-pill">
                                    {{ number_format($stats['pinjaman_aktif'], 0, ',', '.') }}
                                </span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
@stop

@section('css')
    <style>
        /* AdminLTE sudah bagus; jangan “nabrak” styling bawaan small-box */
        .small-box .icon {
            opacity: .25;
        }

        .small-box-footer {
            text-decoration: none !important;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const canvas = document.getElementById('chartSirkulasi');
            if (!canvas) return;

            const ctx = canvas.getContext('2d');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: @json($bulan),
                    datasets: [{
                            label: 'Peminjaman',
                            data: @json($data_peminjaman),
                            borderColor: '#17a2b8',
                            backgroundColor: 'rgba(23,162,184,.12)',
                            fill: true,
                            tension: .25,
                            pointRadius: 2
                        },
                        {
                            label: 'Pengembalian',
                            data: @json($data_pengembalian),
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255,193,7,.12)',
                            fill: true,
                            tension: .25,
                            pointRadius: 2
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false
                    },
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        tooltip: {
                            callbacks: {
                                label: c =>
                                    `${c.dataset.label}: ${Number(c.parsed.y || 0).toLocaleString('id-ID')}`
                            }
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: v => Number(v).toLocaleString('id-ID')
                            }
                        }
                    }
                }
            });
        });
    </script>
@stop

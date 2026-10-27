@extends('adminlte::page')

@section('title', 'Dashboard Perpustakaan')

@section('content_header')
    <div class="d-flex justify-content-between">
        <h1>
            <i class="fas fa-tachometer-alt"></i> Dashboard
            <small class="text-muted">Perpustakaan Digital</small>
        </h1>
        <div>
            <button class="btn btn-primary" data-toggle="modal" data-target="#addBookModal">
                <i class="fas fa-book"></i> Tambah Buku
            </button>
            <button class="btn btn-success" data-toggle="modal" data-target="#addMemberModal">
                <i class="fas fa-user-plus"></i> Daftar Anggota
            </button>
        </div>
    </div>
@stop

@section('content')
    {{-- Flash Alert --}}

    {{-- KPI Cards --}}
    <div class="row">
        <div class="col-lg-3 col-6">
            <div class="small-box bg-info">
                <div class="inner">
                    <h3>{{ $totalBooks ?? 0 }}</h3>
                    <p>Total Buku</p>
                </div>
                <div class="icon">
                    <i class="fas fa-book"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Info detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-success">
                <div class="inner">
                    <h3>{{ $activeMembers ?? 0 }}</h3>
                    <p>Anggota Aktif Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-users"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Info detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-warning">
                <div class="inner">
                    <h3>{{ $activeLoans ?? 0 }}</h3>
                    <p>Peminjaman Aktif</p>
                </div>
                <div class="icon">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Info detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
        <div class="col-lg-3 col-6">
            <div class="small-box bg-primary">
                <div class="inner">
                    <h3>{{ $returnsToday ?? 0 }}</h3>
                    <p>Pengembalian Hari Ini</p>
                </div>
                <div class="icon">
                    <i class="fas fa-undo-alt"></i>
                </div>
                <a href="#" class="small-box-footer">
                    Info detail <i class="fas fa-arrow-circle-right"></i>
                </a>
            </div>
        </div>
    </div>

    {{-- Overdue & Reminder --}}


    {{-- Charts --}}
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-line mr-1"></i>
                        Tren Peminjaman Bulanan
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="loanTrendsChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">
                        <i class="fas fa-chart-pie mr-1"></i>
                        Kategori Terpopuler
                    </h3>
                </div>
                <div class="card-body">
                    <canvas id="categoryChart" style="height: 300px;"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Recent Transactions --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                <i class="fas fa-history mr-1"></i>
                Transaksi Terbaru
            </h3>
            <div class="card-tools">
                <div class="input-group input-group-sm" style="width: 250px;">
                    <input type="text" class="form-control" placeholder="Cari transaksi...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-body table-responsive p-0">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th>ID Transaksi</th>
                        <th>Nama Anggota</th>
                        <th>Judul Buku</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    {{-- Loop transaksi di sini --}}
                </tbody>
            </table>
        </div>
        <div class="card-footer clearfix">
            <ul class="pagination pagination-sm m-0 float-right">
                <li class="page-item"><a class="page-link" href="#">«</a></li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
        </div>
    </div>

    {{-- Modals --}}
    @include('partials.modals.addBookModal')
    @include('partials.modals.addMemberModal')
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Loan Trends Chart
            const loanTrendsChart = new Chart(document.getElementById('loanTrendsChart'), {
                type: 'line',
                data: {
                    labels: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun'],
                    datasets: [{
                        label: 'Peminjaman',
                        data: [65, 59, 80, 81, 56, 55],
                        tension: 0.1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });

            // Category Chart
            const categoryChart = new Chart(document.getElementById('categoryChart'), {
                type: 'pie',
                data: {
                    labels: ['Novel', 'Pendidikan', 'Teknologi', 'Sejarah', 'Lainnya'],
                    datasets: [{
                        data: [30, 25, 20, 15, 10]
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false
                }
            });
        });

        // Notifications
        $(document).Toasts('create', {
            title: 'Pemberitahuan',
            body: 'Reminder pengembalian telah dikirim',
            autohide: true,
            delay: 3000,
            class: 'bg-success'
        });
    </script>
@stop

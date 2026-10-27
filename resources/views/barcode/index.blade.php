@extends('adminlte::page')

@section('title', 'Pilih Buku untuk Cetak Barcode')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="text-dark">Pilih Buku untuk Barcode</h1>
        <ol class="breadcrumb float-sm-right">
            <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
            <li class="breadcrumb-item active">Cetak Barcode</li>
        </ol>
    </div>
@stop

@section('content')
    <form action="{{ route('barcode.preview') }}" method="POST">
        @csrf
        <div class="card">
            <div class="card-header bg-success">
                <h3 class="card-title">Form Pilih Buku</h3>
            </div>
            <div class="card-body">
                <div class="form-group">
                    <label for="jumlah">Jumlah label per buku</label>
                    <input type="number" name="jumlah" id="jumlah" class="form-control" value="1" min="1"
                        required>
                </div>
                <div class="form-group">
                    <label for="ukuran">Ukuran Label</label>
                    <select name="ukuran" id="ukuran" class="form-control">
                        <option value="small">Kecil</option>
                        <option value="medium" selected>Sedang</option>
                        <option value="large">Besar</option>
                    </select>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th><input type="checkbox" id="checkAll"></th>
                            <th>Judul Buku</th>
                            <th>Barcode</th>
                            <th>Kategori</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($bukus as $buku)
                            <tr>
                                <td>
                                    <input type="checkbox" name="buku_ids[]" value="{{ $buku->id }}">
                                </td>
                                <td>{{ $buku->judul_buku }}</td>
                                <td>{{ $buku->barcode }}</td>
                                <td>{{ $buku->kategori }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary mt-3">
                    <i class="fas fa-eye"></i> Preview Barcode
                </button>
            </div>
        </div>
    </form>
@stop

@section('js')
    <script>
        document.getElementById('checkAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[name="buku_ids[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
@stop

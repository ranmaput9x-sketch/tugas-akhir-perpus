@extends('adminlte::page')

@section('title', 'Pilih Anggota untuk Cetak KTA')

@section('content_header')
    <h1>Cetak Kartu Tanda Anggota (KTA) Massal</h1>
@stop

@section('content')
    {{-- FORM UNTUK FILTER --}}
    <div class="card">
        <div class="card-body">
            <form action="{{ route('anggota.form-cetak-kta-massal') }}" method="GET">
                <div class="form-group row">
                    <label for="tingkat" class="col-sm-2 col-form-label">Tampilkan Anggota:</label>
                    <div class="col-sm-4">
                        <select name="tingkat" id="tingkat" class="form-control" onchange="this.form.submit()">
                            <option value="">Semua Tingkat</option>
                            <option value="Siswa" @if (request('tingkat') == 'Siswa') selected @endif>Hanya Siswa</option>
                            <option value="Guru" @if (request('tingkat') == 'Guru') selected @endif>Hanya Guru</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- FORM UNTUK CETAK DATA YANG SUDAH DIFILTER --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Pilih Anggota yang Akan Dicetak</h3>
        </div>
        <div class="card-body">
            <form action="{{ route('anggota.cetak-kta-massal') }}" method="POST" target="_blank">
                @csrf
                <div class="mb-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print"></i> Tampilkan Preview Kartu Terpilih
                    </button>
                </div>

                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th style="width: 10px"><input type="checkbox" id="selectAll"></th>
                            <th>Nama Anggota</th>
                            <th>ID Anggota</th>
                            <th>Tingkat</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($anggotas as $anggota)
                            <tr>
                                <td><input type="checkbox" name="selected_ids[]" value="{{ $anggota->id }}"></td>
                                <td>{{ $anggota->nama_anggota }}</td>
                                <td>{{ $anggota->id_anggota }}</td>
                                <td>{{ $anggota->tingkat }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data anggota yang cocok dengan filter.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </form>
        </div>
    </div>
@stop

@section('js')
    <script>
        document.getElementById('selectAll').addEventListener('click', function(event) {
            const checkboxes = document.querySelectorAll('input[name="selected_ids[]"]');
            checkboxes.forEach(checkbox => {
                checkbox.checked = event.target.checked;
            });
        });
    </script>
@stop

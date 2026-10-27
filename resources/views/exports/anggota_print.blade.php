@extends('adminlte::page')

@section('title', 'Print Data Anggota')

@section('content')
    <div class="card">
        <div class="card-header bg-info">
            <h3 class="card-title text-white">Print Data Anggota</h3>
        </div>
        <div class="card-body">
            <table class="table table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <th>Nama</th>
                        <th>Tgl Lahir</th>
                        <th>Registrasi</th>
                        <th>Berlaku</th>
                        <th>JK</th>
                        <th>Alamat</th>
                        <th>Foto</th>
                        <th>Barcode</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($anggotas as $item)
                        <tr>
                            <td>{{ $item->id_anggota }}</td>
                            <td>{{ $item->nama_anggota }}</td>
                            <td>{{ $item->tanggal_lahir }}</td>
                            <td>{{ $item->tanggal_registrasi }}</td>
                            <td>{{ $item->berlaku_hingga }}</td>
                            <td>{{ $item->jenis_kelamin }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>
                                @php $fotoPath = 'storage/foto/' . $item->foto; @endphp
                                @if ($item->foto && file_exists(public_path($fotoPath)))
                                    <img src="{{ asset($fotoPath) }}" width="60">
                                @else
                                    -
                                @endif
                            </td>
                            <td>
                                @php $barcodePath = 'storage/barcode/' . $item->id_anggota . '.png'; @endphp
                                @if (file_exists(public_path($barcodePath)))
                                    <img src="{{ asset($barcodePath) }}" width="100">
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <button class="btn btn-primary mt-3" onclick="window.print()">Print Halaman Ini</button>
        </div>
    </div>
@stop

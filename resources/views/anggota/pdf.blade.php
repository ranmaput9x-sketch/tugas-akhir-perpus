<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Data Anggota</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        th {
            background-color: #e6e6e6;
        }

        img {
            max-height: 70px;
        }
    </style>
</head>

<body>
    <h2 style="text-align:center;">Daftar Anggota</h2>
    <table>
        <thead>
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
                {{-- <th>Barcode</th> --}}
            </tr>
        </thead>

        <tbody>
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
                        {{-- Tambahkan pengecekan ini --}}
                        @if ($a->foto && file_exists(public_path('storage/' . $a->foto)))
                            <img src="{{ public_path('storage/' . $a->foto) }}" height="60">
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>

</html>

<!DOCTYPE html>
<html>

<head>
    <title>Data Anggota</title>
    <style>
        body {
            font-family: sans-serif;
            font-size: 12px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid #333;
            padding: 5px;
            text-align: center;
            vertical-align: middle;
        }

        img {
            max-height: 80px;
        }
    </style>
</head>

<body>
    <h2 align="center">Data Anggota</h2>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nama</th>
                <th>Tgl Lahir</th>
                <th>Tgl Registrasi</th>
                <th>Berlaku Hingga</th>
                <th>Jenis Kelamin</th>
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
                        @php
                            $fotoPath = public_path('storage/foto/' . $item->foto);
                        @endphp
                        @if ($item->foto && file_exists($fotoPath))
                            <img src="{{ $fotoPath }}">
                        @else
                            -
                        @endif
                    </td>
                    <td>
                        @php
                            $barcodePath = public_path('storage/barcode/' . $item->id_anggota . '.png');
                        @endphp
                        @if (file_exists($barcodePath))
                            <img src="{{ $barcodePath }}">
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

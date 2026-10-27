<!DOCTYPE html>
<html>

<head>
    <title>Print Laporan Sirkulasi</title>
</head>

<body onload="window.print()">

    <h3>Laporan Sirkulasi</h3>

    <table border="1" width="100%" cellspacing="0">
        <thead>
            <tr>
                @foreach ($headers as $h)
                    <th>{{ $h }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach ($rows as $r)
                <tr>
                    @foreach ($r as $cell)
                        <td>{{ $cell }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>

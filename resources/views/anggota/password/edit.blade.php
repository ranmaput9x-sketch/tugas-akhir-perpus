<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Ganti Password</title>
</head>

<body>
    <h1>Ganti Password</h1>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('anggota.password.update') }}">
        @csrf

        <div>
            <label>Password Lama</label>
            <input type="password" name="password_lama" required>
            @error('password_lama')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Password Baru</label>
            <input type="password" name="password_baru" required>
            @error('password_baru')
                <div style="color: red;">{{ $message }}</div>
            @enderror
        </div>

        <div>
            <label>Konfirmasi Password Baru</label>
            <input type="password" name="password_baru_confirmation" required>
        </div>

        <button type="submit">Ganti Password</button>
    </form>
</body>

</html>

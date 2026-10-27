<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <title>Login Anggota</title>
</head>

<body>
    <h1>Login Anggota (Guru/Siswa)</h1>
    <form method="POST" action="{{ route('anggota.login.submit') }}">
        @csrf
        <div>
            <label for="id_anggota">ID Anggota:</label>
            <input type="text" name="id_anggota" id="id_anggota" value="{{ old('id_anggota') }}" required autofocus>
            @error('id_anggota')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="password">Password:</label>
            <input type="password" name="password" id="password" required>
            @error('password')
                <div style="color:red">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Login</button>
    </form>
</body>

</html>

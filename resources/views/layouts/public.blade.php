<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Perpustakaan Online')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: #f6f8fa;
        }

        .library-title {
            font-weight: bold;
            letter-spacing: 2px;
        }
    </style>
    @stack('head')
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand library-title" href="{{ route('katalog.index') }}">Perpustakaan Online</a>
            {{-- <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="{{ route('katalog.anggota') }}" class="nav-link">Daftar Anggota</a></li>
                <li class="nav-item"><a href="{{ route('katalog.index') }}" class="nav-link">Katalog Buku</a></li>
            </ul> --}}
        </div>
    </nav>
    @yield('content')

    {{-- Bootstrap JS (opsional) --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
</body>

</html>

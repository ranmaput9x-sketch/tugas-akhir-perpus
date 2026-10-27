<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>@yield('title') | Perpustakaan</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Animate.css untuk animasi --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />

    {{-- Favicon (opsional) --}}
    {{-- <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon"> --}}
</head>

<body class="bg-gray-100 text-gray-800 min-h-screen">

    {{-- Navbar --}}
    <nav class="bg-white shadow px-6 py-4 mb-6">
        <div class="flex justify-between">
            <div class="font-bold text-blue-700 text-lg">📘 Perpustakaan</div>
            <div>
                <a href="{{ route('dashboard') }}" class="mr-4 hover:underline">Dashboard</a>
                <a href="{{ route('logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                    class="text-red-600 hover:underline">
                    Logout
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </nav>

    {{-- Konten Utama --}}
    <main class="px-6">
        @yield('content')
    </main>

</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Anggota - DigiLib</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="antialiased bg-slate-50">
    @include('partials.modals.navbar')

    <main class="py-12 md:py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <header class="mb-12 text-center">
                <h1 class="text-4xl font-extrabold text-slate-900 tracking-tight">Profil Anggota</h1>
                <p class="mt-2 text-lg text-slate-500">Semua detail keanggotaan dan aktivitasmu ada di sini.</p>
            </header>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

                {{-- LEFT --}}
                <div class="lg:col-span-1 space-y-8">
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 bg-gradient-to-tr from-indigo-600 to-purple-600 text-white">
                            <div class="flex items-center space-x-5">
                                <img class="h-24 w-24 rounded-full object-cover border-4 border-white/50"
                                    src="{{ $user->foto ? asset('storage/' . $user->foto) : 'https://placehold.co/200x200/EBF4FF/7F9CF5?text=' . substr($user->nama_anggota, 0, 1) }}"
                                    alt="Foto Profil {{ $user->nama_anggota }}">
                                <div>
                                    <h2 class="text-2xl font-bold leading-tight">{{ $user->nama_anggota }}</h2>
                                    <p class="text-sm opacity-90 font-medium">{{ $user->tingkat }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="p-6 space-y-5">
                            <div class="flex items-start text-sm">
                                <svg class="h-5 w-5 mr-4 text-slate-400 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 012-2h2a2 2 0 012 2v1m-6 0h6" />
                                </svg>
                                <div>
                                    <p class="text-slate-500">ID Anggota</p>
                                    <p class="font-semibold text-slate-800">{{ $user->id_anggota }}</p>
                                </div>
                            </div>
                            <div class="flex items-start text-sm">
                                <svg class="h-5 w-5 mr-4 text-slate-400 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-slate-500">Email</p>
                                    <p class="font-semibold text-slate-800">{{ $user->email }}</p>
                                </div>
                            </div>
                            <div class="border-t border-slate-200 !my-6"></div>

                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <p class="text-slate-500">Status Keanggotaan</p>
                                </div>
                                <span
                                    class="font-bold px-3 py-1 rounded-full capitalize {{ $user->status == 'aktif' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">
                                    {{ $user->status }}
                                </span>
                            </div>

                            <div class="flex justify-between items-center text-sm">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-3 text-slate-400" fill="none" viewBox="0 0 24 24"
                                        stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    <p class="text-slate-500">Masa Berlaku Kartu</p>
                                </div>
                                <p class="font-bold text-slate-800">
                                    @if ($user->berlaku_hingga)
                                        {{ \Carbon\Carbon::parse($user->berlaku_hingga)->isoFormat('D MMMM YYYY') }}
                                    @else
                                        -
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- RIGHT --}}
                <div class="lg:col-span-2 space-y-8">
                    {{-- Informasi Pendukung --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
                        <h3 class="text-xl font-bold text-slate-800 mb-6">Informasi Pendukung</h3>
                        <dl class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-6">
                            <div class="flex items-start text-sm">
                                <svg class="h-5 w-5 mr-4 text-slate-400 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                                </svg>
                                <div>
                                    <p class="text-slate-500">Nomor Telepon</p>
                                    <p class="font-semibold text-slate-900">{{ $user->no_telepon ?? '-' }}</p>
                                </div>
                            </div>
                            <div class="flex items-start text-sm">
                                <svg class="h-5 w-5 mr-4 text-slate-400 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                </svg>
                                <div>
                                    <p class="text-slate-500">Tanggal Bergabung</p>
                                    <p class="font-semibold text-slate-900">
                                        @if ($user->tanggal_registrasi)
                                            {{ \Carbon\Carbon::parse($user->tanggal_registrasi)->isoFormat('D MMMM YYYY') }}
                                        @else
                                            -
                                        @endif
                                    </p>
                                </div>
                            </div>
                            <div class="flex items-start text-sm md:col-span-2">
                                <svg class="h-5 w-5 mr-4 text-slate-400 mt-0.5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                </svg>
                                <div>
                                    <p class="text-slate-500">Alamat</p>
                                    <p class="font-semibold text-slate-900">{{ $user->alamat ?? '-' }}</p>
                                </div>
                            </div>
                        </dl>
                    </div>

                    {{-- Wishlist --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-rose-500 to-red-500 rounded-2xl flex items-center justify-center shadow-lg shadow-rose-500/20">
                                <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800">Wishlist Buku Saya</h3>
                                <p class="text-slate-500 mt-1">Daftar buku yang kamu tandai untuk dibaca nanti.</p>
                            </div>
                        </div>

                        <div class="space-y-4">
                            @forelse ($wishlistItems as $buku)
                                <a href="{{ route('katalog.buku.show', $buku) }}"
                                    class="flex items-center gap-4 p-3 rounded-xl hover:bg-slate-50 transition-colors group">
                                    <img src="{{ $buku->url_cover ? asset('storage/' . $buku->url_cover) : asset('storage/katalog_foto/cover-default.png') }}"
                                        alt="Cover {{ $buku->judul_buku }}"
                                        class="w-12 h-16 object-cover rounded-md shadow-sm transition-transform group-hover:scale-105">
                                    <div class="flex-1 min-w-0">
                                        <p
                                            class="font-bold text-slate-800 group-hover:text-indigo-600 transition-colors truncate">
                                            {{ $buku->judul_buku }}
                                        </p>
                                        <p class="text-sm text-slate-500 truncate">
                                            Oleh: {{ $buku->penulis->pluck('nama_penulis')->join(', ') }}
                                        </p>
                                    </div>
                                    <svg class="w-5 h-5 text-slate-400 transition-transform group-hover:translate-x-1"
                                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7" />
                                    </svg>
                                </a>
                            @empty
                                <div class="text-center py-8">
                                    <div
                                        class="mx-auto w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mb-5">
                                        <svg class="w-9 h-9 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M5 5a2 2 0 012-2h10a2 2 0 012 2v16l-7-3.5L5 21V5z"></path>
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Wishlist-mu Masih Kosong</h3>
                                    <p class="text-slate-500 text-sm">
                                        Tandai buku yang kamu suka dengan ikon hati ❤️ untuk menyimpannya di sini.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- =========================
                        RIWAYAT ULASAN (BARU)
                        ========================= --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-8">
                        <div class="flex items-center gap-4 mb-6">
                            <div
                                class="w-12 h-12 bg-gradient-to-br from-amber-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-lg shadow-amber-500/20">
                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M7 8h10M7 12h6m-6 4h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H7.414a2 2 0 01-1.414-.586L4.586 3.586A2 2 0 003.172 3H3a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800">Riwayat Ulasan Saya</h3>
                                <p class="text-slate-500 mt-1">Ulasan yang pernah kamu kirim (termasuk yang menunggu
                                    ACC).</p>
                            </div>
                        </div>

                        @php
                            // Kalau controller belum ngirim $ulasanHistory, ambil dari relasi kalau ada.
                            if (!isset($ulasanHistory)) {
                                $ulasanHistory = collect();

                                if (method_exists($user, 'komentarBuku')) {
                                    // Ambil secukupnya biar gak berat. Kalau mau semua, hapus take(20).
                                    $ulasanHistory = $user->komentarBuku()->with('buku')->latest()->take(20)->get();
                                }
                            }

                            $badgeStatus = function ($st) {
                                return match ($st) {
                                    'approved' => 'bg-emerald-100 text-emerald-800',
                                    'rejected' => 'bg-rose-100 text-rose-800',
                                    default => 'bg-amber-100 text-amber-800',
                                };
                            };

                            $badgeRec = function ($rec) {
                                return match ($rec) {
                                    'recommended' => 'bg-emerald-100 text-emerald-800',
                                    'neutral' => 'bg-slate-100 text-slate-800',
                                    'not' => 'bg-rose-100 text-rose-800',
                                    default => 'bg-slate-100 text-slate-800',
                                };
                            };

                            $labelRec = function ($rec) {
                                return match ($rec) {
                                    'recommended' => '✅ Recommended',
                                    'neutral' => '➖ Netral',
                                    'not' => '❌ Not recommended',
                                    default => '—',
                                };
                            };
                        @endphp

                        <div class="space-y-4">
                            @forelse($ulasanHistory as $u)
                                @php
                                    $st = method_exists($u, 'status') ? $u->status() : 'approved';
                                    $rec = method_exists($u, 'rec') ? $u->rec() : null;
                                    $aud = method_exists($u, 'aud') ? $u->aud() : null;
                                    $pin = method_exists($u, 'pinned') ? $u->pinned() : false;

                                    $body = method_exists($u, 'body') ? $u->body() : $u->isi_komentar ?? '';
                                    $judul = $u->buku->judul_buku ?? 'Buku ID: ' . $u->buku_id;
                                @endphp

                                <div class="p-4 rounded-2xl border border-slate-200 hover:bg-slate-50/50 transition">
                                    <div class="flex items-start justify-between gap-4">
                                        <div class="min-w-0">
                                            <a href="{{ isset($u->buku) ? route('katalog.buku.show', $u->buku) : route('katalog.buku.show', $u->buku_id) }}"
                                                class="font-bold text-slate-900 hover:text-indigo-700 transition block truncate">
                                                {{ $judul }}
                                            </a>
                                            <p class="text-xs text-slate-500 mt-1">
                                                {{ optional($u->created_at)->isoFormat('D MMM YYYY, HH:mm') }}
                                            </p>
                                        </div>

                                        <div class="flex flex-wrap gap-2 justify-end">
                                            <span
                                                class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeStatus($st) }}">
                                                {{ strtoupper($st) }}
                                            </span>

                                            @if ($rec)
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-semibold {{ $badgeRec($rec) }}">
                                                    {{ $labelRec($rec) }}
                                                </span>
                                            @endif

                                            @if ($aud)
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700">
                                                    {{ $aud }}
                                                </span>
                                            @endif

                                            @if ($pin)
                                                <span
                                                    class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                    📌 Pinned
                                                </span>
                                            @endif
                                        </div>
                                    </div>

                                    <p class="mt-3 text-sm text-slate-700 leading-relaxed">
                                        {{ \Illuminate\Support\Str::limit($body, 220) }}
                                    </p>
                                </div>
                            @empty
                                <div class="text-center py-10">
                                    <div
                                        class="mx-auto w-20 h-20 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mb-5">
                                        <svg class="w-9 h-9 text-slate-400" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                                d="M8 10h.01M12 10h.01M16 10h.01M9 16h6m4 5H7a2 2 0 01-2-2V5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2z" />
                                        </svg>
                                    </div>
                                    <h3 class="text-lg font-semibold text-slate-700 mb-2">Belum Ada Ulasan</h3>
                                    <p class="text-slate-500 text-sm">
                                        Kamu bisa menulis ulasan di halaman detail buku. Ulasan akan muncul di sini.
                                    </p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Aktivitas Peminjaman (dibikin selaras wrapper-nya) --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                        <div class="p-6 md:p-8 bg-slate-50 border-b border-slate-200">
                            <div class="flex items-center gap-4">
                                <div
                                    class="w-12 h-12 bg-gradient-to-br from-indigo-500 to-purple-500 rounded-2xl flex items-center justify-center shadow-lg shadow-indigo-500/20">
                                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                        </path>
                                    </svg>
                                </div>
                                <div>
                                    <h3 class="text-2xl font-bold text-slate-800">Aktivitas Peminjaman</h3>
                                    <p class="text-slate-500 mt-1">Riwayat transaksi peminjaman buku Anda.</p>
                                </div>
                            </div>
                        </div>

                        <div class="p-6 md:p-8">
                            <div class="space-y-6">
                                @forelse ($loanHistory as $loan)
                                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden">
                                        <div class="bg-slate-50 p-6 border-b border-slate-200">
                                            <div class="flex justify-between items-start flex-wrap gap-4">
                                                <div class="flex items-center gap-3">
                                                    <div
                                                        class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center">
                                                        <span
                                                            class="text-white font-bold text-sm">#{{ $loan->id }}</span>
                                                    </div>
                                                    <div>
                                                        <p class="font-bold text-slate-800 text-lg">Transaksi
                                                            Peminjaman</p>
                                                        <p class="text-slate-500 text-sm">ID: {{ $loan->id }}</p>
                                                    </div>
                                                </div>

                                                <span
                                                    class="font-bold px-4 py-2 rounded-full text-sm capitalize border
                                                    @if ($loan->status == 'Dipinjam') bg-amber-100 text-amber-800 border-amber-200 @endif
                                                    @if ($loan->status == 'Kembali') bg-emerald-100 text-emerald-800 border-emerald-200 @endif
                                                    @if ($loan->status == 'Telat') bg-rose-100 text-rose-800 border-rose-200 @endif">
                                                    <div class="flex items-center gap-2">
                                                        {{ $loan->status }}
                                                    </div>
                                                </span>
                                            </div>

                                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-3 gap-4">
                                                <div class="bg-white p-4 rounded-xl border border-slate-200">
                                                    <p class="text-xs text-slate-500 font-medium">Tanggal Pinjam</p>
                                                    <p class="font-semibold text-slate-700 mt-1">
                                                        {{ \Carbon\Carbon::parse($loan->tanggal_pinjam)->isoFormat('D MMM YYYY') }}
                                                    </p>
                                                </div>
                                                <div class="bg-white p-4 rounded-xl border border-slate-200">
                                                    <p class="text-xs text-slate-500 font-medium">Jatuh Tempo</p>
                                                    <p class="font-semibold text-slate-700 mt-1">
                                                        {{ \Carbon\Carbon::parse($loan->tanggal_kembali)->isoFormat('D MMM YYYY') }}
                                                    </p>
                                                </div>
                                                <div class="bg-white p-4 rounded-xl border border-slate-200">
                                                    <p class="text-xs text-slate-500 font-medium">Dikembalikan</p>
                                                    <p
                                                        class="font-semibold mt-1 {{ $loan->tanggal_pengembalian_aktual ? 'text-emerald-600' : 'text-slate-500' }}">
                                                        @if ($loan->tanggal_pengembalian_aktual)
                                                            {{ \Carbon\Carbon::parse($loan->tanggal_pengembalian_aktual)->isoFormat('D MMM YYYY') }}
                                                        @else
                                                            Belum dikembalikan
                                                        @endif
                                                    </p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="overflow-x-auto">
                                            <table class="min-w-full">
                                                <tbody class="divide-y divide-slate-100">
                                                    @foreach ($loan->items as $item)
                                                        <tr class="hover:bg-slate-50/60 transition-colors">
                                                            <td class="px-6 py-4">
                                                                <div class="flex items-center gap-4">
                                                                    <div
                                                                        class="w-10 h-12 bg-slate-100 rounded-lg flex items-center justify-center">
                                                                        <svg class="w-5 h-5 text-slate-600"
                                                                            fill="none" stroke="currentColor"
                                                                            viewBox="0 0 24 24">
                                                                            <path stroke-linecap="round"
                                                                                stroke-linejoin="round"
                                                                                stroke-width="2"
                                                                                d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.746 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253">
                                                                            </path>
                                                                        </svg>
                                                                    </div>
                                                                    <div>
                                                                        <p class="font-semibold text-slate-800">
                                                                            {{ $item->buku->judul_buku ?? 'Data buku tidak ditemukan' }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </td>
                                                            <td class="px-6 py-4">
                                                                <div
                                                                    class="bg-slate-100 px-3 py-2 rounded-lg inline-flex items-center gap-2">
                                                                    <span class="font-mono text-sm text-slate-600">
                                                                        {{ $item->kode_barcode ?? '-' }}
                                                                    </span>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                @empty
                                    <div class="text-center py-16">
                                        <div
                                            class="mx-auto w-24 h-24 bg-gradient-to-br from-slate-100 to-slate-200 rounded-3xl flex items-center justify-center mb-6">
                                            <svg class="w-10 h-10 text-slate-400" fill="none"
                                                stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    stroke-width="1.5"
                                                    d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                                                </path>
                                            </svg>
                                        </div>
                                        <h3 class="text-xl font-semibold text-slate-700 mb-3">Belum Ada Riwayat
                                            Peminjaman</h3>
                                        <p class="text-slate-500 max-w-md mx-auto mb-8">
                                            Mulai jelajahi koleksi perpustakaan dan pinjam buku favoritmu untuk melihat
                                            aktivitas di sini.
                                        </p>
                                        <a href="{{ route('katalog.index') }}"
                                            class="inline-flex items-center gap-2 px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-medium rounded-xl transition-all hover:scale-105 shadow-lg shadow-indigo-500/30">
                                            Jelajahi Katalog
                                        </a>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div class="p-6 md:p-8 border-t border-slate-200">
                            <a href="{{ route('profil.edit', $user->id) }}"
                                class="group w-full flex items-center justify-center gap-3 px-8 py-4 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-semibold rounded-2xl transition-all duration-300 hover:scale-[1.02] hover:shadow-xl hover:shadow-indigo-500/30">
                                <svg class="w-5 h-5 transition-transform group-hover:rotate-12" fill="none"
                                    stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                    </path>
                                </svg>
                                <span>Edit Profil Saya</span>
                            </a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-slate-800 text-white py-6 mt-16">
        <div class="max-w-7xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <p class="text-slate-400">&copy; {{ date('Y') }} Perpustakaan Smpn 01 Keling. Semua Hak Dilindungi.
            </p>
        </div>
    </footer>
</body>

</html>

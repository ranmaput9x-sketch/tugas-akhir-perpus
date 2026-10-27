<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang Perpustakaan - DigiLib</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <script>
        // Tailwind config (biar warnanya konsisten & modern)
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        poppins: ['Poppins', 'sans-serif']
                    },
                    colors: {
                        brand: {
                            50: '#eef2ff',
                            100: '#e0e7ff',
                            200: '#c7d2fe',
                            300: '#a5b4fc',
                            400: '#818cf8',
                            500: '#6366f1',
                            600: '#4f46e5',
                            700: '#4338ca',
                            800: '#3730a3',
                            900: '#312e81',
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        /* Smooth scroll untuk klik quick links */
        html {
            scroll-behavior: smooth;
        }

        /* Fokus yang jelas untuk aksesibilitas */
        :focus-visible {
            outline: 3px solid rgba(99, 102, 241, 0.45);
            outline-offset: 3px;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-800">
    <!-- Skip link (aksesibilitas) -->
    <a href="#konten"
        class="sr-only focus:not-sr-only focus:fixed focus:top-4 focus:left-4 focus:z-50 focus:rounded-xl focus:bg-white focus:px-4 focus:py-2 focus:shadow">
        Lewati ke konten
    </a>

    <!-- Navigation -->
    @include('partials.modals.navbar')

    @php
        // ====== DATA CEPAT (ubah di sini biar gampang) ======
        $schoolName = 'SMP Negeri 1 Keling';
        $libraryName = 'Perpustakaan DigiLib';

        $headline = 'Profil Perpustakaan Sekolah';
        $tagline = 'Pusat informasi, literasi, dan ruang belajar yang nyaman untuk siswa.';

        // Gunakan asset() kalau file ada di public/, atau ganti ke URL lain
        $logoUrl = 'https://example.com/logo.png';

        $stats = [
            ['label' => 'Jam Operasional', 'value' => 'Senin–Jumat 08:00–15:00'],
            ['label' => 'Layanan', 'value' => 'Pinjam • Baca • Internet'],
            ['label' => 'Lokasi', 'value' => 'Blok A, Lantai 1'],
        ];

        $quickLinks = [
            ['id' => 'ringkasan', 'label' => 'Ringkasan'],
            ['id' => 'visi-misi', 'label' => 'Visi & Misi'],
            ['id' => 'sejarah', 'label' => 'Sejarah'],
            ['id' => 'layanan', 'label' => 'Layanan'],
            ['id' => 'koleksi', 'label' => 'Koleksi'],
            ['id' => 'staf', 'label' => 'Staf'],
            ['id' => 'tata-tertib', 'label' => 'Tata Tertib'],
            ['id' => 'program', 'label' => 'Program'],
            ['id' => 'galeri', 'label' => 'Galeri'],
        ];

        $aboutParagraphs = [
            "{$libraryName} {$schoolName} hadir untuk mendukung kegiatan belajar, menguatkan budaya membaca, dan memudahkan akses informasi bagi warga sekolah.",
            'Kami menyediakan koleksi buku pelajaran, bacaan pengayaan, fiksi, non-fiksi, serta layanan baca di tempat dan akses komputer/internet untuk kebutuhan literasi digital.',
            'Target kami sederhana: siswa lebih mudah menemukan bahan belajar, guru terbantu mencari referensi, dan perpustakaan menjadi ruang belajar yang ramah serta tertib.',
        ];

        $vision = 'Terwujudnya perpustakaan sebagai pusat informasi ilmu pengetahuan.';
        $mission = 'Mewujudkan generasi yang berkualitas, cerdas dan terampil dengan membudayakan membaca.';

        $history =
            'Perpustakaan didirikan pada tahun 2000 dan terus berkembang hingga kini, memperluas koleksi serta layanan berbasis digital untuk mendukung pembelajaran.';

        $services = [
            [
                'title' => 'Peminjaman & Pengembalian',
                'desc' => 'Proses mudah untuk meminjam dan mengembalikan buku sesuai ketentuan.',
            ],
            ['title' => 'Baca di Tempat', 'desc' => 'Ruang baca nyaman untuk membaca koleksi perpustakaan di lokasi.'],
            [
                'title' => 'Komputer & Internet',
                'desc' => 'Akses perangkat dan internet untuk riset tugas sekolah dan literasi digital.',
            ],
            [
                'title' => 'Pendidikan Pemakai',
                'desc' => 'Orientasi anggota baru agar memahami cara mencari, meminjam, dan menjaga koleksi.',
            ],
        ];

        $featuredCollection =
            'Buku pelajaran, fiksi, non-fiksi, majalah, surat kabar, serta koleksi digital (e-book) sesuai ketersediaan.';

        $staff = [
            ['name' => 'Bapak Ahmad', 'role' => 'Kepala Perpustakaan', 'photo' => 'https://example.com/staf1.jpg'],
            ['name' => 'Ibu Siti', 'role' => 'Pustakawan', 'photo' => 'https://example.com/staf2.jpg'],
            ['name' => 'Bapak Joko', 'role' => 'Pustakawan', 'photo' => 'https://example.com/staf3.jpg'],
        ];

        $rules = [
            'Dilarang membawa makanan dan minuman ke ruang koleksi.',
            'Wajib menjaga ketenangan dan kerapian.',
            'Tas dititipkan di loker yang tersedia (jika ada).',
            'Durasi maksimal peminjaman buku adalah 7 hari (sesuaikan kebijakan).',
        ];

        $programs = [
            'Bedah buku & diskusi literasi.',
            'Lomba menulis / resensi buku.',
            'Pojok rekomendasi bacaan mingguan.',
        ];

        $gallery = [
            ['src' => 'https://example.com/foto1.jpg', 'alt' => 'Ruang baca perpustakaan'],
            ['src' => 'https://example.com/foto2.jpg', 'alt' => 'Rak koleksi buku'],
            ['src' => 'https://example.com/foto3.jpg', 'alt' => 'Area layanan sirkulasi'],
            ['src' => 'https://example.com/foto4.jpg', 'alt' => 'Kegiatan literasi di perpustakaan'],
        ];
    @endphp

    <!-- Hero -->
    <header class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-brand-600 via-brand-500 to-sky-500"></div>
        <div class="absolute inset-0 opacity-15"
            style="background-image: radial-gradient(circle at 20% 20%, white 1px, transparent 1px); background-size: 18px 18px;">
        </div>

        <div class="relative mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="py-12 sm:py-16">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div class="max-w-2xl">
                        <p
                            class="inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-sm text-white ring-1 ring-white/25">
                            <span class="h-2 w-2 rounded-full bg-white"></span>
                            Tentang Perpustakaan
                        </p>
                        <h1 class="mt-4 text-3xl font-bold tracking-tight text-white sm:text-4xl">
                            {{ $headline }}
                        </h1>
                        <p class="mt-3 text-base leading-relaxed text-white/90 sm:text-lg">
                            {{ $tagline }}
                        </p>

                        <div class="mt-6 flex flex-wrap gap-2">
                            @foreach ($stats as $s)
                                <span
                                    class="inline-flex items-center rounded-xl bg-white/10 px-3 py-2 text-sm text-white ring-1 ring-white/20">
                                    <span class="font-semibold">{{ $s['label'] }}:</span>
                                    <span class="ml-2 text-white/90">{{ $s['value'] }}</span>
                                </span>
                            @endforeach
                        </div>
                    </div>

                    <div class="flex items-center gap-4 rounded-2xl bg-white/10 p-4 ring-1 ring-white/20 backdrop-blur">
                        <div class="grid h-14 w-14 place-items-center rounded-2xl bg-white">
                            <img src="{{ $logoUrl }}" alt="Logo {{ $schoolName }}"
                                class="h-10 w-10 object-contain" loading="lazy">
                        </div>
                        <div>
                            <p class="text-sm text-white/80">{{ $schoolName }}</p>
                            <p class="text-base font-semibold text-white">{{ $libraryName }}</p>
                        </div>
                    </div>
                </div>

                <!-- CTA -->
                <div class="mt-8 flex flex-wrap gap-3">
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-white px-4 py-2.5 text-sm font-semibold text-brand-700 shadow-sm transition hover:bg-white/90">
                        Kembali ke Beranda
                    </a>
                    <a href="{{ url('/katalog') }}"
                        class="inline-flex items-center justify-center rounded-xl bg-slate-900/30 px-4 py-2.5 text-sm font-semibold text-white ring-1 ring-white/25 transition hover:bg-slate-900/40">
                        Lihat Katalog
                    </a>
                </div>
            </div>
        </div>
    </header>

    <main id="konten" class="mx-auto max-w-7xl px-4 py-12 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 lg:grid-cols-12">
            <!-- Content -->
            <div class="lg:col-span-8">
                <!-- Ringkasan -->
                <section id="ringkasan" class="scroll-mt-24">
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <h2 class="text-xl font-bold text-slate-900">Ringkasan</h2>
                        <div class="mt-4 space-y-4 text-slate-700">
                            @foreach ($aboutParagraphs as $p)
                                <p class="leading-relaxed">{{ $p }}</p>
                            @endforeach
                        </div>
                    </div>
                </section>

                <!-- Accordion Sections -->
                <div class="mt-8 space-y-4">
                    <!-- Visi & Misi -->
                    <section id="visi-misi" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Visi & Misi</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <!-- chevron -->
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>
                            <div class="mt-4 space-y-3 text-slate-700">
                                <p><span class="font-semibold text-slate-900">Visi:</span> {{ $vision }}</p>
                                <p><span class="font-semibold text-slate-900">Misi:</span> {{ $mission }}</p>
                            </div>
                        </details>
                    </section>

                    <!-- Sejarah -->
                    <section id="sejarah" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Sejarah Singkat</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>
                            <p class="mt-4 leading-relaxed text-slate-700">{{ $history }}</p>
                        </details>
                    </section>

                    <!-- Layanan -->
                    <section id="layanan" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Layanan yang Tersedia</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach ($services as $svc)
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <p class="font-semibold text-slate-900">{{ $svc['title'] }}</p>
                                        <p class="mt-1 text-sm leading-relaxed text-slate-700">{{ $svc['desc'] }}</p>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </section>

                    <!-- Koleksi -->
                    <section id="koleksi" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Koleksi Unggulan</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>
                            <p class="mt-4 leading-relaxed text-slate-700">{{ $featuredCollection }}</p>
                        </details>
                    </section>

                    <!-- Staf -->
                    <section id="staf" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Profil Staf</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>

                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2">
                                @foreach ($staff as $person)
                                    <div
                                        class="flex items-center gap-4 rounded-2xl border border-slate-200 bg-white p-4">
                                        <img src="{{ $person['photo'] }}" alt="Foto {{ $person['name'] }}"
                                            class="h-14 w-14 rounded-2xl object-cover" loading="lazy">
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $person['name'] }}</p>
                                            <p class="text-sm text-slate-600">{{ $person['role'] }}</p>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </section>

                    <!-- Tata Tertib -->
                    <section id="tata-tertib" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Tata Tertib & Peraturan</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>

                            <ul class="mt-4 space-y-2 text-slate-700">
                                @foreach ($rules as $r)
                                    <li class="flex gap-3">
                                        <span
                                            class="mt-1 inline-block h-2 w-2 flex-none rounded-full bg-brand-500"></span>
                                        <span class="leading-relaxed">{{ $r }}</span>
                                    </li>
                                @endforeach
                            </ul>
                        </details>
                    </section>

                    <!-- Program -->
                    <section id="program" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Program & Kegiatan</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>

                            <div class="mt-4 grid grid-cols-1 gap-3 sm:grid-cols-2">
                                @foreach ($programs as $p)
                                    <div
                                        class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-sm text-slate-700">
                                        {{ $p }}
                                    </div>
                                @endforeach
                            </div>
                        </details>
                    </section>

                    <!-- Galeri -->
                    <section id="galeri" class="scroll-mt-24">
                        <details class="group rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200" open>
                            <summary class="flex cursor-pointer list-none items-center justify-between gap-4">
                                <h3 class="text-lg font-semibold text-slate-900">Galeri Foto</h3>
                                <span
                                    class="grid h-9 w-9 place-items-center rounded-xl bg-slate-100 text-slate-700 transition group-open:rotate-180">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-5 w-5">
                                        <path fill-rule="evenodd"
                                            d="M5.23 7.21a.75.75 0 011.06.02L10 10.94l3.71-3.71a.75.75 0 111.06 1.06l-4.24 4.24a.75.75 0 01-1.06 0L5.21 8.29a.75.75 0 01.02-1.08z"
                                            clip-rule="evenodd" />
                                    </svg>
                                </span>
                            </summary>

                            <div class="mt-4 grid grid-cols-2 gap-4 sm:grid-cols-3">
                                @foreach ($gallery as $g)
                                    <figure
                                        class="group overflow-hidden rounded-2xl border border-slate-200 bg-slate-100">
                                        <img src="{{ $g['src'] }}" alt="{{ $g['alt'] }}"
                                            class="h-32 w-full object-cover transition duration-300 group-hover:scale-105"
                                            loading="lazy">
                                    </figure>
                                @endforeach
                            </div>
                        </details>
                    </section>
                </div>
            </div>

            <!-- Sidebar -->
            <aside class="lg:col-span-4">
                <div class="sticky top-6 space-y-4">
                    <!-- Quick Links -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <h2 class="text-sm font-semibold tracking-wide text-slate-900">Navigasi Cepat</h2>
                        <nav class="mt-4 grid grid-cols-2 gap-2 sm:grid-cols-3 lg:grid-cols-2">
                            @foreach ($quickLinks as $q)
                                <a href="#{{ $q['id'] }}"
                                    class="rounded-xl bg-slate-50 px-3 py-2 text-sm text-slate-700 ring-1 ring-slate-200 transition hover:bg-slate-100">
                                    {{ $q['label'] }}
                                </a>
                            @endforeach
                        </nav>
                    </div>

                    <!-- Info Card -->
                    <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                        <h2 class="text-base font-semibold text-slate-900">Info Singkat</h2>
                        <div class="mt-4 space-y-3 text-sm text-slate-700">
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Sekolah</span>
                                <span class="text-right font-medium text-slate-900">{{ $schoolName }}</span>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Lokasi</span>
                                <span class="text-right font-medium text-slate-900">Blok A, Lantai 1</span>
                            </div>
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-slate-500">Jam</span>
                                <span class="text-right font-medium text-slate-900">Senin–Jumat 08:00–15:00<br>Sabtu
                                    08:00–12:00</span>
                            </div>
                        </div>

                        <div class="mt-5 flex flex-col gap-2">
                            <a href="{{ route('anggota.login.form') }}"
                                class="inline-flex items-center justify-center rounded-xl bg-brand-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-brand-700">
                                Masuk / Login
                            </a>
                        </div>
                    </div>
                </div>
            </aside>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
            <div class="flex flex-col items-center justify-between gap-3 sm:flex-row">
                <p class="text-sm text-slate-600">&copy; {{ date('Y') }} {{ $libraryName }}. Semua hak
                    dilindungi.</p>
                <p class="text-sm text-slate-500">Dibuat untuk mendukung budaya literasi di sekolah 📚</p>
            </div>
        </div>
    </footer>
</body>

</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DigiLib - Perpustakaan Digital Modern</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        /* Optional: sembunyikan scrollbar untuk slider (tetap bisa di-scroll) */
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }

        @keyframes float {

            0%,
            100% {
                transform: translateY(0);
            }

            50% {
                transform: translateY(-10px);
            }
        }

        .floating {
            animation: float 4s ease-in-out infinite;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    @include('partials.modals.navbar')

    <!-- HERO (Premium) -->
    <section class="relative overflow-hidden py-20">
        <!-- background base -->
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-indigo-50 via-white to-emerald-50"></div>
        <!-- glows -->
        <div class="absolute -top-24 -right-24 h-80 w-80 rounded-full bg-indigo-200/40 blur-3xl -z-10"></div>
        <div class="absolute -bottom-24 -left-24 h-80 w-80 rounded-full bg-emerald-200/40 blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div>
                    <p
                        class="inline-flex items-center gap-2 text-sm font-medium text-indigo-700 bg-indigo-100/70 px-3 py-1 rounded-full">
                        <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                        Perpustakaan Digital Modern
                    </p>

                    <h1 class="mt-5 text-4xl md:text-5xl font-bold text-slate-900 leading-tight">
                        Temukan Dunia Pengetahuan<br>
                        <span class="text-indigo-600">Di Ujung Jari</span> Anda
                    </h1>

                    <p class="mt-4 text-lg text-slate-600 max-w-xl">
                        Akses ribuan buku digital berkualitas dari berbagai genre dan kategori.
                        Baca kapan saja, di mana saja tanpa batas.
                    </p>

                    <div class="mt-8 flex flex-col sm:flex-row gap-3">
                        <a href="{{ route('katalog.daftar-buku') }}"
                            class="inline-flex justify-center items-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                            Jelajahi Katalog
                        </a>
                        <a href="#populer"
                            class="inline-flex justify-center items-center px-5 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 font-semibold hover:bg-slate-50 transition">
                            Lihat Buku Populer
                        </a>
                    </div>

                    <div class="mt-8">
                        <form action="{{ route('katalog.daftar-buku') }}" method="GET" class="relative max-w-xl">
                            <input type="text" name="search" value="{{ request('search') }}"
                                class="w-full px-5 py-4 pr-12 rounded-2xl bg-white border border-slate-200 focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400 shadow-sm"
                                placeholder="Cari judul buku, penulis, atau kategori..."
                                aria-label="Kata kunci pencarian">
                            <button type="submit"
                                class="absolute right-3 top-1/2 -translate-y-1/2 bg-indigo-600 text-white p-2.5 rounded-xl hover:bg-indigo-700 focus:outline-none transition"
                                aria-label="Cari">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                        clip-rule="evenodd" />
                                </svg>
                            </button>
                        </form>

                        <!-- mini stats -->
                        <div class="mt-6 grid grid-cols-3 gap-3 max-w-xl">
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4">
                                <p class="text-sm text-slate-500">Ebook</p>
                                <p class="text-xl font-bold text-slate-900">15.000+</p>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4">
                                <p class="text-sm text-slate-500">Pembaca</p>
                                <p class="text-xl font-bold text-slate-900">50.000+</p>
                            </div>
                            <div class="rounded-2xl bg-white/70 border border-slate-200 p-4">
                                <p class="text-sm text-slate-500">Akses</p>
                                <p class="text-xl font-bold text-slate-900">24/7</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="relative">
                    <div
                        class="absolute inset-0 rounded-3xl bg-gradient-to-tr from-indigo-600/10 to-emerald-600/10 blur-2xl">
                    </div>
                    <img src="{{ asset('storage/katalog_foto/gambar.png') }}" alt="Cover Buku Keren"
                        class="relative w-full max-w-md mx-auto rounded-3xl shadow-xl border border-white/60 floating">
                </div>
            </div>
        </div>
    </section>

    <!-- KATEGORI -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-slate-900">Jelajahi Kategori</h2>
                <p class="mt-2 text-lg text-slate-600">Temukan buku favorit Anda melalui berbagai kategori</p>
            </div>

            <div class="mt-10 grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-5">
                @foreach ($kategori as $kat)
                    <a href="{{ route('katalog.daftar-buku', ['kategori' => $kat->id]) }}"
                        class="group flex flex-col items-center justify-center text-center p-4 rounded-2xl border border-slate-200 bg-white shadow-sm transition hover:-translate-y-1 hover:shadow-xl">
                        <div class="mb-2">
                            {!! $kat->icon !!}
                        </div>
                        <span class="font-semibold text-sm text-slate-800 group-hover:text-indigo-700">
                            {{ $kat->nama_kategori }}
                        </span>
                    </a>
                @endforeach
            </div>
        </div>
    </section>

    <!-- POPULAR BOOKS -->
    <section id="populer" class="py-16 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Buku Populer</h2>
                    <p class="mt-2 text-lg text-slate-600">Buku yang sedang banyak dibaca oleh anggota kami</p>
                </div>
                <a href="{{ route('katalog.daftar-buku') }}"
                    class="text-indigo-600 hover:text-indigo-800 font-semibold">
                    Lihat Semua
                </a>
            </div>

            <div class="mt-10 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-8">
                @forelse ($bukuPopuler as $populer)
                    <div
                        class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition will-change-transform flex flex-col">
                        <a href="{{ route('katalog.buku.show', $populer) }}" class="block">
                            <div class="relative">
                                @if ($populer->url_cover && Storage::disk('public')->exists($populer->url_cover))
                                    <img src="{{ asset('storage/' . $populer->url_cover) }}"
                                        alt="Cover buku {{ $populer->judul_buku }}" class="w-full h-64 object-cover">
                                @else
                                    <img src="{{ asset('storage/katalog_foto/cover-default.png') }}"
                                        alt="Cover default untuk buku {{ $populer->judul_buku }}"
                                        class="w-full h-64 object-cover">
                                @endif

                                <!-- overlay biar cover terasa lebih “dalam” -->
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-slate-900/25 via-transparent to-transparent">
                                </div>

                                <span
                                    class="absolute top-3 right-3 bg-rose-100 text-rose-800 text-xs font-semibold px-3 py-1 rounded-full">
                                    Populer
                                </span>
                            </div>
                        </a>

                        <div class="p-5 flex flex-col flex-grow">
                            <a href="{{ route('katalog.buku.show', $populer) }}" class="block">
                                <h3 class="text-lg font-bold text-slate-900 mb-1 truncate"
                                    title="{{ $populer->judul_buku }}">
                                    {{ $populer->judul_buku }}
                                </h3>
                            </a>

                            @if (!$populer->penulis->isEmpty())
                                <p class="text-slate-600 mb-2">{{ $populer->penulis->first()->nama_penulis }}</p>
                            @else
                                <p class="text-slate-600 mb-2">-</p>
                            @endif

                            <div class="flex items-center mt-auto">
                                <div class="flex items-center text-sm text-slate-500">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 mr-1.5 text-indigo-500"
                                        viewBox="0 0 20 20" fill="currentColor">
                                        <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z" />
                                        <path fill-rule="evenodd"
                                            d="M4 5a2 2 0 012-2h8a2 2 0 012 2v10a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h4a1 1 0 100-2H7zm0 4a1 1 0 100 2h4a1 1 0 100-2H7z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span>{{ $populer->total_pinjam }} kali dipinjam</span>
                                </div>
                            </div>

                            <a href="{{ route('katalog.buku.show', $populer) }}"
                                class="w-full mt-3 inline-flex justify-center items-center px-4 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <p class="text-slate-500">Belum ada data buku populer untuk ditampilkan.</p>
                    </div>
                @endforelse
            </div>
        </div>
    </section>

    <!-- KOLEKSI TERBARU (Scroll-snap, no reload) -->
    <section class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Koleksi Terbaru</h2>
                    <p class="mt-2 text-lg text-slate-600">Buku-buku terbaru yang baru saja kami tambahkan</p>
                </div>

                <div class="flex items-center space-x-3">
                    <button id="prev-arrivals"
                        class="rounded-full p-2 border border-slate-200 hover:bg-slate-50 focus:outline-none transition"
                        aria-label="Sebelumnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <button id="next-arrivals"
                        class="rounded-full p-2 border border-slate-200 hover:bg-slate-50 focus:outline-none transition"
                        aria-label="Berikutnya">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                            fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>
            </div>

            <div class="mt-10">
                <div id="arrivals-slider" class="overflow-x-auto scroll-smooth no-scrollbar">
                    <div id="arrivals-container" class="flex gap-6 snap-x snap-mandatory px-1">
                        @forelse ($bukuTerbaru as $terbaru)
                            <div class="w-72 sm:w-80 flex-shrink-0 snap-start">
                                <div
                                    class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition will-change-transform flex flex-col h-full">
                                    <a href="{{ route('katalog.buku.show', $terbaru) }}" class="block">
                                        <div class="relative">
                                            @if ($terbaru->url_cover && Storage::disk('public')->exists($terbaru->url_cover))
                                                <img src="{{ asset('storage/' . $terbaru->url_cover) }}"
                                                    alt="Cover buku {{ $terbaru->judul_buku }}"
                                                    class="w-full h-64 object-cover">
                                            @else
                                                <img src="{{ asset('storage/katalog_foto/cover-default.png') }}"
                                                    alt="Cover default" class="w-full h-64 object-cover">
                                            @endif

                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-slate-900/25 via-transparent to-transparent">
                                            </div>

                                            <span
                                                class="absolute top-3 right-3 bg-sky-100 text-sky-800 text-xs font-semibold px-3 py-1 rounded-full">
                                                Terbaru
                                            </span>
                                        </div>
                                    </a>

                                    <div class="p-5 flex flex-col flex-grow">
                                        <a href="{{ route('katalog.buku.show', $terbaru) }}" class="block">
                                            <h3 class="text-lg font-bold text-slate-900 mb-1 truncate"
                                                title="{{ $terbaru->judul_buku }}">
                                                {{ $terbaru->judul_buku }}
                                            </h3>
                                        </a>

                                        <p class="text-slate-600 text-sm mb-2">
                                            @if (!$terbaru->penulis->isEmpty())
                                                {{ $terbaru->penulis->first()->nama_penulis }}
                                            @else
                                                Penulis tidak diketahui
                                            @endif
                                        </p>

                                        <div class="mt-auto">
                                            <a href="{{ route('katalog.buku.show', $terbaru) }}"
                                                class="w-full mt-3 inline-flex justify-center items-center px-4 py-2.5 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                                Lihat Detail
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="w-full text-center py-12">
                                <p class="text-slate-500">Belum ada koleksi terbaru untuk ditampilkan.</p>
                            </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </section>

    <footer class="bg-slate-800 text-white py-6 mt-16">
        <div class="max-w-7xl mx-auto text-center px-4 sm:px-6 lg:px-8">
            <p class="text-slate-400">&copy; {{ date('Y') }} Perpustakaan Smpn 01 Keling. Semua Hak Dilindungi.
            </p>
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const slider = document.getElementById('arrivals-slider');
            const prevBtn = document.getElementById('prev-arrivals');
            const nextBtn = document.getElementById('next-arrivals');

            if (!slider || !prevBtn || !nextBtn) return;

            // kira-kira lebar card + gap (w-72/80 + gap-6)
            const step = 360;

            nextBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: step,
                    behavior: 'smooth'
                });
            });

            prevBtn.addEventListener('click', () => {
                slider.scrollBy({
                    left: -step,
                    behavior: 'smooth'
                });
            });
        });
    </script>
</body>

</html>

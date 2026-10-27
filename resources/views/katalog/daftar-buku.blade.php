<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Buku - Perpustakaan</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        .no-scrollbar::-webkit-scrollbar {
            display: none;
        }

        .no-scrollbar {
            -ms-overflow-style: none;
            scrollbar-width: none;
        }
    </style>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }

        .shadow-soft {
            box-shadow: 0 10px 20px rgba(15, 23, 42, .06);
        }
    </style>

</head>

<body class="bg-slate-50">
    @include('partials.modals.navbar')

    {{-- HEADER --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-indigo-50 via-white to-emerald-50"></div>
        <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl -z-10"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-12 pb-8">
            <div class="text-center">
                <p
                    class="inline-flex items-center gap-2 text-sm font-medium text-indigo-700 bg-indigo-100/70 px-3 py-1 rounded-full">
                    <span class="h-2 w-2 rounded-full bg-indigo-600"></span>
                    Pencarian Koleksi
                </p>
                <h1 class="mt-4 text-3xl md:text-4xl font-bold text-slate-900">Temukan Buku yang Kamu Butuhkan</h1>
                <p class="mt-2 text-slate-600">Gunakan pencarian & filter untuk mempersempit hasil secara cepat.</p>
            </div>
        </div>
    </section>

    {{-- SINGLE FORM (jangan nested) --}}
    <form action="{{ route('katalog.daftar-buku') }}" method="GET" id="filter-form">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-12">
            {{-- SEARCH BAR --}}
            <div class="max-w-2xl mx-auto">
                <div class="relative">
                    <input type="text" name="search" value="{{ $searchInputValue ?? request('search') }}"
                        class="w-full px-5 py-4 pr-12 rounded-2xl bg-white border border-slate-200 shadow-sm
                               focus:outline-none focus:ring-4 focus:ring-indigo-200 focus:border-indigo-400"
                        placeholder="Cari judul buku, penulis, atau kategori..." aria-label="Kata kunci pencarian">
                    <button type="submit"
                        class="absolute right-3 top-1/2 -translate-y-1/2 bg-indigo-600 text-white p-2.5 rounded-xl
                               hover:bg-indigo-700 focus:outline-none transition"
                        aria-label="Cari">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>
                </div>

                {{-- ACTIVE FILTER CHIPS --}}
                @php
                    $hasFilters = request()->hasAny([
                        'search',
                        'tahun_terbit',
                        'ketersediaan',
                        'koleksi',
                        'kategori',
                        'lokasi',
                        'sort',
                    ]);
                    $chipBase = 'inline-flex items-center gap-2 px-3 py-1 rounded-full text-xs font-semibold border';
                @endphp

                @if ($hasFilters)
                    <div class="mt-4 flex flex-wrap items-center justify-center gap-2">
                        @if (request('search'))
                            <span class="{{ $chipBase }} bg-white border-slate-200 text-slate-700">
                                Search: <span class="font-bold">{{ request('search') }}</span>
                            </span>
                        @endif

                        @if (request('tahun_terbit'))
                            <span class="{{ $chipBase }} bg-white border-slate-200 text-slate-700">
                                Tahun: <span class="font-bold">{{ request('tahun_terbit') }}</span>
                            </span>
                        @endif

                        @if (request('ketersediaan') === 'tersedia')
                            <span class="{{ $chipBase }} bg-emerald-50 border-emerald-200 text-emerald-800">
                                Tersedia di Rak
                            </span>
                        @endif

                        @if (request('koleksi'))
                            <span class="{{ $chipBase }} bg-white border-slate-200 text-slate-700">
                                Tipe Koleksi
                            </span>
                        @endif

                        @if (request('kategori'))
                            <span class="{{ $chipBase }} bg-white border-slate-200 text-slate-700">
                                Kategori Subjek
                            </span>
                        @endif

                        @if (request('lokasi'))
                            <span class="{{ $chipBase }} bg-white border-slate-200 text-slate-700">
                                Lokasi
                            </span>
                        @endif

                        <a href="{{ route('katalog.daftar-buku') }}"
                            class="inline-flex items-center justify-center px-3 py-1 rounded-full text-xs font-semibold
                                  bg-rose-50 text-rose-800 border border-rose-200 hover:bg-rose-100 transition">
                            Reset Filter
                        </a>
                    </div>
                @endif
            </div>

            {{-- CONTENT GRID --}}
            <div class="mt-10 flex flex-col md:flex-row gap-8">
                {{-- FILTERS --}}
                <aside class="w-full md:w-1/4 lg:w-1/5">
                    {{-- mobile toggle --}}
                    <button type="button" id="toggleFilters"
                        class="md:hidden w-full mb-3 inline-flex items-center justify-between px-4 py-3 rounded-2xl bg-white border border-slate-200 shadow-sm">
                        <span class="text-sm font-bold text-slate-900">Filter</span>
                        <svg id="filterIcon" xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 text-slate-600 transition" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                clip-rule="evenodd" />
                        </svg>
                    </button>

                    <div id="filtersPanel" class="md:block hidden md:sticky md:top-24">
                        <div class="p-5 bg-white rounded-2xl border border-slate-200 shadow-sm">
                            <h2 class="text-lg font-bold text-slate-900 pb-3 border-b border-slate-100">Ditapis dengan
                            </h2>

                            <div class="mt-5 space-y-5">
                                {{-- Tahun Terbit --}}
                                <div>
                                    <label for="tahun_terbit"
                                        class="block text-sm font-semibold text-slate-700 mb-2">Tahun Penerbitan</label>
                                    <select id="tahun_terbit" name="tahun_terbit"
                                        class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-400 focus:ring-4 focus:ring-indigo-200 text-sm">
                                        <option value="">Semua Tahun</option>
                                        @foreach ($allTahun as $tahun)
                                            <option value="{{ $tahun }}"
                                                {{ request('tahun_terbit') == $tahun ? 'selected' : '' }}>
                                                {{ $tahun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Ketersediaan --}}
                                <div>
                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ketersediaan</label>
                                    <div class="flex items-center">
                                        <input id="tersedia" name="ketersediaan" type="checkbox" value="tersedia"
                                            {{ request('ketersediaan') == 'tersedia' ? 'checked' : '' }}
                                            class="h-4 w-4 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <label for="tersedia" class="ml-2 block text-sm text-slate-700">Tersedia di
                                            Rak</label>
                                    </div>
                                </div>

                                {{-- Tipe Koleksi --}}
                                <div>
                                    <label for="koleksi" class="block text-sm font-semibold text-slate-700 mb-2">Tipe
                                        Koleksi</label>
                                    <select id="koleksi" name="koleksi"
                                        class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-400 focus:ring-4 focus:ring-indigo-200 text-sm">
                                        <option value="">Semua Tipe</option>
                                        @foreach ($allKoleksi as $k)
                                            <option value="{{ $k->id }}"
                                                {{ request('koleksi') == $k->id ? 'selected' : '' }}>
                                                {{ $k->nama_koleksi }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Kategori --}}
                                <div>
                                    <label for="kategori"
                                        class="block text-sm font-semibold text-slate-700 mb-2">Kategori Subjek</label>
                                    <select id="kategori" name="kategori"
                                        class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-400 focus:ring-4 focus:ring-indigo-200 text-sm">
                                        <option value="">Semua Subjek</option>
                                        @foreach ($categories as $category)
                                            <option value="{{ $category->id }}"
                                                {{ request('kategori') == $category->id ? 'selected' : '' }}>
                                                {{ $category->nama_kategori }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Lokasi --}}
                                <div>
                                    <label for="lokasi"
                                        class="block text-sm font-semibold text-slate-700 mb-2">Lokasi</label>
                                    <select id="lokasi" name="lokasi"
                                        class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-400 focus:ring-4 focus:ring-indigo-200 text-sm">
                                        <option value="">Semua Lokasi</option>
                                        @foreach ($allLokasi as $l)
                                            <option value="{{ $l->id }}"
                                                {{ request('lokasi') == $l->id ? 'selected' : '' }}>
                                                {{ $l->lokasi }} ({{ $l->kode_rak }})
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                {{-- Action --}}
                                <div class="pt-4 border-t border-slate-100 space-y-2">
                                    <button type="submit"
                                        class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl bg-indigo-600 text-white text-sm font-semibold hover:bg-indigo-700 transition">
                                        Terapkan Filter
                                    </button>

                                    @if (request()->hasAny(['search', 'tahun_terbit', 'ketersediaan', 'koleksi', 'kategori', 'lokasi', 'sort']))
                                        <a href="{{ route('katalog.daftar-buku') }}"
                                            class="w-full inline-flex justify-center items-center px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50 transition">
                                            Reset Filter
                                        </a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </aside>

                {{-- RESULTS --}}
                <main class="w-full md:w-3/4 lg:w-4/5">
                    {{-- Result bar --}}
                    <div
                        class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-5 p-5 bg-white rounded-2xl border border-slate-200 shadow-sm">
                        <p class="text-sm text-slate-700">
                            Ditemukan <span class="font-bold text-slate-900">{{ $books->total() }}</span> hasil.
                        </p>

                        <div class="flex items-center gap-2 w-full sm:w-auto">
                            <label for="sort"
                                class="text-sm font-semibold text-slate-700 whitespace-nowrap">Urutkan</label>
                            <select id="sort" name="sort" onchange="this.form.submit()"
                                class="w-full sm:w-64 rounded-xl border-slate-200 shadow-sm focus:border-indigo-400 focus:ring-4 focus:ring-indigo-200 text-sm">
                                <option value="relevan" {{ $currentSort == 'relevan' ? 'selected' : '' }}>Paling
                                    Relevan</option>
                                <option value="terbaru" {{ $currentSort == 'terbaru' ? 'selected' : '' }}>Baru
                                    Ditambahkan</option>
                                <option value="perubahan_terakhir"
                                    {{ $currentSort == 'perubahan_terakhir' ? 'selected' : '' }}>Perubahan Terakhir
                                </option>
                                <option value="sering_dipinjam"
                                    {{ $currentSort == 'sering_dipinjam' ? 'selected' : '' }}>Sering Dipinjam</option>
                                <option value="tahun_terbaru" {{ $currentSort == 'tahun_terbaru' ? 'selected' : '' }}>
                                    Tahun Terbit (Terbaru)</option>
                                <option value="tahun_terlama" {{ $currentSort == 'tahun_terlama' ? 'selected' : '' }}>
                                    Tahun Terbit (Terlama)</option>
                                <option value="judul_asc" {{ $currentSort == 'judul_asc' ? 'selected' : '' }}>Judul
                                    (A–Z)</option>
                                <option value="judul_desc" {{ $currentSort == 'judul_desc' ? 'selected' : '' }}>Judul
                                    (Z–A)</option>
                            </select>
                        </div>
                    </div>

                    @if ($books->count())
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                            @foreach ($books as $book)
                                <div
                                    class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm hover:shadow-xl hover:-translate-y-1 transition will-change-transform group">
                                    <a href="{{ route('katalog.buku.show', $book->id) }}" class="block">
                                        <div class="relative aspect-[3/4] bg-gray-200 overflow-hidden">
                                            @if ($book->url_cover)
                                                <img class="object-cover w-full h-full"
                                                    src="{{ asset('storage/' . $book->url_cover) }}"
                                                    alt="Cover Buku">
                                            @else
                                                <img class="object-cover w-full h-full"
                                                    src="{{ asset('storage/katalog_foto/cover-default.png') }}"
                                                    alt="Cover default">
                                            @endif

                                            <div
                                                class="absolute inset-0 bg-gradient-to-t from-slate-900/35 via-transparent to-transparent">
                                            </div>

                                            {{-- BADGES TOP --}}
                                            <div class="absolute top-3 left-3 flex flex-wrap gap-2">
                                                @php
                                                    $isEbook = !empty($book->file_ebook) || !empty($book->format_ebook);
                                                @endphp

                                                {{-- Jenis --}}
                                                @if ($isEbook)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                        Ebook
                                                    </span>
                                                @else
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                                                        Fisik
                                                    </span>

                                                    {{-- Ketersediaan (HANYA untuk Fisik) --}}
                                                    @if (isset($book->tersedia_item))
                                                        @if ($book->tersedia_item > 0)
                                                            <span
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                                Tersedia ({{ $book->tersedia_item }})
                                                            </span>
                                                        @else
                                                            <span
                                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                                                Dipinjam
                                                            </span>
                                                        @endif
                                                    @endif
                                                @endif

                                                {{-- Tahun terbit --}}
                                                @if (!empty($book->tahun_terbit))
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                                                        {{ $book->tahun_terbit }}
                                                    </span>
                                                @endif
                                            </div>
                                        </div>


                                        <div class="p-4">
                                            <h3 class="font-bold text-slate-900 truncate group-hover:text-indigo-700 transition"
                                                title="{{ $book->judul_buku }}">
                                                {{ $book->judul_buku }}
                                            </h3>

                                            {{-- Penulis --}}
                                            @if ($book->penulis->isNotEmpty())
                                                <p class="text-sm text-slate-600 mt-1 truncate">
                                                    {{ $book->penulis->pluck('nama_penulis')->join(', ') }}
                                                </p>
                                            @else
                                                <p class="text-sm text-slate-500 mt-1">Penulis tidak diketahui</p>
                                            @endif

                                            {{-- Kategori + Koleksi --}}
                                            <div class="mt-3 flex flex-wrap gap-2">
                                                @if ($book->kategori->isNotEmpty())
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700">
                                                        {{ $book->kategori->first()->nama_kategori }}
                                                    </span>
                                                @endif

                                                @if ($book->koleksi)
                                                    <span
                                                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700">
                                                        {{ $book->koleksi->nama_koleksi }}
                                                    </span>
                                                @endif
                                            </div>

                                            {{-- Eksemplar (nice touch, bukan gimmick) --}}
                                            @if (isset($book->total_item) && isset($book->tersedia_item))
                                                <p class="mt-3 text-xs text-slate-500">
                                                    Eksemplar: <span
                                                        class="font-semibold text-slate-700">{{ $book->tersedia_item }}</span>
                                                    tersedia dari
                                                    <span
                                                        class="font-semibold text-slate-700">{{ $book->total_item }}</span>
                                                </p>
                                            @endif
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-8">
                            {{ $books->links() }}
                        </div>
                    @else
                        <div class="text-center py-16 px-6 bg-white rounded-2xl border border-slate-200 shadow-sm">
                            <h3 class="text-xl font-bold text-slate-900">Data kosong</h3>
                            <p class="mt-2 text-slate-600">Coba ubah kata kunci atau reset filter.</p>
                            <div class="mt-6">
                                <a href="{{ route('katalog.daftar-buku') }}"
                                    class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                    Reset & Lihat Semua Buku
                                </a>
                            </div>
                        </div>
                    @endif
                </main>
            </div>
        </div>
    </form>

    <script>
        // Mobile filter toggle
        (function() {
            const btn = document.getElementById('toggleFilters');
            const panel = document.getElementById('filtersPanel');
            const icon = document.getElementById('filterIcon');
            if (!btn || !panel) return;

            btn.addEventListener('click', () => {
                panel.classList.toggle('hidden');
                if (icon) icon.classList.toggle('rotate-180');
            });
        })();
    </script>
</body>

</html>

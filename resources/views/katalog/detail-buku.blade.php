<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail: {{ $buku->judul_buku }} - DigiLib</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>

<body class="bg-slate-50">
    @include('partials.modals.navbar')

    {{-- HERO / HEADER --}}
    <section class="relative overflow-hidden">
        <div class="absolute inset-0 -z-10 bg-gradient-to-br from-indigo-50 via-white to-emerald-50"></div>
        <div class="absolute -top-20 -right-20 h-72 w-72 rounded-full bg-indigo-200/40 blur-3xl -z-10"></div>
        <div class="absolute -bottom-24 -left-24 h-72 w-72 rounded-full bg-emerald-200/40 blur-3xl -z-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pt-10 pb-6">
            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                <div>
                    <a href="{{ route('katalog.daftar-buku') }}"
                        class="inline-flex items-center gap-2 text-sm font-semibold text-slate-600 hover:text-indigo-700 transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                        Kembali ke Daftar Buku
                    </a>

                    <h1 class="mt-3 text-3xl md:text-4xl font-bold text-slate-900 leading-tight">
                        {{ $buku->judul_buku }}
                    </h1>

                    @if ($buku->sub_judul)
                        <p class="mt-2 text-slate-600">{{ $buku->sub_judul }}</p>
                    @endif
                </div>

                {{-- Quick badges --}}
                <div class="flex flex-wrap gap-2">
                    @if (!empty($buku->file_ebook) || !empty($buku->format_ebook))
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                            Ebook
                        </span>
                    @else
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">
                            Fisik
                        </span>
                    @endif

                    @if (!empty($buku->tahun_terbit))
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-indigo-100 text-indigo-800">
                            Tahun {{ $buku->tahun_terbit }}
                        </span>
                    @endif

                    @if ($buku->kategori && $buku->kategori->isNotEmpty())
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700">
                            {{ $buku->kategori->first()->nama_kategori }}
                        </span>
                    @endif

                    @if ($buku->koleksi)
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700">
                            {{ $buku->koleksi->nama_koleksi }}
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <main class="pb-14">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid lg:grid-cols-12 gap-8 lg:gap-10">

                {{-- LEFT: Cover + actions --}}
                <aside class="lg:col-span-4">
                    <div class="lg:sticky lg:top-24 space-y-4">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="relative">
                                @if ($buku->url_cover && Storage::disk('public')->exists($buku->url_cover))
                                    <img src="{{ asset('storage/' . $buku->url_cover) }}"
                                        alt="Cover buku {{ $buku->judul_buku }}"
                                        class="w-full object-cover aspect-[4/5]">
                                @else
                                    <img src="{{ asset('storage/katalog_foto/cover-default.png') }}" alt="Cover default"
                                        class="w-full object-cover aspect-[4/5]">
                                @endif
                                <div
                                    class="absolute inset-0 bg-gradient-to-t from-slate-900/25 via-transparent to-transparent">
                                </div>
                            </div>

                            <div class="p-5">
                                <div class="space-y-3 text-sm">
                                    <div class="flex items-start justify-between gap-3">
                                        <span class="text-slate-500">Penerbit</span>
                                        <span class="text-slate-800 font-semibold text-right">
                                            @if ($buku->penerbit)
                                                <a href="{{ route('katalog.daftar-buku', ['search' => $buku->penerbit->nama_penerbit]) }}"
                                                    class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 hover:bg-indigo-100 transition">
                                                    {{ $buku->penerbit->nama_penerbit }}
                                                </a>
                                            @else
                                                -
                                            @endif
                                        </span>
                                    </div>

                                    <div class="flex items-start justify-between gap-3">
                                        <span class="text-slate-500">Tempat Terbit</span>
                                        <span
                                            class="text-slate-800 font-semibold text-right">{{ $buku->tempat_terbit ?? '-' }}</span>
                                    </div>

                                    <div class="flex items-start justify-between gap-3">
                                        <span class="text-slate-500">ISBN</span>
                                        <span
                                            class="text-slate-800 font-semibold text-right">{{ $buku->isbn ?? '-' }}</span>
                                    </div>

                                    <div class="flex items-start justify-between gap-3">
                                        <span class="text-slate-500">Nomor Panggil</span>
                                        <span class="text-slate-800 font-semibold text-right">
                                            <span class="px-2 py-1 rounded-lg bg-slate-100 font-mono">
                                                {{ $buku->nomor_panggil ?? '-' }}
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Action buttons --}}
                        <div class="grid gap-3">
                            {{-- Ebook action (kalau file ada) --}}
                            @if (!empty($buku->file_ebook) && Storage::disk('public')->exists($buku->file_ebook))
                                <a href="{{ asset('storage/' . $buku->file_ebook) }}" target="_blank" rel="noopener"
                                    class="inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-emerald-600 text-white font-semibold hover:bg-emerald-700 transition focus:outline-none focus:ring-4 focus:ring-emerald-200">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                        fill="currentColor">
                                        <path d="M4 3a2 2 0 012-2h6a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V3z" />
                                        <path d="M8 6a1 1 0 000 2h4a1 1 0 100-2H8z" />
                                    </svg>
                                    Baca Ebook
                                </a>
                            @endif

                            {{-- Wishlist --}}
                            @auth('anggota')
                                <form action="{{ route('wishlist.toggle', $buku->id) }}" method="POST">
                                    @csrf

                                    @if ($isInWishlist)
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-rose-600 text-white font-semibold hover:bg-rose-700 transition focus:outline-none focus:ring-4 focus:ring-rose-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20"
                                                fill="currentColor">
                                                <path fill-rule="evenodd"
                                                    d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                            Hapus dari Wishlist
                                        </button>
                                    @else
                                        <button type="submit"
                                            class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-800 font-semibold hover:bg-slate-50 transition focus:outline-none focus:ring-4 focus:ring-indigo-200">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                            </svg>
                                            Tambahkan ke Wishlist
                                        </button>
                                    @endif
                                </form>
                            @endauth

                            @guest('anggota')
                                <a href="{{ route('anggota.login.form') }}"
                                    class="w-full inline-flex items-center justify-center gap-2 px-4 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition focus:outline-none focus:ring-4 focus:ring-indigo-200">
                                    Masuk untuk Wishlist
                                </a>
                            @endguest
                        </div>
                    </div>
                </aside>

                {{-- RIGHT: Main content --}}
                <section class="lg:col-span-8">
                    {{-- Authors + availability summary --}}
                    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex-1">
                                <p class="text-sm text-slate-500">Penulis</p>
                                <div class="mt-2 flex flex-wrap gap-2">
                                    @forelse ($buku->penulis as $penulis)
                                        <a href="{{ route('katalog.daftar-buku', ['search' => $penulis->nama_penulis]) }}"
                                            class="inline-flex items-center px-3 py-1 rounded-full bg-indigo-50 text-indigo-700 border border-indigo-100 hover:bg-indigo-100 transition text-sm font-semibold">
                                            {{ $penulis->nama_penulis }}
                                        </a>
                                    @empty
                                        <span class="text-sm text-slate-500">Penulis tidak diketahui</span>
                                    @endforelse
                                </div>
                            </div>

                            @php
                                $isEbook = !empty($buku->file_ebook) || !empty($buku->format_ebook);
                                $ebookReadable =
                                    !empty($buku->file_ebook) && Storage::disk('public')->exists($buku->file_ebook);

                                // Fisik tetap dihitung dari eksemplar (item)
                                $totalFisik = (int) ($totalEksemplar ?? 0);
                                $availFisik = (int) ($tersedia ?? 0);
                                $pctFisik = $totalFisik > 0 ? (int) round(($availFisik / $totalFisik) * 100) : 0;
                            @endphp

                            <div class="w-full sm:w-72">
                                <div class="flex items-center justify-between gap-2">
                                    <p class="text-sm font-semibold text-slate-700">Ketersediaan</p>

                                    @if ($isEbook)
                                        @if ($ebookReadable)
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                ✅ Ebook tersedia
                                            </span>
                                        @else
                                            <span
                                                class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">
                                                ⚠️ Ebook bermasalah
                                            </span>
                                        @endif
                                    @endif
                                </div>

                                @if ($isEbook && !$ebookReadable)
                                    <p class="mt-2 text-xs text-rose-600">
                                        File ebook tidak ditemukan / link rusak. Hubungi admin untuk memperbaiki.
                                    </p>
                                @endif

                                @if ($totalFisik > 0)
                                    <div class="mt-3">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-semibold text-slate-700">Fisik</p>
                                            <p class="text-sm text-slate-600">
                                                <span
                                                    class="font-bold text-slate-900">{{ $availFisik }}</span>/{{ $totalFisik }}
                                            </p>
                                        </div>

                                        <div class="mt-2 h-2 rounded-full bg-slate-100 overflow-hidden">
                                            @if ($availFisik > 0)
                                                <div class="h-full bg-emerald-500"
                                                    style="width: {{ $pctFisik }}%"></div>
                                            @else
                                                <div class="h-full bg-rose-500" style="width: 100%"></div>
                                            @endif
                                        </div>

                                        <p class="mt-2 text-xs text-slate-500">
                                            {{ $availFisik > 0 ? 'Ada eksemplar fisik yang bisa dipinjam.' : 'Eksemplar fisik sedang dipinjam / tidak tersedia.' }}
                                        </p>
                                    </div>
                                @else
                                    <p class="mt-3 text-xs text-slate-500">
                                        Tidak ada eksemplar fisik untuk buku ini.
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Tabs --}}
                    <div x-data="{ tab: 'deskripsi' }" class="mt-6">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
                            <div class="border-b border-slate-200">
                                <nav class="flex gap-1 p-2" aria-label="Tabs">
                                    <button type="button" @click="tab='deskripsi'"
                                        :class="tab === 'deskripsi' ? 'bg-indigo-600 text-white shadow-sm' :
                                            'text-slate-600 hover:bg-slate-50 hover:text-indigo-700'"
                                        class="px-4 py-2.5 rounded-xl text-sm font-semibold transition">
                                        Deskripsi
                                    </button>

                                    <button type="button" @click="tab='ketersediaan'"
                                        :class="tab === 'ketersediaan' ? 'bg-indigo-600 text-white shadow-sm' :
                                            'text-slate-600 hover:bg-slate-50 hover:text-indigo-700'"
                                        class="px-4 py-2.5 rounded-xl text-sm font-semibold transition">
                                        Eksemplar
                                    </button>

                                    <button type="button" @click="tab='detail'"
                                        :class="tab === 'detail' ? 'bg-indigo-600 text-white shadow-sm' :
                                            'text-slate-600 hover:bg-slate-50 hover:text-indigo-700'"
                                        class="px-4 py-2.5 rounded-xl text-sm font-semibold transition">
                                        Detail
                                    </button>
                                </nav>
                            </div>

                            <div class="p-6">
                                <div x-show="tab==='deskripsi'" x-cloak x-transition.opacity>
                                    <div class="prose max-w-none text-slate-700">
                                        {!! $buku->sinopsis ?? '<p>Sinopsis untuk buku ini belum tersedia.</p>' !!}
                                    </div>
                                </div>

                                <div x-show="tab==='ketersediaan'" x-cloak x-transition.opacity>
                                    @php
                                        $isEbookTab = !empty($buku->file_ebook) || !empty($buku->format_ebook);
                                        $ebookReadableTab =
                                            !empty($buku->file_ebook) &&
                                            Storage::disk('public')->exists($buku->file_ebook);
                                    @endphp

                                    @if ($isEbookTab)
                                        <div class="mb-4 p-4 rounded-2xl border border-slate-200 bg-slate-50">
                                            @if ($ebookReadableTab)
                                                <div
                                                    class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                                                    <p class="text-sm text-slate-700">
                                                        Ebook bisa langsung dibaca — tidak perlu dipinjam. ✅
                                                    </p>
                                                    <a href="{{ asset('storage/' . $buku->file_ebook) }}"
                                                        target="_blank" rel="noopener"
                                                        class="inline-flex items-center justify-center px-4 py-2 rounded-xl bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">
                                                        Baca Ebook
                                                    </a>
                                                </div>
                                            @else
                                                <p class="text-sm text-rose-700">
                                                    Ebook terdeteksi, tapi file/link-nya tidak tersedia
                                                    (error/terhapus). ⚠️
                                                </p>
                                            @endif
                                        </div>
                                    @endif

                                    <div class="rounded-2xl border border-slate-200 overflow-hidden">
                                        <div
                                            class="grid grid-cols-12 bg-slate-50 px-4 py-3 text-xs font-bold text-slate-600">
                                            <div class="col-span-5 sm:col-span-4">Barcode</div>
                                            <div class="col-span-5 sm:col-span-6">Lokasi</div>
                                            <div class="col-span-2 sm:col-span-2 text-right">Status</div>
                                        </div>

                                        @forelse ($buku->items as $item)
                                            <div
                                                class="grid grid-cols-12 px-4 py-3 text-sm border-t border-slate-200 items-center">
                                                <div class="col-span-5 sm:col-span-4">
                                                    <span
                                                        class="inline-flex font-mono text-xs px-2 py-1 rounded-lg bg-slate-100 text-slate-800">
                                                        {{ $item->kode_barcode }}
                                                    </span>
                                                </div>

                                                <div class="col-span-5 sm:col-span-6 text-slate-700">
                                                    {{ $item->rak->lokasi ?? 'Belum memasukkan lokasi' }}
                                                    @if (!empty($item->rak->kode_rak))
                                                        <span
                                                            class="text-xs text-slate-500">({{ $item->rak->kode_rak }})</span>
                                                    @endif
                                                </div>

                                                <div class="col-span-2 sm:col-span-2 text-right">
                                                    @if ($item->status === 'Tersedia')
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">
                                                            Tersedia
                                                        </span>
                                                    @else
                                                        <span
                                                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">
                                                            {{ $item->status }}
                                                        </span>
                                                    @endif
                                                </div>
                                            </div>
                                        @empty
                                            <div class="p-6 text-center text-slate-500">
                                                Tidak ada data eksemplar untuk buku ini.
                                            </div>
                                        @endforelse
                                    </div>
                                </div>

                                <div x-show="tab==='detail'" x-cloak x-transition.opacity>
                                    <div class="grid sm:grid-cols-2 gap-4 text-sm">
                                        <div class="rounded-2xl border border-slate-200 p-4">
                                            <p class="text-xs font-bold text-slate-500">Jumlah Halaman</p>
                                            <p class="mt-1 font-semibold text-slate-900">
                                                {{ $buku->jumlah_halaman ?? '-' }}</p>
                                        </div>

                                        <div class="rounded-2xl border border-slate-200 p-4">
                                            <p class="text-xs font-bold text-slate-500">Edisi</p>
                                            <p class="mt-1 font-semibold text-slate-900">{{ $buku->edisi ?? '-' }}</p>
                                        </div>

                                        <div class="rounded-2xl border border-slate-200 p-4">
                                            <p class="text-xs font-bold text-slate-500">Format Ebook</p>
                                            <p class="mt-1 font-semibold text-slate-900">
                                                {{ $buku->format_ebook ?? '-' }}</p>
                                        </div>

                                        <div class="rounded-2xl border border-slate-200 p-4">
                                            <p class="text-xs font-bold text-slate-500">Koleksi</p>
                                            <p class="mt-1 font-semibold text-slate-900">
                                                {{ $buku->koleksi->nama_koleksi ?? '-' }}</p>
                                        </div>
                                    </div>

                                    <div class="mt-4 rounded-2xl border border-slate-200 p-4">
                                        <p class="text-xs font-bold text-slate-500">Catatan</p>
                                        <p class="mt-1 text-slate-700">
                                            Detail fisik bersifat opsional — kalau data belum diinput, tampil “-”.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- =======================
                        ULASAN (No. 6)
                        ======================= --}}
                    <section class="mt-8">
                        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6">
                            <div class="flex items-center justify-between gap-4">
                                <h2 class="text-lg font-bold text-slate-900">Ulasan Pembaca</h2>
                                <p class="text-sm text-slate-600">{{ $buku->komentar->count() }} ulasan</p>
                            </div>

                            @if (session('success'))
                                <div
                                    class="mt-4 p-3 rounded-xl bg-emerald-50 border border-emerald-200 text-emerald-800 text-sm">
                                    {{ session('success') }}
                                </div>
                            @endif

                            @auth('anggota')
                                <form action="{{ route('ulasan.store', $buku->id) }}" method="POST"
                                    class="mt-5 space-y-4">
                                    @csrf

                                    <div class="grid sm:grid-cols-2 gap-4">
                                        <div>
                                            <label
                                                class="block text-sm font-semibold text-slate-700 mb-2">Rekomendasi</label>
                                            <select name="rec"
                                                class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-200">
                                                <option value="recommended">✅ Direkomendasikan</option>
                                                <option value="neutral">➖ Biasa saja</option>
                                                <option value="not">❌ Tidak direkomendasikan</option>
                                            </select>
                                        </div>

                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Untuk siapa?
                                                (opsional)</label>
                                            <select name="aud"
                                                class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-200">
                                                <option value="">—</option>
                                                <option value="kelas7">Kelas 7</option>
                                                <option value="kelas8">Kelas 8</option>
                                                <option value="kelas9">Kelas 9</option>
                                                <option value="guru">Guru</option>
                                                <option value="pemula">Pemula</option>
                                                <option value="menengah">Menengah</option>
                                                <option value="lanjutan">Lanjutan</option>
                                            </select>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Ulasan</label>
                                        <textarea name="body" rows="4" class="w-full rounded-xl border-slate-200 focus:ring-4 focus:ring-indigo-200"
                                            placeholder="Tulis ulasan yang membantu: kenapa bagus/kurang, cocok untuk siapa, bagian yang paling berguna... (min 30 karakter)">{{ old('body') }}</textarea>
                                        @error('body')
                                            <p class="mt-2 text-sm text-rose-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <button type="submit"
                                        class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                        Kirim Ulasan
                                    </button>
                                </form>
                            @endauth

                            @guest('anggota')
                                <div class="mt-5 p-4 rounded-2xl bg-slate-50 border border-slate-200">
                                    <a href="{{ route('anggota.login.form') }}"
                                        class="inline-flex items-center justify-center px-5 py-3 rounded-xl bg-indigo-600 text-white font-semibold hover:bg-indigo-700 transition">
                                        Masuk untuk menulis ulasan
                                    </a>
                                </div>
                            @endguest

                            @php
                                $ulasan = $buku->komentar
                                    ->sortByDesc(fn($k) => method_exists($k, 'pinned') ? ($k->pinned() ? 1 : 0) : 0)
                                    ->filter(
                                        fn($k) => method_exists($k, 'status') ? $k->status() === 'approved' : true,
                                    );
                            @endphp

                            <div class="mt-8 space-y-4">
                                @forelse($ulasan as $k)
                                    <div class="p-5 rounded-2xl border border-slate-200">
                                        <div class="flex items-start justify-between gap-3">
                                            <div>
                                                <p class="font-bold text-slate-900">
                                                    {{ $k->anggota->nama_anggota ?? 'Anonim' }}</p>
                                                <p class="text-xs text-slate-500">
                                                    {{ optional($k->created_at)->format('d M Y, H:i') }}</p>
                                            </div>

                                            <div class="flex flex-wrap gap-2 justify-end">
                                                @php
                                                    $rec = method_exists($k, 'rec') ? $k->rec() : null;
                                                    $aud = method_exists($k, 'aud') ? $k->aud() : null;
                                                    $pinned = method_exists($k, 'pinned') ? $k->pinned() : false;
                                                @endphp

                                                @if ($rec === 'recommended')
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-emerald-100 text-emerald-800">✅
                                                        Recommended</span>
                                                @elseif($rec === 'neutral')
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-slate-100 text-slate-800">➖
                                                        Netral</span>
                                                @elseif($rec === 'not')
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-rose-100 text-rose-800">❌
                                                        Not recommended</span>
                                                @endif

                                                @if ($aud)
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-white border border-slate-200 text-slate-700">
                                                        {{ $aud }}
                                                    </span>
                                                @endif

                                                @if ($pinned)
                                                    <span
                                                        class="px-3 py-1 rounded-full text-xs font-semibold bg-amber-100 text-amber-800">📌
                                                        Pilihan</span>
                                                @endif
                                            </div>
                                        </div>

                                        <p class="mt-3 text-slate-700 leading-relaxed">
                                            {{ method_exists($k, 'body') ? $k->body() : $k->isi_komentar }}
                                        </p>
                                    </div>
                                @empty
                                    <div class="text-slate-500 text-sm mt-4">
                                        Belum ada ulasan yang disetujui. Jadilah yang pertama menulis ulasan berkualitas
                                        😉
                                    </div>
                                @endforelse
                            </div>
                        </div>
                    </section>

                </section>
            </div>
        </div>
    </main>
</body>

</html>

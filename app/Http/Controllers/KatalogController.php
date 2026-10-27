<?php

namespace App\Http\Controllers;

// Model yang dibutuhkan
use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Peminjaman;
use App\Models\Anggota;
use App\Models\Koleksi;
use App\Models\Rak;

// Helper & Class dari Laravel
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class KatalogController extends Controller
{
    /**
     * Menampilkan halaman utama katalog buku.
     */
    public function index(Request $request)
    {
        $buku = Buku::orderBy('judul_buku')->paginate(12);
        $kategori = Kategori::where('tampil_di_beranda', true) // 1. Filter hanya yang statusnya true
            ->orderBy('nama_kategori')           // 2. Urutkan berdasarkan nama
            ->take(9)                            // batas kategori yang ada diberanda
            ->get();                             // 4. Eksekusi query
        $query = Buku::with(['penulis', 'kategori', 'items']);
        if ($request->filled('q')) { // <-- Diubah dari 'search' jadi 'q'
            $searchTerm = '%' . $request->q . '%'; // <-- Diubah juga jadi 'q'
            $query->where(function ($queryBuilder) use ($searchTerm) { // P.S. ganti $q jadi nama lain biar gak bingung
                $queryBuilder->where('judul_buku', 'like', $searchTerm)
                    ->orWhereHas('penulis', function ($subq) use ($searchTerm) {
                        $subq->where('nama_penulis', 'like', $searchTerm);
                    })
                    ->orWhereHas('kategori', function ($subq) use ($searchTerm) {
                        $subq->where('nama_kategori', 'like', $searchTerm);
                    });
            });
        }

        // -- MULAI BAGIAN BARU --
        // Peta untuk ikon dan warna berdasarkan nama kategori
        // Ikon diambil dari Heroicons (SVG) biar gampang di-custom
        $iconMap = [
            'Fiksi' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.246 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>',
            'Sains' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547a2 2 0 00-.547 1.806l.477 2.387a6 6 0 00.517 3.86l.158.318a6 6 0 003.86.517l2.387.477a2 2 0 001.806-.547a2 2 0 00.547-1.806l-.477-2.387a6 6 0 00-.517-3.86l-.158-.318z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.572 14.572a2 2 0 00.547-1.806l-.477-2.387a6 6 0 00-.517-3.86l-.158-.318a6 6 0 00-3.86-.517l-2.387-.477a2 2 0 00-1.806.547a2 2 0 00-.547 1.806l.477 2.387a6 6 0 00.517 3.86l.158.318a6 6 0 003.86.517l2.387.477a2 2 0 001.806-.547z" /></svg>',
            'Sejarah' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>',
            'Teknologi' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 12h14M5 12a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2M5 12a2 2 0 00-2 2v4a2 2 0 002 2h14a2 2 0 002-2v-4a2 2 0 00-2-2m-2-4h.01M17 16h.01" /></svg>',
            'Biografi' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>',
            'Anak' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.783-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" /></svg>',
            'default' => '<svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /></svg>'
        ];

        $colorMap = [
            'Fiksi' => 'bg-indigo-100 text-indigo-700 hover:bg-indigo-200',
            'Sains' => 'bg-green-100 text-green-700 hover:bg-green-200',
            'Sejarah' => 'bg-yellow-100 text-yellow-700 hover:bg-yellow-200',
            'Teknologi' => 'bg-sky-100 text-sky-700 hover:bg-sky-200',
            'Biografi' => 'bg-rose-100 text-rose-700 hover:bg-rose-200',
            'Anak' => 'bg-amber-100 text-amber-700 hover:bg-amber-200',
            'default' => 'bg-gray-100 text-gray-700 hover:bg-gray-200'
        ];

        // Tambahkan properti ikon dan warna ke setiap objek kategori
        foreach ($kategori as $kat) {
            $key = 'default'; // default key
            foreach (array_keys($iconMap) as $map_key) {
                if (stripos($kat->nama_kategori, $map_key) !== false) {
                    $key = $map_key;
                    break;
                }
            }
            $kat->icon = $iconMap[$key];
            $kat->color = $colorMap[$key];
        }
        // -- SELESAI BAGIAN BARU --
        $bukuPopuler = Buku::select('buku.*', DB::raw('count(peminjaman_item_buku.id) as total_pinjam'))
            ->join('item_buku', 'buku.id', '=', 'item_buku.buku_id')
            ->join('peminjaman_item_buku', 'item_buku.id', '=', 'peminjaman_item_buku.item_buku_id')
            ->groupBy('buku.id')
            ->orderBy('total_pinjam', 'desc')
            ->take(4) // Ambil 4 buku teratas
            ->get();

        $bukuTerbaru = Buku::orderBy('created_at', 'desc')
            ->take(8) // Ambil 8 buku terbaru
            ->get();

        return view('katalog.index', compact('buku', 'kategori', 'bukuPopuler', 'bukuTerbaru'));
    }
    public function daftarBuku(Request $request)
    {
        // Query dasar (eager loading biar gak N+1)
        $query = Buku::with(['penulis', 'kategori', 'koleksi']);


        // Buat isi search box (kalau user klik kategori di beranda tanpa ngetik search)
        $searchInputValue = $request->input('search');
        if ($request->filled('kategori') && !$request->filled('search')) {
            $selectedCategory = Kategori::find($request->kategori);
            if ($selectedCategory) {
                $searchInputValue = $selectedCategory->nama_kategori;
            }
        }

        // SEARCH
        if ($request->filled('search')) {
            $searchTerm = '%' . $request->search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('judul_buku', 'like', $searchTerm)
                    ->orWhereHas('penulis', function ($subq) use ($searchTerm) {
                        $subq->where('nama_penulis', 'like', $searchTerm);
                    })
                    ->orWhereHas('kategori', function ($subq) use ($searchTerm) {
                        $subq->where('nama_kategori', 'like', $searchTerm);
                    })
                    ->orWhereHas('penerbit', function ($subq) use ($searchTerm) {
                        $subq->where('nama_penerbit', 'like', $searchTerm);
                    });
            });
        }

        // FILTER: Kategori
        if ($request->filled('kategori')) {
            $query->whereHas('kategori', function ($q) use ($request) {
                $q->where('kategori.id', $request->kategori);
            });
        }

        // FILTER: Ketersediaan (item_buku.status)
        // Kamu pakai value query param: ketersediaan=tersedia
        // Di DB kamu pakai status: 'Tersedia' (huruf besar) -> samakan
        if ($request->filled('ketersediaan') && $request->ketersediaan === 'tersedia') {
            $query->where(function ($q) {
                // Ebook dianggap selalu tersedia karena bisa langsung dibaca
                $q->whereNotNull('file_ebook')
                    ->orWhereNotNull('format_ebook')

                    // Buku fisik: harus ada item yang statusnya Tersedia
                    ->orWhereHas('items', function ($sub) {
                        $sub->where('status', 'Tersedia');
                    });
            });
        }

        // FILTER: Tahun Terbit
        if ($request->filled('tahun_terbit')) {
            $query->where('tahun_terbit', $request->tahun_terbit);
        }

        // FILTER: Tipe Koleksi
        if ($request->filled('koleksi')) {
            $query->where('koleksi_id', $request->koleksi);
        }

        // FILTER: Lokasi (Rak) via item -> rak
        if ($request->filled('lokasi')) {
            $query->whereHas('items.rak', function ($q) use ($request) {
                $q->where('id', $request->lokasi);
            });
        }

        // ✅ INI KUNCINYA: hitung total eksemplar dan yang tersedia
        $query->withCount([
            'items as total_item',
            'items as tersedia_item' => function ($q) {
                $q->where('status', 'Tersedia');
            },
        ]);

        // SORTING
        $sort = $request->input('sort', 'relevan');
        switch ($sort) {
            case 'terbaru':
                $query->orderBy('buku.created_at', 'desc');
                break;

            case 'perubahan_terakhir':
                $query->orderBy('buku.updated_at', 'desc');
                break;

            case 'tahun_terbaru':
                $query->orderBy('buku.tahun_terbit', 'desc');
                break;

            case 'tahun_terlama':
                $query->orderBy('buku.tahun_terbit', 'asc');
                break;

            case 'judul_asc':
                $query->orderBy('buku.judul_buku', 'asc');
                break;

            case 'judul_desc':
                $query->orderBy('buku.judul_buku', 'desc');
                break;

            case 'sering_dipinjam':
                $query->withCount('peminjamanItems')
                    ->orderBy('peminjaman_items_count', 'desc');
                break;

            case 'relevan':
            default:
                $query->orderBy('buku.created_at', 'desc');
                break;
        }

        // Pagination
        $buku = $query->paginate(20)->withQueryString();

        // Data untuk dropdown filter
        $kategori = Kategori::orderBy('nama_kategori')->get();
        $koleksi  = Koleksi::orderBy('nama_koleksi')->get();
        $lokasi   = Rak::orderBy('lokasi')->get();

        // Tahun terbit unik
        $tahunTerbit = Buku::select('tahun_terbit')
            ->distinct()
            ->orderBy('tahun_terbit', 'desc')
            ->pluck('tahun_terbit');

        return view('katalog.daftar-buku', [
            'books' => $buku,
            'categories' => $kategori,
            'allKoleksi' => $koleksi,
            'allLokasi' => $lokasi,
            'allTahun' => $tahunTerbit,
            'currentSort' => $sort,
            'searchInputValue' => $searchInputValue,
        ]);
    }


    /**
     * Menampilkan halaman 'Tentang Kami'.
     */
    public function tentang()
    {
        return view('katalog.tentang');
    }

    /**
     * Menampilkan halaman detail untuk satu buku.
     */
    public function show(Buku $buku)
    {
        // Load relasi yang memang dipakai view (hindari N+1)
        $buku->load([
            'penulis',
            'kategori',
            'penerbit',
            'koleksi',
            'items.rak',
            'komentar.anggota',
        ]);

        // Biarkan DB yang ngitung (lebih cepat daripada hitung di collection)
        $buku->loadCount([
            'items as total_eksemplar',
            'items as tersedia_eksemplar' => function ($q) {
                $q->where('status', 'Tersedia');
            },
        ]);

        $totalEksemplar = (int) $buku->total_eksemplar;
        $tersedia = (int) $buku->tersedia_eksemplar;

        $isInWishlist = false;
        if (Auth::guard('anggota')->check()) {
            /** @var \App\Models\Anggota $anggota */
            $anggota = Auth::guard('anggota')->user();

            $isInWishlist = $anggota->wishlist()
                ->where('buku_id', $buku->id)
                ->exists();
        }

        return view('katalog.detail-buku', compact('buku', 'totalEksemplar', 'tersedia', 'isInWishlist'));
    }


    /**
     * Menampilkan halaman profil anggota yang sedang login.
     */
    public function showProfile()
    {
        // 1. Ambil data anggota yang sedang login dari guard 'anggota'.
        /** @var \App\Models\Anggota $user */
        $user = Auth::guard('anggota')->user();

        // 2. Ambil data riwayat peminjaman milik user tersebut.
        $loanHistory = Peminjaman::with('items.buku')
            ->where('anggota_id', $user->id)
            ->whereHas('items')
            ->orderBy('tanggal_pinjam', 'desc')
            ->take(10) // Ambil 10 riwayat terbaru saja
            ->get();
        $wishlistItems = $user->wishlist()
            ->with('penulis') // Eager load penulis biar query lebih efisien
            ->latest('wishlists.created_at') // Urutkan dari yang paling baru ditambahin
            ->take(6) // Ambil 6 buku aja biar nggak kepanjangan
            ->get();
        $ulasanHistory = \App\Models\KomentarBuku::with('buku')
            ->where('anggota_id', $user->id)
            ->latest()
            ->take(20)
            ->get();

        // 3. Kirim data ke view.
        return view('katalog.profil', [
            'user' => $user,
            'loanHistory' => $loanHistory,
            'wishlistItems' => $wishlistItems,
            'ulasanHistory' => $ulasanHistory,
        ]);
    }

    /**
     * Menampilkan form untuk mengedit profil anggota.
     * Menggunakan Route Model Binding untuk mengambil data asli.
     */
    public function showEditForm(Anggota $anggota)
    {
        // Pastikan anggota yang login hanya bisa edit profilnya sendiri
        // Ini bisa juga diperkuat dengan policy atau pengecekan ID di sini
        if (Auth::guard('anggota')->user()->id !== $anggota->id) {
            abort(403, 'Akses Ditolak'); // Mencegah user edit profil orang lain via URL
        }

        return view('katalog.edit-profil', [
            'user' => $anggota
        ]);
    }

    /**
     * Memproses data dari form edit profil dan menyimpannya ke database.
     */
    public function updateProfile(Request $request, Anggota $anggota)
    {
        // Pastikan anggota yang login hanya bisa update profilnya sendiri
        if (Auth::guard('anggota')->user()->id !== $anggota->id) {
            abort(403, 'Akses Ditolak');
        }

        // 1. Validasi data
        $request->validate([
            'nama_anggota' => 'required|string|max:255',
            'no_telepon' => 'nullable|string|max:15',
            'alamat' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // Max 2MB
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        // 2. Ambil semua data valid dari request
        $data = $request->except(['_token', '_method', 'password_confirmation']);

        // 3. Logika untuk upload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($anggota->foto && Storage::disk('public')->exists($anggota->foto)) {
                Storage::disk('public')->delete($anggota->foto);
            }

            // Simpan foto baru ke 'storage/app/public/anggota_foto'
            // Laravel otomatis membuat nama file yang unik
            $path = $request->file('foto')->store('anggota_foto', 'public');
            $data['foto'] = $path;
        }

        // 4. Logika untuk update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        } else {
            unset($data['password']); // Jangan update password jika fieldnya kosong
        }

        // 5. Update data anggota di database
        $anggota->update($data);

        // 6. Redirect kembali ke halaman profil dengan pesan sukses
        return redirect()->route('anggota.profil')->with('success', 'Profil kamu berhasil diperbarui!');
    }

    /**
     * Proses logout untuk anggota.
     */
    public function logout(Request $request)
    {
        Auth::guard('anggota')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('katalog.index')->with('status', 'Kamu berhasil logout!');
    }

    /**
     * Menyimpan komentar baru dari anggota untuk sebuah buku.
     */
    public function storeKomentar(Request $request, Buku $buku)
    {
        // 1. Validasi dulu inputnya, wajib diisi ya!
        $request->validate([
            'isi_komentar' => 'required|string|min:5|max:2000',
        ], [
            'isi_komentar.required' => 'Komentar jangan dikosongin dong!',
            'isi_komentar.min' => 'Komentarnya kependekan, minimal 5 karakter ya.',
        ]);

        // 2. Buat komentar baru pakai relasi yang udah dibikin
        $buku->komentar()->create([
            'anggota_id' => auth('anggota')->id(), // Ambil ID anggota yg lagi login
            'isi_komentar' => $request->isi_komentar,
        ]);

        // 3. Balikin ke halaman sebelumnya dengan pesan sukses
        return back()->with('success', 'Makasih, komentarmu berhasil ditambahin!');
    }
}

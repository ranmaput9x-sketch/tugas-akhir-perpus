<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\Kategori;
use App\Models\Koleksi;
use App\Models\Penerbit;
use App\Models\Penulis;
use App\Models\Rak;
use App\Models\ItemBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PDF;
use Illuminate\Support\Str;

class BukuController extends Controller
{
    /**
     * Menampilkan daftar semua buku.
     */
    public function index()
    {
        $bukus = Buku::with(['penulis', 'penerbit', 'items'])->latest()->get();
        return view('buku.index', compact('bukus'));
    }

    /**
     * Menampilkan form untuk membuat buku baru.
     */
    public function create()
    {
        return view('buku.create', [
            // Data dikirim ke view untuk pilihan di form
            'penulis'  => Penulis::orderBy('nama_penulis')->get(),
            'penerbit' => Penerbit::orderBy('nama_penerbit')->get(),
            'kategori' => Kategori::orderBy('nama_kategori')->get(),
            'koleksis' => Koleksi::all(),
            'raks'     => Rak::orderBy('kode_rak')->get(),
        ]);
    }

    /**
     * Menyimpan data buku baru ke database.
     */
    public function store(Request $request)
    {
        // Jalankan validasi menggunakan method terpusat
        $validatedData = $request->validate(
            $this->getValidationRules(),
            [
                'file_ebook.required' => 'File eBook wajib diunggah untuk koleksi digital.',
                'file_ebook.mimes'    => 'Format file eBook harus PDF atau EPUB.',
                'file_ebook.max'      => 'Ukuran file eBook maksimal 10MB.',
            ]
        );


        // Mulai transaksi database untuk menjaga integritas data
        DB::beginTransaction();
        try {
            // Siapkan data untuk tabel 'buku'
            $bukuData = $validatedData;

            // Handle upload file cover & ebook
            if ($request->hasFile('url_cover')) {
                $bukuData['url_cover'] = $request->file('url_cover')->store('covers', 'public');
            }
            if ($request->hasFile('file_ebook')) {
                $bukuData['file_ebook'] = $request->file('file_ebook')->store('ebooks', 'public');
                $bukuData['format_ebook'] = $request->file('file_ebook')->getClientOriginalExtension();
            }

            // Handle atau buat relasi baru menggunakan helper
            $bukuData['penerbit_id'] = $this->syncOrCreatePenerbit($request->penerbit_id);
            $kategoriId = $this->syncOrCreateKategori($request->kategori_id);
            $penulisIds = array_map([$this, 'syncOrCreatePenulis'], $request->penulis_id);

            // Buat entitas Buku utama (nomor panggil akan di-update nanti)
            $buku = Buku::create($bukuData);

            // Sinkronisasi relasi Many-to-Many
            $buku->penulis()->sync($penulisIds);
            $buku->kategori()->sync([$kategoriId]);

            // Generate Nomor Panggil unik dan update ke buku
            $penulisUtama = Penulis::find($penulisIds[0]);
            $kategoriUtama = Kategori::find($kategoriId);
            $nomorPanggil = $this->generateUniqueCallNumber($kategoriUtama, $penulisUtama, $buku->judul_buku, $buku->tahun_terbit);
            $buku->update(['nomor_panggil' => $nomorPanggil]);

            // Cek apakah perlu dibuatkan item/eksemplar fisik
            $koleksi = Koleksi::find($request->koleksi_id);
            if (!$this->isDigitalKoleksiId($request->koleksi_id) && $request->filled('jumlah_eksemplar')) {

                $this->createItems($buku, $request);
            }

            // Jika semua berhasil, commit transaksi
            DB::commit();

            return redirect()->route('buku.show', $buku)->with('success', 'Buku dan eksemplar berhasil ditambahkan!');
        } catch (\Throwable $e) {
            // Jika ada error, batalkan semua query (rollback)
            DB::rollBack();
            return back()->withErrors('Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan detail satu buku.
     */
    public function show(Buku $buku)
    {
        $buku->load(['penulis', 'kategori', 'penerbit', 'koleksi', 'items.rak']);
        return view('buku.show', compact('buku'));
    }

    /**
     * Menampilkan form untuk mengedit data buku.
     */
    public function edit(Buku $buku)
    {
        return view('buku.edit', [
            'buku'      => $buku,
            'penulis'   => Penulis::orderBy('nama_penulis')->get(),
            'penerbits' => Penerbit::orderBy('nama_penerbit')->get(),
            'kategoris' => Kategori::orderBy('nama_kategori')->get(),
            'koleksis'  => Koleksi::all(),
            'raks'      => Rak::orderBy('kode_rak')->get(),
        ]);
    }

    public function update(Request $request, Buku $buku)
    {
        $validatedData = $request->validate(
            $this->getValidationRules(),
            [
                'file_ebook.required' => 'File eBook wajib diunggah untuk koleksi digital.',
                'file_ebook.mimes'    => 'Format file eBook harus PDF atau EPUB.',
                'file_ebook.max'      => 'Ukuran file eBook maksimal 10MB.',
            ]
        );


        DB::beginTransaction();
        try {
            $bukuData = $validatedData;

            // cover: kalau upload baru, hapus yang lama
            if ($request->hasFile('url_cover')) {
                if ($buku->url_cover && Storage::disk('public')->exists($buku->url_cover)) {
                    Storage::disk('public')->delete($buku->url_cover);
                }
                $bukuData['url_cover'] = $request->file('url_cover')->store('covers', 'public');
            } else {
                unset($bukuData['url_cover']); // jangan null-kan kalau tidak upload
            }

            // ebook: kalau upload baru, hapus yang lama
            if ($request->hasFile('file_ebook')) {
                if ($buku->file_ebook && Storage::disk('public')->exists($buku->file_ebook)) {
                    Storage::disk('public')->delete($buku->file_ebook);
                }
                $bukuData['file_ebook'] = $request->file('file_ebook')->store('ebooks', 'public');
                $bukuData['format_ebook'] = $request->file('file_ebook')->getClientOriginalExtension();
            } else {
                unset($bukuData['file_ebook'], $bukuData['format_ebook']);
            }

            // relasi baru/lama
            $bukuData['penerbit_id'] = $this->syncOrCreatePenerbit($request->penerbit_id);
            $kategoriId = $this->syncOrCreateKategori($request->kategori_id);
            $penulisIds = array_map([$this, 'syncOrCreatePenulis'], $request->penulis_id);

            $buku->update($bukuData);

            $buku->penulis()->sync($penulisIds);
            $buku->kategori()->sync([$kategoriId]);

            // nomor panggil: regenerate biar konsisten dan unik
            $penulisUtama = Penulis::find($penulisIds[0]);
            $kategoriUtama = Kategori::find($kategoriId);
            $nomorPanggil = $this->generateUniqueCallNumber(
                $kategoriUtama,
                $penulisUtama,
                $buku->judul_buku,
                $buku->tahun_terbit,
                $buku->id
            );
            $buku->update(['nomor_panggil' => $nomorPanggil]);

            DB::commit();
            return redirect()->route('buku.show', $buku)->with('success', 'Buku berhasil diperbarui!');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal update data: ' . $e->getMessage())->withInput();
        }
    }


    /**
     * Menghapus data buku dari database.
     */
    public function destroy(Buku $buku)
    {
        try {
            if ($buku->url_cover) {
                Storage::disk('public')->delete($buku->url_cover);
            }
            if ($buku->file_ebook) {
                Storage::disk('public')->delete($buku->file_ebook);
            }
            $buku->delete();
            return redirect()->route('buku.index')->with('success', 'Buku berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus data: ' . $e->getMessage());
        }
    }

    // ===================================================================================
    // PROTECTED / PRIVATE METHODS (Fungsi Bantuan)
    // ===================================================================================

    /**
     * Aturan validasi yang digunakan bersama oleh method store dan update.
     */
    protected function getValidationRules(?Buku $buku = null): array
    {
        $request = request();
        $isDigital = $this->isDigitalKoleksiId($request->koleksi_id);

        $mustUploadEbook = $isDigital && (
            // create buku digital harus upload
            !$buku ||
            // update: wajib upload hanya kalau sebelumnya belum ada file
            empty($buku->file_ebook)
        );

        return [
            'judul_buku'     => 'required|string|max:255',
            'sub_judul'      => 'nullable|string|max:255',
            'isbn'           => ['nullable', 'string', 'max:25', Rule::unique('buku', 'isbn')->ignore($buku)],
            'tahun_terbit'   => 'required|digits:4|integer|min:1800|max:' . now()->year,
            'jumlah_halaman' => 'required|integer|min:1',
            'deskripsi'      => 'nullable|string',
            'url_cover'      => 'nullable|image|max:2048',

            // ebook: required hanya saat wajib
            'file_ebook'     => ['nullable', Rule::requiredIf($mustUploadEbook), 'file', 'mimes:pdf,epub', 'max:10240'],

            'penerbit_id'    => 'required',
            'kategori_id'    => 'required',
            'penulis_id'     => 'required|array|min:1',
            'penulis_id.*'   => 'required',
            'koleksi_id'     => 'required|exists:koleksis,id',
            'tempat_terbit'  => 'nullable|string|max:100',
            'edisi'          => 'nullable|string|max:50',
            'nomor_panggil'  => 'required|string|max:255',

            // fisik: hanya wajib saat CREATE fisik (bukan update)
            'jumlah_eksemplar'  => [Rule::requiredIf(!$isDigital && !$buku), 'nullable', 'integer', 'min:1'],
            'rak_id'            => [Rule::requiredIf(!$isDigital && !$buku), 'nullable', 'exists:rak,id'],
            'sumber_perolehan'  => [Rule::requiredIf(!$isDigital && !$buku), 'nullable', 'string'],
            'tanggal_perolehan' => [Rule::requiredIf(!$isDigital && !$buku), 'nullable', 'date'],
            'harga'             => 'nullable|numeric|min:0',
        ];
    }


    /**
     * Fungsi Otomatis untuk Generate Nomor Panggil yang Unik.
     */
    private function generateUniqueCallNumber(
        Kategori $kategori,
        Penulis  $penulisUtama,
        string   $judulBuku,
        string   $tahunTerbit,
        ?int     $bukuIdToIgnore = null
    ): string {
        $code = $kategori->kode_klasifikasi ?: '000';
        $abbr = strtoupper(substr(
            preg_replace('/[^A-Za-z]/', '', $penulisUtama->nama_penulis),
            0,
            3
        ));
        $init = strtolower(substr($judulBuku, 0, 1));

        // Base always includes year
        $base = sprintf('%s %s %s %s', $code, $tahunTerbit, $abbr, $init);

        // Ignore current book ID when updating
        $query = fn($np) =>
        Buku::where('nomor_panggil', $np)
            ->when($bukuIdToIgnore, fn($q) => $q->where('id', '!=', $bukuIdToIgnore));

        // If unique, return base
        if (! $query($base)->exists()) {
            return $base;
        }

        // Otherwise, append no.X
        $i = 1;
        while ($query("{$base} no.{$i}")->exists()) {
            $i++;
        }

        return "{$base} no.{$i}";
    }

    /**
     * Membuat item/eksemplar buku berdasarkan jumlah yang diinput.
     */
    private function createItems(Buku $buku, Request $request): void
    {
        $jumlah = (int) $request->jumlah_eksemplar;
        $nomorEksemplarAwal = $buku->items()->count();

        for ($i = 1; $i <= $jumlah; $i++) {
            $newItem = $buku->items()->create([
                'rak_id'            => $request->rak_id,
                'status'            => 'Tersedia',
                'sumber_perolehan'  => $request->sumber_perolehan,
                'tanggal_perolehan' => $request->tanggal_perolehan,
                'harga'             => $request->harga,
            ]);
            $newItem->update([
                'kode_barcode' => 'INV-' . str_pad($newItem->id, 6, '0', STR_PAD_LEFT),
                'nomor_eksemplar'   => $nomorEksemplarAwal + $i,
            ]);
        }
    }

    /**
     * Helper untuk mencari atau membuat penulis baru.
     */
    protected function syncOrCreatePenulis(string|int $input): int
    {
        if (is_numeric($input)) return (int) $input;
        return Penulis::firstOrCreate(['nama_penulis' => trim($input)])->id;
    }

    /**
     * Helper untuk mencari atau membuat penerbit baru.
     */
    protected function syncOrCreatePenerbit(string|int $input): int
    {
        if (is_numeric($input)) return (int) $input;
        return Penerbit::firstOrCreate(['nama_penerbit' => trim($input)])->id;
    }

    /**
     * Helper untuk mencari atau membuat kategori baru.
     */
    protected function syncOrCreateKategori(string|int $input): int
    {
        if (is_numeric($input)) return (int) $input;
        $nama = trim($input);
        return Kategori::firstOrCreate(['nama_kategori' => $nama])->id;
    }

    public function cetakLabel(Buku $buku)
    {
        $pdf = PDF::loadView('buku.pdf.label', compact('buku'));

        // Pakai setPaper biar Dompdf pasti page-nya 30×40 mm
        // 30 mm ≈ 85.04 pt, 40 mm ≈ 113.39 pt
        $pdf->setPaper([0, 0, 85.04, 113.39], 'portrait');

        $fileName = Str::slug($buku->judul_buku) . '-label-3x4cm.pdf';
        return $pdf->download($fileName);
    }

    public function cetakBarcode(Buku $buku)
    {
        $items = $buku->items()->with(['buku.kategori'])->get();
        $pdf = PDF::loadView('buku.pdf.barcode', compact('items', 'buku'));
        $fileName = Str::slug($buku->judul_buku) . '-hasil-cetak-barcode.pdf';
        return $pdf->download($fileName);
    }

    public function bulkDestroy(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'selected'   => 'required|array',
            'selected.*' => 'exists:buku,id',
        ], [
            'selected.required'   => 'Pilih minimal satu buku yang ingin dihapus.',
            'selected.*.exists'   => 'Buku dengan ID :input tidak ditemukan.',
        ]);

        // 2. Ambil dulu model-model-nya
        $bukus = Buku::whereIn('id', $request->input('selected'))->get();

        // 3. Hapus file cover di storage untuk tiap buku
        foreach ($bukus as $buku) {
            if ($buku->url_cover && Storage::disk('public')->exists($buku->url_cover)) {
                Storage::disk('public')->delete($buku->url_cover);
            }
            if ($buku->file_ebook && Storage::disk('public')->exists($buku->file_ebook)) {
                Storage::disk('public')->delete($buku->file_ebook);
            }
        }
        // 4. Baru hapus record-nya
        Buku::whereIn('id', $request->input('selected'))->delete();

        // 5. Redirect balik dengan notifikasi
        return redirect()
            ->route('buku.index')
            ->with('success', 'Buku terpilih beserta file cover & eBook berhasil dihapus!');
    }
    private function isDigitalKoleksiId($koleksiId): bool
    {
        if (!$koleksiId) return false;

        $koleksi = Koleksi::find($koleksiId);
        if (!$koleksi) return false;

        $name = Str::lower(trim($koleksi->nama_koleksi ?? ''));

        // fleksibel: digital / ebook / e-book / media digital, dll
        return Str::contains($name, ['digital', 'ebook', 'e-book']);
    }
}

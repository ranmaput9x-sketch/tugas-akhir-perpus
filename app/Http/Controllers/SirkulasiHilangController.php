<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Models\ItemBuku;
use App\Models\Penerbit;
use App\Models\Kategori;
use App\Models\Koleksi;
use App\Models\Penulis;
use App\Models\Rak;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Str;


class SirkulasiHilangController extends Controller
{
    /**
     * Tampilkan semua pivot yg kondisi='hilang'
     * dan belum punya pengganti
     */
    public function index()
    {
        $lost = DB::table('peminjaman_item_buku as pi')
            ->join('item_buku as i', 'pi.item_buku_id', '=', 'i.id')
            ->join('buku as b',         'i.buku_id',         '=', 'b.id')
            ->join('peminjaman as p',    'pi.peminjaman_id',  '=', 'p.id')
            ->where('pi.kondisi', 'hilang')
            ->whereNull('pi.pengganti_item_buku_id')
            ->select([
                'pi.id as pivot_id',
                'pi.peminjaman_id',
                'i.id       as item_id',
                'i.buku_id  as buku_id',
                'i.kode_barcode',
                'b.judul_buku',
                'p.anggota_id',
            ])
            ->get();

        $bukuList      = Buku::select('id', 'judul_buku')
            ->orderBy('judul_buku')
            ->get();
        $penerbitList  = Penerbit::select('id', 'nama_penerbit')
            ->orderBy('nama_penerbit')
            ->get();
        $rakList       = Rak::select('id', 'kode_rak', 'lokasi')
            ->orderBy('kode_rak')
            ->get();
        $kategoriList  = Kategori::select('id', 'nama_kategori')
            ->orderBy('nama_kategori')
            ->get();
        $koleksiList = DB::table('koleksis')->select('id', 'nama_koleksi')->get();
        $penulisList = Penulis::select('id', 'nama_penulis')
            ->orderBy('nama_penulis')
            ->get();
        return view('sirkulasi.hilang', compact(
            'lost',
            'bukuList',
            'penerbitList',
            'rakList',
            'kategoriList',
            'koleksiList',
            'penulisList'
        ));
    }
    /**
     * Terima input quick‑create pengganti untuk beberapa pivot sekaligus
     */
    public function store(Request $request)
    {
        $request->validate([
            'pivot_id'        => 'required|array',
            'pivot_id.*'      => 'exists:peminjaman_item_buku,id',
            'buku_id'         => 'required|array',
            'buku_id.*'       => 'exists:buku,id',
            'kode_pengganti'  => 'required|array',
            'kode_pengganti.*' => 'string|max:50',

        ]);

        DB::beginTransaction();
        try {
            foreach ($request->pivot_id as $i => $pivotId) {
                $bukuId = $request->buku_id[$i];
                $kode   = $request->kode_pengganti[$i];

                // 1) buat eksemplar baru
                $newItemId = DB::table('item_buku')->insertGetId([
                    'buku_id'      => $bukuId,
                    'kode_barcode' => $kode,
                    'status'       => 'Tersedia',
                    'created_at'   => now(),
                    'updated_at'   => now(),
                ]);

                // 2) update pivot tanpa model
                DB::table('peminjaman_item_buku')
                    ->where('id', $pivotId)
                    ->update([
                        'pengganti_item_buku_id' => $newItemId,
                        'updated_at'             => now(),
                    ]);

                // 3) biarkan kondisi='hilang' tetap di pivot
                //    (karena kita hanya mengisi pengganti_item_buku_id)
            }

            DB::commit();
            return redirect()
                ->route('sirkulasi.hilang.index')
                ->with('success', 'Pengganti berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors('Gagal: ' . $e->getMessage());
        }
    }

    public function updatePivot(Request $req)
    {
        $req->validate([
            'pivot_id'     => 'required|integer|exists:peminjaman_item_buku,id',
            'pengganti_id' => 'required|integer|exists:item_buku,id',
        ]);

        // 1) update pivot
        DB::table('peminjaman_item_buku')
            ->where('id', $req->pivot_id)
            ->update([
                'pengganti_item_buku_id' => $req->pengganti_id,
                'kondisi' => 'baik', // atau buat enum baru 'diganti'
                'updated_at' => now(),
            ]);

        // 2) ambil judul dari Buku lewat ItemBuku->Buku
        $itemPengganti = ItemBuku::with('buku')->find($req->pengganti_id);
        $judul         = $itemPengganti->buku->judul_buku;

        return response()->json([
            'success' => true,
            'judul'   => $judul,
        ]);
    }
    public function createBuku(Request $req)
    {
        $req->validate([
            'pivot_id'        => 'required|integer|exists:peminjaman_item_buku,id',
            'buku_id'         => 'nullable|integer|exists:buku,id',
            'judul_baru'      => 'required_without:buku_id|string|max:255',
            'isbn'            => 'nullable|string|max:20',
            'deskripsi'       => 'nullable|string',
            'pengarang'       => 'nullable|string|max:255',
            'tahun_terbit'    => 'required|digits:4|integer|min:1900|max:' . date('Y'),
            'jumlah_halaman'  => 'required_without:buku_id|integer|min:1',
            'sub_judul'       => 'nullable|string|max:255',
            'tempat_terbit'   => 'nullable|string|max:255',
            'edisi'           => 'nullable|string|max:100',
            'nomor_panggil'   => 'nullable|string|max:50',
            'koleksi_id'      => 'nullable|integer|exists:koleksis,id',
            'kategori_id'     => 'nullable|integer|exists:kategori,id',
            'nama_kategori'   => 'nullable|required_without:kategori_id|string|max:255',
            'penulis_id'      => 'nullable|integer|exists:penulis,id',
            'nama_penulis'    => 'nullable|required_without:penulis_id|string|max:255',
            'sumber_perolehan' => 'nullable|string|max:255',
            'tanggal_perolehan' => 'nullable|date',
            'harga'           => 'nullable|numeric|min:0',
            'penerbit_id'     => 'nullable|integer|exists:penerbit,id',
            'nama_penerbit'   => 'nullable|required_without:penerbit_id|string|max:255',
            'rak_id'          => 'required|integer|exists:rak,id',
        ]);

        DB::beginTransaction();
        try {
            // Penerbit
            if ($req->filled('penerbit_id')) {
                $penerbit = Penerbit::find($req->penerbit_id);
            } elseif ($req->filled('nama_penerbit')) {
                $penerbit = Penerbit::firstOrCreate(['nama_penerbit' => $req->nama_penerbit]);
            } else {
                throw ValidationException::withMessages(['penerbit_id' => 'Penerbit wajib dipilih.']);
            }

            // Penulis
            if ($req->filled('penulis_id')) {
                $penulis = Penulis::find($req->penulis_id);
            } elseif ($req->filled('nama_penulis')) {
                $penulis = Penulis::firstOrCreate(['nama_penulis' => $req->nama_penulis]);
            }

            // ambil kode klasifikasi:
            if ($req->filled('kategori_id')) {
                $kategori = Kategori::find($req->kategori_id);
            } else {
                $kategori = Kategori::firstOrCreate(['nama_kategori' => $req->nama_kategori]);
            }
            $kodeKlas = $kategori->kode_klasifikasi;  // misal "384.36"

            // hitung tiga huruf awal penulis
            $penName = $penulis->nama_penulis ?? '';
            $alpha   = preg_replace('/[^A-Za-z]/', '', $penName);
            $tiga    = strtoupper(Str::limit($alpha, 3, ''));
            if (strlen($tiga) < 3) $tiga = strtoupper(Str::substr($alpha, 0, 3));

            // huruf pertama judul
            $first   = strtolower(Str::substr($req->judul_baru, 0, 1));

            // nomor panggil:
            $nomorPanggil = "{$kodeKlas} {$tiga} {$first}";

            // Koleksi
            if ($req->filled('koleksi_id')) {
                $koleksi = Koleksi::find($req->koleksi_id);
            }

            // Data untuk Buku
            $bukuData = [
                'judul_buku'     => $req->judul_baru,
                'pengarang'      => $req->pengarang,
                'sub_judul'      => $req->sub_judul,
                'isbn'           => $req->isbn,
                'deskripsi'      => $req->deskripsi,
                'penerbit_id'    => $penerbit->id,
                'penulis_id'     => $penulis->id ?? null,
                'kategori_id'    => $kategori->id ?? null,
                'koleksi_id'     => $koleksi->id ?? null,
                'tahun_terbit'   => $req->tahun_terbit,
                'jumlah_halaman' => $req->jumlah_halaman,
                'tempat_terbit'  => $req->tempat_terbit,
                'edisi'          => $req->edisi,
                'nomor_panggil'  => $nomorPanggil,
            ];

            if ($req->filled('buku_id')) {
                $buku = Buku::find($req->buku_id);
                $buku->update($bukuData);
            } else {
                $buku = Buku::create($bukuData);
            }
            $buku->penulis()->sync($req->input('penulis_id', []));
            $buku->kategori()->sync($req->input('kategori_id', []));
            // Buat eksemplar baru
            $next    = ItemBuku::max('id') + 1;
            $barcode = 'INV-' . str_pad($next, 6, '0', STR_PAD_LEFT);
            $item = ItemBuku::create([
                'buku_id'           => $buku->id,
                'kode_barcode'      => $barcode,
                'status'            => 'Tersedia',
                'rak_id'            => $req->rak_id,
                'sumber_perolehan'  => $req->sumber_perolehan,
                'tanggal_perolehan' => $req->tanggal_perolehan,
                'harga'             => $req->harga,
            ]);

            // Update pivot
            DB::table('peminjaman_item_buku')
                ->where('id', $req->pivot_id)
                ->update([
                    'pengganti_item_buku_id' => $item->id,
                    'updated_at'             => now(),
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'item_id' => $item->id,
                'kode'    => $item->kode_barcode,
                'judul'   => $buku->judul_buku,
            ]);
        } catch (\Throwable $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Gagal: ' . $e->getMessage(),
            ], 500);
        }
    }
}

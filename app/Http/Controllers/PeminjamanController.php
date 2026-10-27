<?php

namespace App\Http\Controllers;

use App\Models\Peminjaman;
use App\Models\ItemBuku;
use App\Models\Anggota;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class PeminjamanController extends Controller
{
    /**
     * Aturan sekolah:
     * - Masa pinjam awal: 7 hari
     * - Maksimal total masa pinjam: 14 hari sejak tanggal_pinjam
     * - Perpanjangan hanya +7 hari (sekali), dan dihitung dari tanggal_kembali lama (bukan dari hari ini)
     */
    private const LOAN_DAYS_DEFAULT = 7;
    private const LOAN_DAYS_MAX_TOTAL = 14;
    private const MAX_ACTIVE_ITEMS = 2;
    public function index()
    {
        $peminjamans = Peminjaman::with([
            'anggota',
            'items.buku',
            // relasi pivot ke pengganti (jika kamu sudah definisikan)
            'items.pivotPengganti'
        ])->get();

        return view('peminjaman.index', compact('peminjamans'));
    }

    public function halamanSirkulasi()
    {
        return view('peminjaman.sirkulasi');
    }
    public function hasilSirkulasi()
    {
        // ambil 20 transaksi per halaman
        $peminjamans = Peminjaman::with(['anggota', 'items.buku'])
            ->orderBy('tanggal_pinjam', 'desc')
            ->paginate(20);      // ← paginate, bukan get()

        return view('sirkulasi.hasil', compact('peminjamans'));
    }


    public function cariAnggota(Request $request)
    {
        $request->validate([
            'kode' => 'required|string'
        ]);

        // Temukan anggota berdasar id_anggota
        // Catatan: status transaksi bisa tidak merepresentasikan status item (partial return).
        // Sumber kebenaran untuk "masih dipinjam" adalah item_buku.status = Dipinjam.
        $anggota = Anggota::where('id_anggota', $request->kode)->first();

        if (! $anggota) {
            return response()->json([
                'success' => false,
                'message' => 'Anggota tidak ditemukan.'
            ], 404);
        }

        // Cek status non-aktif / kadaluwarsa
        if ($anggota->status === 'non-aktif' || $anggota->status === 'kadaluwarsa') {
            return response()->json([
                'success' => false,
                'message' => $anggota->status === 'non-aktif'
                    ? 'Status anggota ini Non-Aktif.'
                    : 'Kartu anggota sudah kadaluwarsa.'
            ], 422);
        }

        // Ambil transaksi yang masih aktif.
        // BUG yang kamu lihat di UI (transaksi sudah "Kembali" tapi masih muncul sebagai aktif)
        // terjadi karena sebelumnya kita hanya memfilter berdasarkan status item.
        // Jadi, transaksi yang status header-nya sudah Kembali tetap ikut kebawa kalau relasi item-nya
        // masih ada (atau data lama belum rapi).
        // Solusi: kunci juga ke status transaksi = Dipinjam (dan belum punya tanggal_pengembalian_aktual).
        $activePeminjamans = $anggota->peminjaman()
            ->where('status', 'Dipinjam')
            ->whereNull('tanggal_pengembalian_aktual')
            ->whereHas('items', fn($q) => $q->where('item_buku.status', 'Dipinjam'))
            ->with('items.buku')
            ->get();

        // Hitung pinjaman aktif berbasis JUMLAH ITEM yang masih Dipinjam (bukan jumlah transaksi)
        $pinjamanAktif = $activePeminjamans
            ->flatMap(fn($p) => $p->items->where('status', 'Dipinjam'))
            ->count();

        // Detail pinjaman aktif untuk kebutuhan perpanjang (renewal)
        $today = Carbon::today();
        $activeLoans = $activePeminjamans
            ->map(function ($p) use ($today) {
                $pinjamDate = Carbon::parse($p->tanggal_pinjam)->startOfDay();
                $dueDate    = Carbon::parse($p->tanggal_kembali)->startOfDay();
                $maxDueDate = $pinjamDate->copy()->addDays(self::LOAN_DAYS_MAX_TOTAL);

                // Bisa diperpanjang hanya jika transaksi masih Dipinjam.
                // (Tanpa check ini, transaksi yang sudah Kembali tetap bisa ditandai "Bisa diperpanjang" di UI.)
                $canRenew = ($p->status === 'Dipinjam')
                    && $dueDate->lt($maxDueDate)
                    && $today->lte($maxDueDate);

                return [
                    'id'            => $p->id,
                    'status'        => $p->status,
                    'tanggal_pinjam' => $pinjamDate->toDateString(),
                    'tanggal_kembali' => $dueDate->toDateString(),
                    'max_due_date'  => $maxDueDate->toDateString(),
                    'can_renew'     => $canRenew,
                ];
            })
            ->values();

        // Susun array borrowed untuk dropdown “Tandai Hilang”
        $borrowed = $activePeminjamans
            ->flatMap(fn($p) => $p->items
                ->where('status', 'Dipinjam')
                ->map(fn($i) => [
                    'id'    => $i->id,
                    'kode'  => $i->kode_barcode,
                    'judul' => $i->buku->judul_buku,
                ]))
            ->values();

        $remainingQuota = max(0, self::MAX_ACTIVE_ITEMS - $pinjamanAktif);

        // Return response
        return response()->json([
            'success' => true,
            'data'    => [
                // ID untuk proses transaksi
                'db_id'         => $anggota->id,

                // Identitas (sesuai kolom DB kamu)
                'id_anggota'    => $anggota->id_anggota,
                'nomor_induk'   => $anggota->nomor_induk,
                'nama'          => $anggota->nama_anggota,
                'tingkat'       => $anggota->tingkat,
                'jenis_kelamin' => $anggota->jenis_kelamin,
                'email'         => $anggota->email,
                'no_telepon'    => $anggota->no_telepon,
                'alamat'        => $anggota->alamat,
                'tanggal_lahir' => optional($anggota->tanggal_lahir)->format('Y-m-d'),
                'tanggal_registrasi' => optional($anggota->tanggal_registrasi)->format('Y-m-d'),
                'berlaku_hingga' => optional($anggota->berlaku_hingga)->format('Y-m-d'),
                'status'        => $anggota->status,

                // Foto
                'foto'          => $anggota->foto
                    ? asset('storage/' . $anggota->foto)
                    : asset('images/default-user.png'),

                // Data sirkulasi yang sudah kamu punya
                'pinjamanAktif' => $pinjamanAktif,
                'borrowed'      => $borrowed,
                'remainingQuota' => $remainingQuota,
                'maxActiveItems' => self::MAX_ACTIVE_ITEMS,

                // Data tambahan untuk fitur perpanjang
                'activeLoans'   => $activeLoans,
            ],
        ]);
    }
    public function cariBuku(Request $request)
    {
        // $satuItemBuku = \App\Models\ItemBuku::first();
        // dd($satuItemBuku);

        $request->validate(['kode' => 'required|string', 'mode' => 'required|in:pinjam,kembali']);
        $kodeEksemplar = $request->kode;
        $mode = $request->mode;

        if ($mode === 'pinjam') {
            $item = \App\Models\ItemBuku::with('buku')
                ->where('kode_barcode', $kodeEksemplar)
                ->first();
            if (!$item) {
                return response()->json(['success' => false, 'message' => 'Eksemplar buku tidak ditemukan.']);
            }
            if ($item->status !== 'Tersedia') {
                return response()->json(['success' => false, 'message' => 'Buku ini sedang tidak tersedia (status: ' . $item->status . ').']);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $item->id,
                    'judul' => $item->buku->judul_buku,
                    'kode' => $item->kode_barcode
                ]
            ]);
        }

        if ($mode === 'kembali') {
            $peminjaman = Peminjaman::whereHas(
                'items',
                fn($q) =>
                $q->where('kode_barcode', $kodeEksemplar)
            )->where('status', 'Dipinjam')
                ->with('anggota', 'items.buku')
                ->first();

            if (!$peminjaman)
                return response()->json([
                    'success' => false,
                    'message' => 'Buku ini tidak tercatat sedang dipinjam.'
                ]);
            if (! $peminjaman->anggota) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data peminjam tidak ditemukan. Hubungi admin.'
                ]);
            }
            $item = $peminjaman->items->firstWhere('kode_barcode', $kodeEksemplar);
            if (! $item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Eksemplar ini tidak sesuai data peminjaman.'
                ]);
            }
            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $item->id,
                    'judul' => $item->buku->judul_buku,
                    'kode' => $item->kode_barcode,
                    'peminjam' => $peminjaman->anggota->nama_anggota,
                ]
            ]);
        }
    }
    public function prosesPinjam(Request $request)
    {
        $data = $request->validate([
            'anggota_id' => 'required|integer|exists:anggotas,id',
            'item_ids'   => 'required|array|min:1',
            'item_ids.*' => 'exists:item_buku,id',
        ]);

        $anggotaId = $data['anggota_id'];
        $itemIds   = array_values(array_unique($data['item_ids']));

        DB::beginTransaction();
        try {
            // Lock item supaya aman dari race condition
            $items = ItemBuku::whereIn('id', $itemIds)->lockForUpdate()->get();
            if ($items->count() !== count($itemIds)) {
                DB::rollBack();
                return response()->json(['success' => false, 'message' => 'Ada item yang tidak valid.'], 422);
            }
            $notAvailable = $items->first(fn($i) => $i->status !== 'Tersedia');
            if ($notAvailable) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Ada buku yang tidak tersedia (status: ' . $notAvailable->status . ').'
                ], 422);
            }

            // Hitung jumlah item aktif (status item_buku = Dipinjam) milik anggota
            $activeCount = Peminjaman::where('anggota_id', $anggotaId)
                ->where('status', 'Dipinjam')
                ->withCount(['items as active_items_count' => function ($q) {
                    $q->where('item_buku.status', 'Dipinjam');
                }])
                ->get()
                ->sum('active_items_count');

            if ($activeCount + count($itemIds) > self::MAX_ACTIVE_ITEMS) {
                DB::rollBack();
                $sisa = max(0, self::MAX_ACTIVE_ITEMS - $activeCount);
                return response()->json([
                    'success' => false,
                    'message' => "Limit pinjam aktif " . self::MAX_ACTIVE_ITEMS . " eksemplar. Saat ini masih meminjam {$activeCount}. Sisa kuota: {$sisa}.",
                ], 422);
            }

            // Pakai tanggal tanpa jam supaya konsisten dengan kolom DATE di DB
            $today = Carbon::today();
            $peminjaman = Peminjaman::create([
                'anggota_id'      => $anggotaId,
                'tanggal_pinjam'  => $today,
                'tanggal_kembali' => $today->copy()->addDays(self::LOAN_DAYS_DEFAULT),
                'status'          => 'Dipinjam',
            ]);

            $peminjaman->items()->attach($itemIds);
            ItemBuku::whereIn('id', $itemIds)->update(['status' => 'Dipinjam']);

            DB::commit();
            return response()->json(['success' => true, 'message' => 'Peminjaman berhasil dicatat!']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Perpanjang peminjaman: +7 hari, tapi total maksimal 14 hari sejak tanggal_pinjam.
     * Endpoint: POST /sirkulasi/peminjaman/{id}/perpanjang
     */
    public function perpanjang(Request $request, $id)
    {
        $request->validate([
            // dikunci: hanya boleh +7 hari
            'days' => 'required|integer|in:7',
        ]);

        $days = (int) $request->days;

        return DB::transaction(function () use ($id, $days) {
            // Lock transaksi supaya aman dari klik dobel
            $peminjaman = Peminjaman::lockForUpdate()->findOrFail($id);

            if ($peminjaman->status !== 'Dipinjam') {
                return response()->json([
                    'success' => false,
                    'message' => 'Peminjaman sudah tidak aktif (bukan status Dipinjam).'
                ], 400);
            }

            $pinjamDate = Carbon::parse($peminjaman->tanggal_pinjam)->startOfDay();
            $oldDue     = Carbon::parse($peminjaman->tanggal_kembali)->startOfDay();
            $maxDue     = $pinjamDate->copy()->addDays(self::LOAN_DAYS_MAX_TOTAL);

            // Sudah mentok 14 hari total
            if ($oldDue->gte($maxDue)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak bisa diperpanjang lagi. Sudah mencapai batas maksimal 14 hari.'
                ], 422);
            }

            // Renewal dihitung dari due date lama (bukan dari hari ini)
            $newDue = $oldDue->copy()->addDays($days);

            // Kunci ke batas maksimal
            if ($newDue->gt($maxDue)) {
                $newDue = $maxDue;
            }

            $peminjaman->tanggal_kembali = $newDue->toDateString();
            $peminjaman->save();

            return response()->json([
                'success' => true,
                'message' => 'Perpanjangan berhasil.',
                'old_due_date' => $oldDue->toDateString(),
                'new_due_date' => $newDue->toDateString(),
                'max_due_date' => $maxDue->toDateString(),
            ]);
        });
    }
    public function prosesKembali(Request $request)
    {
        $data = $request->validate([
            'item_ids'   => 'required|array|min:1',
            'item_ids.*' => 'exists:item_buku,id',
            'kondisi'    => 'required|array',
            'kondisi.*'  => 'in:baik,rusak,hilang',
            'pengganti'  => 'array',
            'pengganti.*' => 'exists:item_buku,id',
        ]);
        $itemIds = $data['item_ids'];
        $kondisi = $data['kondisi']; // array [ item_id => 'baik'|'rusak' ]

        DB::beginTransaction();
        try {
            // Ambil transaksi yang memang masih punya item Dipinjam dari daftar itemIds
            $peminjamans = Peminjaman::whereHas('items', function ($q) use ($itemIds) {
                $q->whereIn('item_buku.id', $itemIds)
                    ->where('item_buku.status', 'Dipinjam');
            })
                ->with('items')
                ->get();

            if ($peminjamans->isEmpty()) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada item yang valid untuk dikembalikan (mungkin sudah kembali/rusak/hilang).'
                ], 422);
            }

            $now = Carbon::now();
            $maxLateDays = 0;

            foreach ($peminjamans as $p) {
                $due       = Carbon::parse($p->tanggal_kembali);
                $lateDays = 0;
                if ($now->gt($due)) {
                    // gunakan $due->diffInDays($now) untuk dapatkan selisih positif
                    $lateDays = $due->diffInDays($now);
                }
                $maxLateDays = max($maxLateDays, $lateDays);


                // 1) Update header
                $returned  = $p->items
                    ->whereIn('id', $itemIds)
                    ->pluck('id')
                    ->all();

                // Update pivot + status item per item
                foreach ($returned as $id) {
                    $k = $kondisi[$id] ?? 'baik';
                    $pivotData = ['kondisi' => $k, 'returned_at' => $now,];
                    $pgId = null;
                    if ($k === 'hilang') {
                        $pgId = $data['pengganti'][$id] ?? null;
                        $pivotData['pengganti_item_buku_id'] = $pgId;
                    }
                    $p->items()->updateExistingPivot($id, [
                        'kondisi' => $k,
                        'pengganti_item_buku_id' => $pgId,
                        // isi only if null (pakai raw)
                        'returned_at' => DB::raw('COALESCE(returned_at, "' . $now->toDateTimeString() . '")'),
                    ]);
                    $statusAwal = match ($k) {
                        'rusak'  => 'Rusak',
                        'hilang' => 'Hilang',
                        default  => 'Tersedia',
                    };
                    ItemBuku::where('id', $id)->update(['status' => $statusAwal]);
                    if ($k === 'hilang' && !empty($pgId)) {
                        ItemBuku::where('id', $pgId)->update(['status' => 'Tersedia']);
                    }
                }

                // Setelah status item diupdate, baru tentukan status transaksi
                $masihDipinjam = $p->items()
                    ->where('item_buku.status', 'Dipinjam')
                    ->exists();

                if (! $masihDipinjam) {
                    $p->update([
                        'status'                      => 'Kembali',
                        'tanggal_pengembalian_aktual' => $now,
                        'late_days'                   => $lateDays,
                    ]);
                } else {
                    // pastikan tetap Dipinjam kalau masih ada tanggungan
                    $p->update(['status' => 'Dipinjam']);
                }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => $maxLateDays
                    ? "Pengembalian sukses. Kamu telat {$maxLateDays} hari, tapi tanpa denda."
                    : "Pengembalian sukses tepat waktu.",
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}

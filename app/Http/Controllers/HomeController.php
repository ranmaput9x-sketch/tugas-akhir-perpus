<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Buku;
use App\Models\Anggota;
use App\Models\Peminjaman;


class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $now       = Carbon::now();
        $startYear = $now->copy()->startOfYear();
        $endYear   = $now->copy()->endOfYear();

        // 1) Kartu statistik
        $stats = [
            'total_buku'     => Buku::count(),
            'total_anggota'  => Anggota::count(),
            'pinjaman_aktif' => Peminjaman::where('status', 'Dipinjam')->count(),
        ];

        // 2) Tambahan statistik
        // Versi pivot kehilangan (lebih konsisten dgn proses "Hilang")
        $buku_hilang = DB::table('peminjaman_item_buku')
            ->where('kondisi', 'hilang')
            ->whereNull('pengganti_item_buku_id')
            ->count();
        // Atau jika kamu ingin dari status item:
        // $buku_hilang = Buku::whereHas('items', fn($q) => $q->where('status', 'Hilang'))->count();

        $pinjaman_bulan_ini = Peminjaman::whereBetween('created_at', [
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth(),
        ])->count();

        $pengembalian_bulan_ini = Peminjaman::whereNotNull('tanggal_pengembalian_aktual')
            ->whereBetween('tanggal_pengembalian_aktual', [
                $now->copy()->startOfMonth(),
                $now->copy()->endOfMonth(),
            ])->count();

        // 3) Label bulan (untuk chart)
        $bulan = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember',
        ];

        // 4) Data chart peminjaman per bulan (tahun berjalan)
        $pinjamData = Peminjaman::selectRaw('MONTH(created_at) as m, COUNT(*) as total')
            ->whereBetween('created_at', [$startYear, $endYear])
            ->groupBy('m')
            ->pluck('total', 'm')
            ->toArray();

        $kembaliData = Peminjaman::selectRaw('MONTH(tanggal_pengembalian_aktual) as m, COUNT(*) as total')
            ->whereNotNull('tanggal_pengembalian_aktual')
            ->whereBetween('tanggal_pengembalian_aktual', [$startYear, $endYear])
            ->groupBy('m')
            ->pluck('total', 'm')
            ->toArray();

        // Susun array 12 bulan (isi 0 kalau tidak ada data)
        $data_peminjaman   = [];
        $data_pengembalian = [];
        foreach (range(1, 12) as $m) {
            $data_peminjaman[]   = $pinjamData[$m]   ?? 0;
            $data_pengembalian[] = $kembaliData[$m] ?? 0;
        }

        // 5) Pinjaman terbaru (5 baris)
        $pinjaman_terbaru = DB::table('peminjaman as p')
            ->join('peminjaman_item_buku as pi', 'pi.peminjaman_id', '=', 'p.id')
            ->join('item_buku as i', 'i.id', '=', 'pi.item_buku_id')
            ->join('buku as b', 'b.id', '=', 'i.buku_id')
            ->join('anggotas as a', 'a.id', '=', 'p.anggota_id') // <-- kalau tabelmu 'anggotas', ganti jadi: join('anggotas as a', ...)
            ->orderBy('p.created_at', 'desc')
            ->limit(5)
            ->get([
                'p.created_at',
                'a.nama_anggota',
                'b.judul_buku',
            ]);

        // 6) Kirim ke view
        return view('home', compact(
            'stats',
            'buku_hilang',
            'pinjaman_bulan_ini',
            'pengembalian_bulan_ini',
            'bulan',
            'data_peminjaman',
            'data_pengembalian',
            'pinjaman_terbaru'
        ));
    }
}

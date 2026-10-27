<?php

namespace App\Http\Controllers;

use App\Models\Buku;
use App\Models\KomentarBuku;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KomentarBukuController extends Controller
{
    public function store(Request $request, Buku $buku)
    {
        $request->validate([
            'rec' => 'required|in:recommended,neutral,not',
            'aud' => 'nullable|in:kelas7,kelas8,kelas9,guru,pemula,menengah,lanjutan',
            'body' => 'required|string|min:30|max:800',
        ], [
            'body.min' => 'Ulasan minimal 30 karakter (jangan cuma “halo”).',
        ]);

        $body = trim($request->body);

        // Anti sampah (heuristic sederhana tapi efektif)
        $lower = mb_strtolower($body);
        $blocked = ['halo', 'hai', 'test', 'pertama', 'mantap', 'keren'];
        foreach ($blocked as $w) {
            if ($lower === $w || $lower === $w . '!' || $lower === $w . '.') {
                return back()->withErrors(['body' => 'Tulis ulasan yang beneran membantu (bukan sapaan doang).'])->withInput();
            }
        }

        $anggota = Auth::guard('anggota')->user();

        // 1 ulasan per user per buku -> update kalau sudah ada
        $komentar = KomentarBuku::firstOrNew([
            'buku_id' => $buku->id,
            'anggota_id' => $anggota->id,
        ]);

        $meta = $komentar->meta();

        $komentar->setMeta([
            'v' => 1,
            'rec' => $request->rec,
            'aud' => $request->aud,
            'body' => $body,
            // kalau ulasan baru: pending. kalau edit ulasan approved: balik pending biar aman.
            'status' => 'pending',
            'pinned' => (bool)($meta['pinned'] ?? false),
        ]);

        $komentar->save();

        return back()->with('success', 'Ulasan terkirim. Menunggu disetujui admin 🙂');
    }

    // (Opsional) Admin approve/reject/pin tanpa tambah kolom:
    public function moderate(Request $request, KomentarBuku $komentar)
    {
        // Kamu sesuaikan gate/role admin kamu di sini.
        // Contoh paling simpel: kalau punya middleware admin, taruh di route.
        $request->validate([
            'status' => 'nullable|in:approved,pending,rejected',
            'pinned' => 'nullable|boolean',
        ]);

        $meta = $komentar->meta();
        $meta['status'] = $request->status ?? ($meta['status'] ?? 'pending');
        $meta['pinned'] = $request->has('pinned') ? (bool)$request->pinned : (bool)($meta['pinned'] ?? false);

        $komentar->setMeta($meta);
        $komentar->save();

        return back()->with('success', 'Ulasan diperbarui.');
    }
    public function index()
    {
        // Ambil semua ulasan untuk dimoderasi
        $ulasan = \App\Models\KomentarBuku::with(['anggota', 'buku'])
            ->orderByDesc('created_at')
            ->get();

        // Pisah berdasarkan status yang disimpan di JSON isi_komentar
        $pending  = $ulasan->filter(fn($k) => $k->status() === 'pending');
        $approved = $ulasan->filter(fn($k) => $k->status() === 'approved');
        $rejected = $ulasan->filter(fn($k) => $k->status() === 'rejected');

        // Pin tampil paling atas di approved
        $approved = $approved->sortByDesc(fn($k) => $k->pinned() ? 1 : 0);

        return view('ulasan.index', compact('pending', 'approved', 'rejected'));
    }
}

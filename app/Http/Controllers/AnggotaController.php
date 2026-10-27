<?php

namespace App\Http\Controllers;

use App\Models\Anggota;
use Illuminate\Support\Facades\DB;
use App\Models\Peminjaman;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AnggotaController extends Controller
{
    /**
     * Menampilkan daftar semua anggota.
     */
    public function index(Request $request)
    {
        Anggota::where('status', 'aktif')
            ->whereDate('berlaku_hingga', '<', now())
            ->update(['status' => 'kadaluwarsa']);

        $query = Anggota::query();

        if ($request->filled('q')) {
            $q = $request->q;
            $query->where(function ($subQuery) use ($q) {
                $subQuery->where('id_anggota', 'like', "%$q%")
                    ->orWhere('nama_anggota', 'like', "%$q%");
            });
        }

        $anggotas = $query->latest()->get(); // Gunakan paginate untuk data yang banyak
        return view('anggota.index', compact('anggotas'));
    }

    public function create()
    {
        return view('anggota.create');
    }

    /**
     * Menyimpan anggota baru. Dibuat lebih efisien.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_anggota' => 'required|string|max:255|unique:anggotas,nama_anggota',
            'nomor_induk'  => 'required|string|unique:anggotas,nomor_induk',
            'password'     => 'required|min:6',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat'       => 'nullable|string',
            'foto'         => 'nullable|image|max:2048',
            'tingkat'      => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            $data = $request->except(['foto', 'password', 'email']); // Hapus email dari data request
            $data['password'] = bcrypt($request->password);
            $data['tanggal_registrasi'] = $request->tanggal_registrasi; // Ambil dari form

            // --- LOGIKA ID & EMAIL OTOMATIS ---
            $kodeTingkat = ($request->tingkat === 'Guru') ? 'G' : 'S';
            $tahun = date('y');
            $empatDigitTerakhir = substr($request->nomor_induk, -4);
            $nomorUrut = str_pad(Anggota::max('id') + 1, 4, '0', STR_PAD_LEFT);

            $data['id_anggota'] = "{$kodeTingkat}{$tahun}-{$empatDigitTerakhir}-{$nomorUrut}";
            $domain = ($request->tingkat === 'Guru') ? '@guru.perpus.com' : '@siswa.perpus.com';
            $data['email'] = strtolower($data['id_anggota']) . $domain;
            $data['barcode'] = $request->nomor_induk;
            // ------------------------------------
            // Aturan berlaku hingga
            if ($request->tingkat === 'Guru') {
                $data['berlaku_hingga'] = '9999-12-31';
            } else {
                $data['berlaku_hingga'] = now()->addYears(3);
            }
            // ---------------------------------------------

            if ($request->hasFile('foto')) {
                $data['foto'] = $request->file('foto')->store('anggota_foto', 'public');
            }
            // Simpan semua data sekaligus
            Anggota::create($data);
            DB::commit();

            return redirect()->route('anggota.index')
                ->with('success', "Anggota '{$request->nama_anggota}' dengan ID {$data['id_anggota']} berhasil disimpan!");
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withErrors('Gagal menyimpan data: ' . $e->getMessage())->withInput();
        }
    }

    public function cekDuplicate(Request $request)
    {
        // 1. Ambil ID anggota (jika sedang dalam mode edit) untuk diabaikan
        $idToIgnore = $request->id;

        // 2. Cek duplikat untuk nama
        $namaExists = Anggota::where('nama_anggota', $request->nama_anggota)
            ->when($idToIgnore, function ($query) use ($idToIgnore) {
                return $query->where('id', '!=', $idToIgnore);
            })
            ->exists();
        return response()->json([
            'nama_duplicate'  => $namaExists,
            'email_duplicate' => false // Set ke false karena email tidak diinput manual
        ]);
    }


    public function show(Anggota $anggota)
    {
        // eager‑load peminjaman dan tiap item buku
        $anggota->load([
            'peminjaman' => fn($q) => $q
                ->with(['items.buku', 'items.rak'])
                ->orderByDesc('tanggal_pinjam'),
        ]);

        return view('anggota.show', compact('anggota'));
    }


    /**
     * Menampilkan form edit anggota.
     */
    public function edit(Anggota $anggota)
    {
        // Tidak perlu mengirim data lain selain $anggota
        return view('anggota.edit', compact('anggota'));
    }


    public function update(Request $request, Anggota $anggota)
    {
        $request->validate([
            'nama_anggota' => ['required', 'string', 'max:255', Rule::unique('anggotas')->ignore($anggota->id)],
            'email'        => ['required', 'email', Rule::unique('anggotas')->ignore($anggota->id)],
            'status'       => 'required|in:aktif,non-aktif,alumni',
            // Tambahkan validasi lain jika diperlukan
        ]);

        $updateData = $request->except(['foto', 'password']);

        // Jika ada password baru, hash dan update
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        // Handle update foto
        if ($request->hasFile('foto')) {
            // Hapus foto lama
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }
            $updateData['foto'] = $request->file('foto')->store('anggota_foto', 'public');
        }

        $anggota->update($updateData);

        return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil diupdate!');
    }

    /**
     * Menghapus data anggota, termasuk file fotonya.
     */
    public function destroy(Anggota $anggota) // Gunakan Route-Model Binding
    {
        try {
            // Hapus file foto dari storage jika ada
            if ($anggota->foto) {
                Storage::disk('public')->delete($anggota->foto);
            }

            // Hapus data dari database
            $anggota->delete();

            return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil dihapus.');
        } catch (\Throwable $e) {
            return back()->withErrors('Gagal menghapus data: ' . $e->getMessage());
        }
    }

    public function search(Request $request)
    {
        $query = $request->input('q');
        $anggotas = Anggota::where('id_anggota', 'LIKE', "%$query%")
            ->orWhere('nama_anggota', 'LIKE', "%$query%")
            ->orWhere('alamat', 'LIKE', "%$query%")
            ->get();

        return response()->json([
            'html' => view('anggota.partials.table', compact('anggotas'))->render()
        ]);
    }

    public function bulkDelete(Request $request)

    {
        $ids = $request->input('ids');

        if ($ids && count($ids)) {
            Anggota::whereIn('id', $ids)->delete();

            return redirect()->route('anggota.index')->with('success', 'Data anggota berhasil dihapus.');
        }

        return redirect()->back()->with('error', 'Tidak ada data yang dipilih.');
    }
    public function bulkAction(Request $request)
    {
        $request->validate([
            'action' => 'required|in:delete,deactivate,activate',
            'selected_ids' => 'required|array',
        ]);

        $ids = $request->input('selected_ids');
        $action = $request->input('action');
        $count = count($ids);

        switch ($action) {
            case 'delete':
                // Hapus juga foto dari storage sebelum hapus data
                $anggotas = Anggota::whereIn('id', $ids)->get();
                foreach ($anggotas as $anggota) {
                    if ($anggota->foto) {
                        Storage::disk('public')->delete($anggota->foto);
                    }
                }
                Anggota::whereIn('id', $ids)->delete();
                return redirect()->route('anggota.index')->with('success', "Berhasil menghapus {$count} data anggota.");

            case 'deactivate':
                Anggota::whereIn('id', $ids)->update(['status' => 'non-aktif']);
                return redirect()->route('anggota.index')->with('success', "Berhasil menonaktifkan {$count} anggota.");

            case 'activate':
                Anggota::whereIn('id', $ids)->update(['status' => 'aktif']);
                return redirect()->route('anggota.index')->with('success', "Berhasil mengaktifkan {$count} anggota.");
        }

        return redirect()->route('anggota.index')->with('error', 'Aksi tidak valid.');
    }

    // public function cetakKta(Anggota $anggota)
    // {
    //     // Pastikan barcode untuk anggota ini sudah dibuat
    //     $anggota->generateBarcodeImage();

    //     // Tampilkan view kta.blade.php dengan data anggota
    //     return view('anggota.kta', compact('anggota'));
    // }
    public function showFormCetakKtaMassal(Request $request)
    {
        // Memulai query builder
        $query = Anggota::query();

        // Terapkan filter berdasarkan tingkat jika ada di URL
        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        // Ambil data setelah difilter, urutkan dari yang terbaru
        $anggotas = $query->latest()->get();

        // Tampilkan view dengan data yang sudah difilter
        return view('anggota.form-cetak-kta-massal', compact('anggotas'));
    }
    public function cetakKtaMassal(Request $request)
    {
        $ids = $request->input('selected_ids');

        if (empty($ids)) {
            return redirect()->back()->with('error', 'Tidak ada anggota yang dipilih.');
        }

        $anggotas = Anggota::whereIn('id', $ids)->get();

        // Pastikan semua barcode ada sebelum ditampilkan
        foreach ($anggotas as $anggota) {
            $anggota->generateBarcodeImage();
        }

        return view('anggota.kta-massal', compact('anggotas'));
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Models\Anggota;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class AnggotaApiController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum'); // 🔐 Middleware token API
    }

    // 🔍 GET /api/anggota?nama=indah&tingkat=siswa
    public function index(Request $request)
    {
        $query = Anggota::query();

        if ($request->filled('nama')) {
            $query->where('nama_anggota', 'like', '%' . $request->nama . '%');
        }

        if ($request->filled('tingkat')) {
            $query->where('tingkat', $request->tingkat);
        }

        return response()->json($query->get());
    }

    // ✅ Validasi & Simpan
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id_anggota' => 'required|unique:anggotas',
            'nama_anggota' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'tanggal_registrasi' => 'required|date',
            'berlaku_hingga' => 'required|date|after_or_equal:tanggal_registrasi',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'status' => 'in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $anggota = Anggota::create($validator->validated());
        return response()->json($anggota, 201);
    }

    public function show(Anggota $anggota)
    {
        return response()->json($anggota);
    }

    public function update(Request $request, Anggota $anggota)
    {
        $validator = Validator::make($request->all(), [
            'nama_anggota' => 'sometimes|string|max:255',
            'tanggal_lahir' => 'sometimes|date',
            'tanggal_registrasi' => 'sometimes|date',
            'berlaku_hingga' => 'sometimes|date|after_or_equal:tanggal_registrasi',
            'jenis_kelamin' => 'sometimes|in:Laki-laki,Perempuan',
            'status' => 'sometimes|in:aktif,nonaktif',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $anggota->update($validator->validated());
        return response()->json($anggota);
    }

    public function destroy(Anggota $anggota)
    {
        $anggota->delete();
        return response()->json(['message' => 'Anggota dihapus'], 204);
    }
}

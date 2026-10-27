<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Penulis;
use App\Models\Penerbit;
use App\Models\Kategori;

class SelectController extends Controller
{
    public function penulis(Request $request)
    {
        $search = $request->input('q');
        $data = Penulis::where('nama_penulis', 'LIKE', "%{$search}%")
            ->get(['id', 'nama_penulis as text']);
        return response()->json($data);
    }

    public function penerbit(Request $request)
    {
        $search = $request->input('q');
        $data = Penerbit::where('nama_penerbit', 'LIKE', "%{$search}%")
            ->get(['id', 'nama_penerbit as text']);
        return response()->json($data);
    }

    public function kategori(Request $request)
    {
        $search = $request->input('q');
        $data = Kategori::where('nama_kategori', 'LIKE', "%{$search}%")
            ->get(['id', 'nama_kategori as text']);
        return response()->json($data);
    }
}

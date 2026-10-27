<?php

namespace App\Http\Controllers\Api;

use App\Models\Buku;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BukuApiController extends Controller
{
    public function index()
    {
        return response()->json(Buku::all());
    }

    public function store(Request $request)
    {
        $buku = Buku::create($request->all());
        return response()->json($buku, 201);
    }

    public function show(Buku $buku)
    {
        return response()->json($buku);
    }

    public function update(Request $request, Buku $buku)
    {
        $buku->update($request->all());
        return response()->json($buku);
    }

    public function destroy(Buku $buku)
    {
        $buku->delete();
        return response()->json(null, 204);
    }
}

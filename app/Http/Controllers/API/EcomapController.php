<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Ecomap;
use Illuminate\Http\Request;

class EcomapController extends Controller
{
    // Get All Ecomaps (Public) - dengan relasi desa
    public function index()
    {
        $ecomaps = Ecomap::with('desa')->orderBy('id', 'desc')->get();
        return response()->json($ecomaps);
    }

    // Get Single Ecomap (Public)
    public function show($id)
    {
        $ecomap = Ecomap::with('desa')->find($id);

        if (!$ecomap) {
            return response()->json(['message' => 'Ecomap tidak ditemukan'], 404);
        }

        return response()->json($ecomap);
    }

    // Create Ecomap (Fasilitator Only)
    public function store(Request $request)
    {
        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tanggal' => 'required|date',
            'jumlah_organik' => 'required|numeric|min:0',
            'jumlah_anorganik' => 'required|numeric|min:0',
            'jumlah_residu' => 'required|numeric|min:0',
            'pengelolaan_organik' => 'required|string',
            'pengelolaan_anorganik' => 'required|string',
            'pengelolaan_residu' => 'required|string',
        ]);

        $ecomap = Ecomap::create($request->all());

        return response()->json([
            'message' => 'Data Ecomap berhasil ditambahkan',
            'data' => $ecomap->load('desa')
        ], 201);
    }

    // Update Ecomap (Fasilitator Only)
    public function update(Request $request, $id)
    {
        $ecomap = Ecomap::find($id);

        if (!$ecomap) {
            return response()->json(['message' => 'Ecomap tidak ditemukan'], 404);
        }

        $request->validate([
            'desa_id' => 'required|exists:desas,id',
            'tanggal' => 'required|date',
            'jumlah_organik' => 'required|numeric|min:0',
            'jumlah_anorganik' => 'required|numeric|min:0',
            'jumlah_residu' => 'required|numeric|min:0',
            'pengelolaan_organik' => 'required|string',
            'pengelolaan_anorganik' => 'required|string',
            'pengelolaan_residu' => 'required|string',
        ]);

        $ecomap->update($request->all());

        return response()->json([
            'message' => 'Data Ecomap berhasil diupdate',
            'data' => $ecomap->load('desa')
        ]);
    }

    // Delete Ecomap (Fasilitator Only)
    public function destroy($id)
    {
        $ecomap = Ecomap::find($id);

        if (!$ecomap) {
            return response()->json(['message' => 'Ecomap tidak ditemukan'], 404);
        }

        $ecomap->delete();

        return response()->json([
            'message' => 'Data Ecomap berhasil dihapus'
        ]);
    }
}

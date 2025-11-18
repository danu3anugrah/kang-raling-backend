<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Desa;
use Illuminate\Http\Request;

class DesaController extends Controller
{
    // Get All Desa (Public)
    public function index()
    {
        $desas = Desa::orderBy('id', 'asc')->get();
        return response()->json($desas);
    }

    // Get Single Desa (Public)
    public function show($id)
    {
        $desa = Desa::find($id);

        if (!$desa) {
            return response()->json(['message' => 'Desa tidak ditemukan'], 404);
        }

        return response()->json($desa);
    }

    // Create Desa (Fasilitator Only)
    public function store(Request $request)
    {
        try {
            $request->validate([
                'nama_desa' => 'required|string|max:255',
            ]);

            $desa = Desa::create([
                'nama_desa' => $request->nama_desa,
            ]);

            return response()->json([
                'message' => 'Desa berhasil ditambahkan',
                'data' => $desa
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan desa',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update Desa (Fasilitator Only)
    public function update(Request $request, $id)
    {
        $desa = Desa::find($id);

        if (!$desa) {
            return response()->json(['message' => 'Desa tidak ditemukan'], 404);
        }

        $request->validate([
            'nama_desa' => 'required|string|max:255',
        ]);

        $desa->nama_desa = $request->nama_desa;
        $desa->save();

        return response()->json([
            'message' => 'Desa berhasil diupdate',
            'data' => $desa
        ]);
    }

    // Delete Desa (Fasilitator Only)
    public function destroy($id)
    {
        $desa = Desa::find($id);

        if (!$desa) {
            return response()->json(['message' => 'Desa tidak ditemukan'], 404);
        }

        $desa->delete();

        return response()->json([
            'message' => 'Desa berhasil dihapus'
        ]);
    }
}

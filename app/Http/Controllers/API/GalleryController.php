<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;

class GalleryController extends Controller
{
    // Get All Galleries (Public)
    public function index()
    {
        $galleries = Gallery::orderBy('created_at', 'desc')->get();
        return response()->json($galleries);
    }

    // Get Single Gallery (Public)
    public function show($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery tidak ditemukan'], 404);
        }

        return response()->json($gallery);
    }

    // Create Gallery (Fasilitator Only)
    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif|max:10240',
            'keterangan' => 'nullable|string',
        ]);

        // Upload gambar
        $gambar = $request->file('gambar');
        $gambarName = time() . '_' . $gambar->getClientOriginalName();
        $gambar->move(public_path('uploads/galleries'), $gambarName);

        $gallery = Gallery::create([
            'gambar' => 'uploads/galleries/' . $gambarName,
            'keterangan' => $request->keterangan,
        ]);

        return response()->json([
            'message' => 'Gallery berhasil ditambahkan',
            'data' => $gallery
        ], 201);
    }

    // Update Gallery (Fasilitator Only)
    public function update(Request $request, $id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery tidak ditemukan'], 404);
        }

        $request->validate([
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'keterangan' => 'nullable|string',
        ]);

        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if (file_exists(public_path($gallery->gambar))) {
                unlink(public_path($gallery->gambar));
            }

            // Upload gambar baru
            $gambar = $request->file('gambar');
            $gambarName = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('uploads/galleries'), $gambarName);
            $gallery->gambar = 'uploads/galleries/' . $gambarName;
        }

        $gallery->keterangan = $request->keterangan;
        $gallery->save();

        return response()->json([
            'message' => 'Gallery berhasil diupdate',
            'data' => $gallery
        ]);
    }

    // Delete Gallery (Fasilitator Only)
    public function destroy($id)
    {
        $gallery = Gallery::find($id);

        if (!$gallery) {
            return response()->json(['message' => 'Gallery tidak ditemukan'], 404);
        }

        // Hapus gambar
        if (file_exists(public_path($gallery->gambar))) {
            unlink(public_path($gallery->gambar));
        }

        $gallery->delete();

        return response()->json([
            'message' => 'Gallery berhasil dihapus'
        ]);
    }
}

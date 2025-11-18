<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BlogController extends Controller
{
    // Get All Blogs (Public)
    public function index()
    {
        $blogs = Blog::orderBy('created_at', 'desc')->get();
        return response()->json($blogs);
    }

    // Get Single Blog (Public)
    public function show($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog tidak ditemukan'], 404);
        }

        return response()->json($blog);
    }

    // Create Blog (Fasilitator Only)
    public function store(Request $request)
    {
        $request->validate([
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'judul' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:255',
            'isi_berita' => 'required|string',
        ]);

        // Upload gambar (jika ada)
        $gambarPath = null;
        if ($request->hasFile('gambar')) {
            $gambar = $request->file('gambar');
            $gambarName = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('uploads/blogs'), $gambarName);
            $gambarPath = 'uploads/blogs/' . $gambarName;
        }

        $blog = Blog::create([
            'gambar' => $gambarPath ?? 'uploads/blogs/default.jpg',
            'judul' => $request->judul,
            'nama_penulis' => $request->nama_penulis,
            'isi_berita' => $request->isi_berita,
        ]);

        return response()->json([
            'message' => 'Blog berhasil ditambahkan',
            'data' => $blog
        ], 201);
    }

    // Update Blog (Fasilitator Only)
    public function update(Request $request, $id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog tidak ditemukan'], 404);
        }

        $request->validate([
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10240',
            'judul' => 'required|string|max:255',
            'nama_penulis' => 'required|string|max:255',
            'isi_berita' => 'required|string',
        ]);

        // Jika ada gambar baru
        if ($request->hasFile('gambar')) {
            // Hapus gambar lama
            if (file_exists(public_path($blog->gambar))) {
                unlink(public_path($blog->gambar));
            }

            // Upload gambar baru
            $gambar = $request->file('gambar');
            $gambarName = time() . '_' . $gambar->getClientOriginalName();
            $gambar->move(public_path('uploads/blogs'), $gambarName);
            $blog->gambar = 'uploads/blogs/' . $gambarName;
        }

        $blog->judul = $request->judul;
        $blog->nama_penulis = $request->nama_penulis;
        $blog->isi_berita = $request->isi_berita;
        $blog->save();

        return response()->json([
            'message' => 'Blog berhasil diupdate',
            'data' => $blog
        ]);
    }

    // Delete Blog (Fasilitator Only)
    public function destroy($id)
    {
        $blog = Blog::find($id);

        if (!$blog) {
            return response()->json(['message' => 'Blog tidak ditemukan'], 404);
        }

        // Hapus gambar
        if (file_exists(public_path($blog->gambar))) {
            unlink(public_path($blog->gambar));
        }

        $blog->delete();

        return response()->json([
            'message' => 'Blog berhasil dihapus'
        ]);
    }
}

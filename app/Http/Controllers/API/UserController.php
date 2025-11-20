<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // Get All Users (Admin only)
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return response()->json($users);
    }

    // Get Single User (Admin only)
    public function show($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        return response()->json($user);
    }

    // Create User (Admin only)
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'role' => 'required|in:admin,fasilitator',
                'is_active' => 'boolean',
            ]);

            $user = User::create([
                'name' => $validatedData['name'],
                'email' => $validatedData['email'],
                'password' => Hash::make($validatedData['password']),
                'role' => $validatedData['role'],
                'is_active' => $validatedData['is_active'] ?? true,
            ]);

            return response()->json([
                'message' => 'User berhasil ditambahkan',
                'data' => $user
            ], 201);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal menambahkan user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update User (Admin only)
    public function update(Request $request, $id)
    {
        try {
            $user = User::find($id);

            if (!$user) {
                return response()->json(['message' => 'User tidak ditemukan'], 404);
            }

            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . $id,
                'password' => 'nullable|string|min:8',
                'role' => 'required|in:admin,fasilitator',
                'is_active' => 'boolean',
            ]);

            $user->name = $validatedData['name'];
            $user->email = $validatedData['email'];
            $user->role = $validatedData['role'];
            $user->is_active = $validatedData['is_active'] ?? true;

            // Only update password if provided
            if (!empty($validatedData['password'])) {
                $user->password = Hash::make($validatedData['password']);
            }

            $user->save();

            return response()->json([
                'message' => 'User berhasil diupdate',
                'data' => $user
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengupdate user',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Delete User (Admin only)
    public function destroy($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        // Prevent deleting self
        if ($user->id === auth()->id()) {
            return response()->json(['message' => 'Tidak bisa menghapus akun sendiri'], 403);
        }

        $user->delete();

        return response()->json([
            'message' => 'User berhasil dihapus'
        ]);
    }

    // Toggle Active Status (Admin only)
    public function toggleActive($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        return response()->json([
            'message' => 'Status user berhasil diubah',
            'data' => $user
        ]);
    }

    // Reset Password (Admin only)
    public function resetPassword(Request $request, $id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 404);
        }

        $request->validate([
            'new_password' => 'required|string|min:8',
        ]);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'message' => 'Password user berhasil direset'
        ]);
    }

    // 👇 TAMBAHKAN 2 METHOD BARU INI

    // Update Profile Sendiri (untuk semua user yang login)
    public function updateProfile(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'name' => 'required|string|max:255',
                'email' => 'required|email|unique:users,email,' . auth()->id(),
            ]);

            $user = User::findOrFail(auth()->id());
            $user->update($validatedData);

            return response()->json([
                'message' => 'Profil berhasil diperbarui',
                'data' => $user->fresh()
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal memperbarui profil',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // Update Password Sendiri (untuk semua user yang login)
    public function updatePassword(Request $request)
    {
        try {
            $request->validate([
                'new_password' => 'required|string|min:8',
            ]);

            $user = User::findOrFail(auth()->id());
            $user->update([
                'password' => Hash::make($request->new_password)
            ]);

            return response()->json([
                'message' => 'Password berhasil diubah'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Gagal mengubah password',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}

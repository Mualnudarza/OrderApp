<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ManajemenAksesController extends Controller
{
    /**
     * Menampilkan daftar pengguna untuk manajemen peran.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function index()
    {
        // Hanya tampilkan pengguna yang bukan 'master' untuk diubah perannya
        // Master tidak bisa mengubah peran master lain atau dirinya sendiri melalui UI ini
        $users = User::where('id', '!=', Auth::id())
                     ->whereIn('role', ['admin', 'kasir']) // Hanya tampilkan admin dan kasir
                     ->get();

        return view('manajemen_akses', compact('users'));
    }

    /**
     * Memperbarui peran pengguna.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function updateRole(Request $request, User $user)
    {
        // Validasi bahwa peran baru adalah 'admin' atau 'kasir'
        $request->validate([
            'role' => 'required|in:admin,kasir',
        ]);

        // Master tidak bisa mengubah peran dirinya sendiri atau peran 'master' lainnya
        if ($user->id === Auth::id() || $user->isMaster()) {
            return back()->with('error', 'Anda tidak bisa mengubah peran Anda sendiri atau peran Master lainnya.');
        }

        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Peran pengguna berhasil diperbarui!');
    }
}
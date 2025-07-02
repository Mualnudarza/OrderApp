<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash; // Import Hash facade

class ManajemenAksesController extends Controller
{
    /**
     * Menampilkan daftar pengguna untuk manajemen peran.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function index()
    {
        // Tampilkan semua pengguna kecuali pengguna yang sedang login (agar tidak bisa mengubah diri sendiri)
        // dan tidak menampilkan peran 'master' lainnya untuk diubah melalui UI ini
        $users = User::where('id', '!=', Auth::id())
                     ->whereIn('role', ['admin', 'kasir']) // Hanya tampilkan admin dan kasir untuk diubah
                     ->get();

        return view('manajemen_akses', compact('users'));
    }

    /**
     * Menyimpan pengguna baru ke database.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:admin,kasir,master', // Izinkan master membuat admin/kasir/master
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan!');
    }

    /**
     * Memperbarui peran pengguna.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function updateRole(Request $request, User $user)
    {
        // Validasi bahwa peran baru adalah 'admin' atau 'kasir' atau 'master'
        $request->validate([
            'role' => 'required|in:admin,kasir,master',
        ]);

        // Master tidak bisa mengubah peran dirinya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa mengubah peran Anda sendiri.');
        }
        
        // Master tidak bisa mengubah peran Master lain menjadi peran yang lebih rendah (misal: admin/kasir)
        // Tetapi bisa mengubah peran master lain menjadi master juga
        if ($user->isMaster() && $request->role !== 'master') {
             return back()->with('error', 'Anda tidak bisa mengubah peran Master lain menjadi peran yang lebih rendah.');
        }


        $user->role = $request->role;
        $user->save();

        return back()->with('success', 'Peran pengguna berhasil diperbarui!');
    }

    /**
     * Menghapus pengguna dari database.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function destroy(User $user)
    {
        // Master tidak bisa menghapus akunnya sendiri
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        // Master tidak bisa menghapus akun Master lainnya
        if ($user->isMaster()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Master lainnya.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}
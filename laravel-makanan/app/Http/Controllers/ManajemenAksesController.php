<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\RoleHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon; // Import Carbon for date handling

class ManajemenAksesController extends Controller
{
    /**
     * Menampilkan daftar pengguna untuk manajemen peran.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function index()
    {
        // Tampilkan semua pengguna kecuali pengguna yang sedang login (agar tidak bisa mengubah diri sendiri)
        $users = User::where('id', '!=', Auth::id())
                     ->get();

        // Mengambil semua histori peran untuk ditampilkan secara keseluruhan
        // Menggunakan with(['user', 'changedBy']) untuk memuat relasi
        $allRoleHistories = RoleHistory::with(['user', 'changedBy'])
                                       ->orderBy('changed_at', 'desc') // Urutkan dari yang terbaru
                                       ->get();

        return view('manajemen_akses', compact('users', 'allRoleHistories'));
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
            'role' => 'required|in:admin,kasir,master',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
        ]);

        // Rekam histori saat pengguna baru dibuat
        RoleHistory::create([
            'user_id' => $user->id,
            'old_role' => null, // Peran lama null karena ini pengguna baru
            'new_role' => $request->role,
            'changed_by_user_id' => Auth::id(),
            'changed_at' => now(),
        ]);

        return back()->with('success', 'Pengguna baru berhasil ditambahkan!')->with('form_type', 'add_user');
    }

    /**
     * Memperbarui peran pengguna.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function updateRole(Request $request, User $user)
    {
        $request->validate([
            'role' => 'required|in:admin,kasir,master',
        ]);

        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa mengubah peran Anda sendiri.');
        }

        if ($user->isMaster() && $request->role !== 'master') {
            return back()->with('error', 'Anda tidak bisa mengubah peran Master lain menjadi peran yang lebih rendah.');
        }

        $oldRole = $user->role;
        $newRole = $request->role;

        if ($oldRole !== $newRole) {
            $user->role = $newRole;
            $user->save();

            RoleHistory::create([
                'user_id' => $user->id,
                'old_role' => $oldRole,
                'new_role' => $newRole,
                'changed_by_user_id' => Auth::id(),
                'changed_at' => now(),
            ]);

            return back()->with('success', 'Peran pengguna berhasil diperbarui!');
        }

        return back()->with('info', 'Tidak ada perubahan peran yang terdeteksi.');
    }

    /**
     * Menghapus pengguna dari database.
     * Hanya bisa diakses oleh peran 'master'.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Anda sendiri.');
        }

        if ($user->isMaster()) {
            return back()->with('error', 'Anda tidak bisa menghapus akun Master lainnya.');
        }

        $user->delete();

        return back()->with('success', 'Pengguna berhasil dihapus!');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Import Rule untuk validasi unik saat update

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        // Validasi: 'nama' wajib diisi dan harus unik di tabel 'kategoris'
        $request->validate([
            'nama' => 'required|unique:kategoris,nama',
        ], [
            'nama.unique' => 'Nama kategori ini sudah ada. Mohon gunakan nama lain.',
            'nama.required' => 'Nama kategori tidak boleh kosong.',
        ]);

        Kategori::create(['nama' => $request->nama]);
        return redirect('/kategori')->with('success', 'Kategori berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategoris = Kategori::all();
        return view('kategori', compact('kategori', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::findOrFail($id);

        // Validasi: 'nama' wajib diisi dan harus unik di tabel 'kategoris',
        // KECUALI untuk kategori yang sedang diupdate (diidentifikasi dengan ID-nya).
        $request->validate([
            'nama' => [
                'required',
                Rule::unique('kategoris', 'nama')->ignore($kategori->id),
            ],
        ], [
            'nama.unique' => 'Nama kategori ini sudah ada. Mohon gunakan nama lain.',
            'nama.required' => 'Nama kategori tidak boleh kosong.',
        ]);

        $kategori->update(['nama' => $request->nama]);
        return redirect('/kategori')->with('success', 'Kategori diperbarui!');
    }

    public function destroy($id)
    {
        $kategori = Kategori::findOrFail($id);
        $kategori->delete();
        return redirect('/kategori')->with('success', 'Kategori dihapus!');
    }
}

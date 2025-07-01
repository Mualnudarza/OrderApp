<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;

class KategoriController extends Controller
{
    public function index()
    {
        $kategoris = Kategori::all();
        return view('kategori', compact('kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate(['nama' => 'required']);
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
        $request->validate(['nama' => 'required']);
        $kategori = Kategori::findOrFail($id);
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

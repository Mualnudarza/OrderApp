<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('menu', compact('menus', 'kategoris'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        Menu::create([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id
        ]);

        
        return redirect('/menu')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function filterByKategori($id)
    {
        $menus = Menu::with('kategori')->where('kategori_id', $id)->get();
        $kategoris = Kategori::all();
        $kategoriAktif = Kategori::findOrFail($id);

        return view('menu', compact('menus', 'kategoris', 'kategoriAktif'));
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $menus = Menu::with('kategori')->get();
        $kategoris = Kategori::all();
        return view('menu', compact('menu', 'menus', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'harga' => 'required|integer',
            'kategori_id' => 'required|exists:kategoris,id'
        ]);

        $menu = Menu::findOrFail($id);
        $menu->update([
            'nama' => $request->nama,
            'harga' => $request->harga,
            'deskripsi' => $request->deskripsi,
            'kategori_id' => $request->kategori_id
        ]);

        return redirect('/menu')->with('success', 'Menu diperbarui!');
    }

    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);
        $menu->delete();
        return redirect('/menu')->with('success', 'Menu dihapus!');
    }
}

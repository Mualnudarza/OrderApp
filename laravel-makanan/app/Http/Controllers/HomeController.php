<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Kategori; // Pastikan ini ada
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $menus = Menu::with('kategori')->get();
        $kategoris = Kategori::all(); // Pastikan ini ada untuk mengambil semua kategori
        return view('home', compact('menus', 'kategoris')); // Pastikan 'kategoris' dikirim ke view
    }
}
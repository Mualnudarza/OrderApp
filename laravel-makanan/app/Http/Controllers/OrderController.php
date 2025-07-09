<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Menu; // Abstraksi: Mengimpor model Menu untuk berinteraksi dengan data menu
use App\Models\Order; // Abstraksi: Mengimpor model Order untuk berinteraksi dengan data pesanan
use App\Models\OrderItem; // Abstraksi: Mengimpor model OrderItem untuk berinteraksi dengan item pesanan
use App\Models\Kategori; // Abstraksi: Mengimpor model Kategori untuk berinteraksi dengan data kategori
use Illuminate\Http\Request; // Abstraksi: Mengimpor objek Request untuk menangani input HTTP
use Illuminate\Support\Facades\DB; // Abstraksi: Mengimpor Facade DB untuk operasi database tingkat rendah (transaksi)
use Carbon\Carbon; // Abstraksi: Mengimpor Carbon untuk manipulasi tanggal dan waktu

/**
 * Class OrderController
 * @package App\Http\Controllers
 *
 * Enkapsulasi: Kelas ini mengemas semua logika bisnis yang berkaitan dengan pengelolaan pesanan,
 * termasuk menampilkan menu, membuat pesanan baru, melihat laporan pesanan aktif, melihat histori pesanan,
 * mencetak rekap laporan, dan memperbarui status pesanan.
 * Pewarisan: OrderController mewarisi dari App\Http\Controllers\Controller, yang memberikan akses
 * ke fungsionalitas dasar controller Laravel seperti validasi dan helper respons.
 * Abstraksi: Controller ini berinteraksi dengan Model (Menu, Order, OrderItem, Kategori) dan Facade (DB, Request)
 * tanpa perlu tahu detail implementasi internal mereka.
 */
class OrderController extends Controller
{
    /**
     * Menampilkan daftar menu yang tersedia untuk pemesanan.
     * Fungsi: Mengambil semua menu dan kategori dari database untuk ditampilkan kepada pengguna.
     * Abstraksi: Menggunakan model Menu dan Kategori untuk mengambil data.
     */
    public function index()
    {
        $menus = Menu::with('kategori')->get(); // Ambil semua menu beserta kategorinya
        $kategoris = Kategori::all(); // Ambil semua kategori
        return view('order', compact('menus', 'kategoris'));
    }

    /**
     * Menyimpan pesanan baru ke database.
     * Fungsi: Memproses data pesanan dari form, termasuk nama pemesan, nomor meja,
     * dan daftar item menu yang dipesan beserta kuantitasnya.
     * Enkapsulasi: Menggunakan transaksi database untuk memastikan semua operasi (membuat pesanan dan item pesanan)
     * berhasil secara atomik. Jika ada kegagalan, semua perubahan akan dibatalkan (rollback).
     * Polymorphism: Metode `create()` pada model `Order` dan `OrderItem` akan melakukan operasi INSERT.
     */
    public function store(Request $request)
    {
        // Abstraksi: Validasi input dari request.
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'meja_nomor' => 'nullable|string|max:255',
            'menu_ids' => 'required|array',
            'menu_ids.*' => 'exists:menus,id', // Pastikan setiap ID menu ada di tabel menus
            'quantities' => 'required|array',
            'quantities.*' => 'integer|min:1', // Pastikan setiap kuantitas adalah integer positif
        ]);

        // Enkapsulasi: Memulai transaksi database.
        DB::beginTransaction();

        try {
            // Buat pesanan baru
            $order = Order::create([
                'nama_pemesan' => $request->nama_pemesan,
                'meja_nomor' => $request->meja_nomor,
                'status' => 'pending', // Status awal pesanan
                'total_harga' => 0, // Akan dihitung setelah item ditambahkan
            ]);

            $totalHarga = 0;
            // Iterasi melalui menu yang dipesan dan tambahkan ke order_items
            foreach ($request->menu_ids as $index => $menuId) {
                $menu = Menu::find($menuId);
                $quantity = $request->quantities[$index];

                if ($menu && $quantity > 0) {
                    OrderItem::create([
                        'order_id' => $order->id,
                        'menu_id' => $menu->id,
                        'quantity' => $quantity,
                        'price' => $menu->harga,
                        'subtotal' => $menu->harga * $quantity,
                    ]);
                    $totalHarga += ($menu->harga * $quantity);
                }
            }

            // Perbarui total harga pesanan
            $order->total_harga = $totalHarga;
            $order->save(); // Polymorphism: Menyimpan perubahan pada objek Order.

            // Enkapsulasi: Commit transaksi jika semua operasi berhasil.
            DB::commit();

            // Redirect ke halaman laporan pesanan yang sedang diproses
            return redirect()->route('laporanpesanan.list')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            // Enkapsulasi: Rollback transaksi jika terjadi kesalahan.
            DB::rollBack();
            // Abstraksi: Mengembalikan pengguna dengan pesan error.
            return back()->with('error', 'Gagal membuat pesanan: ' . $e->getMessage())->withInput();
        }
    }

    /**
     * Menampilkan daftar pesanan yang masih dalam status 'pending'.
     * Fungsi: Mengambil semua pesanan dengan status 'pending' dari database
     * dan menampilkannya di halaman laporan pesanan.
     * Abstraksi: Menggunakan model Order dan relasi 'orderItems' dan 'menu'
     * untuk mengambil data yang diperlukan.
     */
    public function showPendingOrders()
    {
        // Ambil semua pesanan dengan status 'pending'
        // Eager load orderItems dan menu terkait untuk menghindari N+1 query problem
        $orders = Order::where('status', 'pending') // Mengubah nama variabel dari $pendingOrders menjadi $orders
                               ->with(['orderItems.menu', 'orderItems.menu.kategori'])
                               ->orderBy('created_at', 'asc') // Urutkan dari yang paling lama
                               ->get();

        // Mengembalikan view 'laporanpesanan' dengan data pesanan pending
        return view('laporanpesanan', compact('orders')); // Mengirimkan variabel $orders
    }


    /**
     * Menampilkan histori pesanan (status 'completed' atau 'cancelled').
     * Fungsi: Mengambil pesanan yang telah selesai atau dibatalkan.
     * Abstraksi: Menggunakan `whereIn` untuk memfilter status.
     */
    public function showHistory()
    {
        $historyOrders = Order::whereIn('status', ['completed', 'cancelled'])
                               ->with(['orderItems.menu', 'orderItems.menu.kategori'])
                               ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
                               ->get();

        return view('historipesanan', compact('historyOrders')); // Pastikan ini mengarah ke historipesanan.blade.php
    }

    /**
     * Mencetak rekap laporan pesanan.
     * Fungsi: Menghasilkan tampilan rekap pesanan yang siap cetak.
     * Abstraksi: Menggunakan `Carbon` untuk format tanggal.
     */
    public function printRekap(Request $request)
    {
        // Ambil data pesanan yang sudah selesai dari database
        $completedOrders = Order::where('status', 'completed')
                               ->with('orderItems.menu')
                               ->orderBy('created_at', 'asc')
                               ->get();

        // Hitung total pendapatan
        $totalPendapatan = $completedOrders->sum('total_harga');

        // Mengembalikan view untuk rekap print
        return view('rekap_print', compact('completedOrders', 'totalPendapatan'));
    }

    /**
     * Memperbarui status pesanan (pending, completed, cancelled).
     * Fungsi: Mengubah status pesanan berdasarkan ID pesanan dan status baru.
     * Abstraksi: Menggunakan `$request->validate()` untuk validasi,
     * `Order::findOrFail()` untuk mencari model, dan `$order->save()` untuk menyimpan perubahan.
     * Polymorphism: Metode `save()` pada objek `Order` akan melakukan operasi UPDATE.
     * Redirect dilakukan ke rute yang berbeda (`laporanpesanan.list` atau `historipesanan.list`)
     * berdasarkan nilai status, menunjukkan perilaku yang berbeda dari metode yang sama.
     */
    public function updateStatus(Request $request, $id)
    {
        // Abstraksi: Validasi status baru.
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        // Abstraksi: Mencari pesanan berdasarkan ID.
        $order = Order::findOrFail($id);
        // Enkapsulasi: Memperbarui atribut status.
        $order->status = $request->status;
        // Abstraksi/Polymorphism: Menyimpan perubahan ke database.
        $order->save();

        // Enkapsulasi/Polymorphism: Arahkan ke halaman yang berbeda tergantung status baru.
        if ($order->status == 'pending') {
            return redirect()->route('laporanpesanan.list')->with('success', 'Status pesanan berhasil diperbarui!');
        } else {
            // Jika status menjadi 'completed' atau 'cancelled', arahkan ke histori pesanan
            return redirect()->route('historipesanan.list')->with('success', 'Status pesanan berhasil diperbarui dan dipindahkan ke histori!');
        }
    }
}

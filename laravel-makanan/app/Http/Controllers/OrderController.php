<?php
// app/Http/Controllers/OrderController.php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Kategori; // Pastikan ini di-import
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    /**
     * Menampilkan daftar menu untuk halaman pemesanan.
     */
    public function index()
    {
        // Mengambil semua menu beserta kategori terkait
        $menus = Menu::with('kategori')->get();
        $kategoris = Kategori::all(); // Ambil semua kategori
        return view('order', compact('menus', 'kategoris')); // Kirim kategori ke view
    }

    /**
     * Menyimpan pesanan baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi data yang masuk dari form
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'meja_nomor' => 'nullable|string|max:255',
            'menu_ids' => 'required|string', // Menerima sebagai string JSON
            'quantities' => 'required|string', // Menerima sebagai string JSON
        ]);

        // Decode JSON string menjadi array
        $menuIds = json_decode($request->menu_ids, true);
        $quantities = json_decode($request->quantities, true);

        // Pastikan menu_ids dan quantities adalah array dan memiliki jumlah elemen yang sama
        if (!is_array($menuIds) || !is_array($quantities) || count($menuIds) !== count($quantities)) {
            return redirect()->back()->with('error', 'Data menu tidak valid.');
        }

        // Memulai transaksi database untuk memastikan integritas data
        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $orderItemsData = [];

            // Mengambil detail menu yang dipesan dan menghitung total harga
            foreach ($menuIds as $index => $menuId) {
                $menu = Menu::find($menuId);
                $quantity = $quantities[$index];

                if ($menu && $quantity > 0) {
                    $hargaPerItem = $menu->harga;
                    $totalHarga += ($hargaPerItem * $quantity);

                    $orderItemsData[] = [
                        'menu_id' => $menuId,
                        'quantity' => $quantity,
                        'harga_per_item' => $hargaPerItem,
                    ];
                }
            }

            // Membuat entri pesanan baru di tabel orders
            $order = Order::create([
                'nama_pemesan' => $request->nama_pemesan,
                'meja_nomor' => $request->meja_nomor,
                'total_harga' => $totalHarga,
                'status' => 'pending', // Status awal pesanan
            ]);

            // Menyimpan setiap item pesanan ke tabel order_items
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            // Commit transaksi jika semua operasi berhasil
            DB::commit();

            // Setelah pesanan berhasil disimpan, arahkan ke halaman daftar pesanan
            return redirect()->route('laporanpesanan.list')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi kesalahan
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan daftar semua pesanan.
     */
    public function showOrders()
    {
        // Mengambil semua pesanan beserta item pesanan dan detail menu terkait
        $orders = Order::with('orderItems.menu')->orderBy('created_at', 'desc')->get();
        // Mengubah nama view dari 'orders' menjadi 'laporanpesanan'
        return view('laporanpesanan', compact('orders'));
    }

    /**
     * Memperbarui status pesanan.
     */
    public function updateStatus(Request $request, $id)
    {
        // Validasi status baru
        $request->validate([
            'status' => 'required|in:pending,completed,cancelled',
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'Status pesanan berhasil diperbarui!');
    }
}


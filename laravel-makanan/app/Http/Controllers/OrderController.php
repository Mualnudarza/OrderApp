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
     * Menampilkan daftar menu untuk halaman pemesanan.
     * Enkapsulasi: Metode ini mengemas logika untuk mengambil data menu dan kategori,
     * serta menampilkan view 'order'.
     * Abstraksi: Menggunakan Eloquent ORM (`Menu::with('kategori')->get()`, `Kategori::all()`)
     * untuk mengambil data dari database tanpa menulis query SQL mentah.
     */
    public function index()
    {
        // Abstraksi: Mengambil semua menu beserta kategori terkait.
        // `with('kategori')` adalah contoh abstraksi eager loading relasi.
        $menus = Menu::with('kategori')->get();
        // Abstraksi: Mengambil semua kategori.
        $kategoris = Kategori::all();
        // Enkapsulasi: Mengembalikan view 'order' dengan data yang sudah disiapkan.
        return view('order', compact('menus', 'kategoris'));
    }

    /**
     * Menyimpan pesanan baru ke database.
     * Enkapsulasi: Mengemas seluruh alur pembuatan pesanan, mulai dari validasi,
     * perhitungan total, hingga penyimpanan ke database dalam sebuah transaksi.
     * Abstraksi: Menggunakan `$request->validate()` untuk validasi input,
     * `DB::beginTransaction()`/`DB::commit()`/`DB::rollBack()` untuk manajemen transaksi,
     * `Menu::find()` untuk mencari menu, `Order::create()` untuk membuat pesanan,
     * dan `$order->orderItems()->create()` untuk menambahkan item pesanan.
     * Polymorphism: Metode `validate` pada objek Request dapat digunakan untuk berbagai
     * aturan validasi pada input yang berbeda. Metode `create` pada model Order dan OrderItem
     * melakukan operasi INSERT yang berbeda tergantung pada modelnya.
     */
    public function store(Request $request)
    {
        // Abstraksi: Validasi data yang masuk dari form.
        $request->validate([
            'nama_pemesan' => 'required|string|max:255',
            'meja_nomor' => 'nullable|string|max:255',
            'menu_ids' => 'required|string', // Menerima sebagai string JSON
            'quantities' => 'required|string', // Menerima sebagai string JSON
        ]);

        // Enkapsulasi: Mengubah string JSON menjadi array PHP.
        $menuIds = json_decode($request->menu_ids, true);
        $quantities = json_decode($request->quantities, true);

        // Enkapsulasi: Logika validasi tambahan untuk memastikan integritas data.
        if (!is_array($menuIds) || !is_array($quantities) || count($menuIds) !== count($quantities)) {
            return redirect()->back()->with('error', 'Data menu tidak valid.');
        }

        // Abstraksi: Memulai transaksi database. Ini mengelompokkan operasi database
        // sehingga semuanya berhasil atau tidak sama sekali.
        DB::beginTransaction();

        try {
            $totalHarga = 0;
            $orderItemsData = [];

            // Enkapsulasi: Mengambil detail menu yang dipesan dan menghitung total harga.
            foreach ($menuIds as $index => $menuId) {
                // Abstraksi: Mencari menu berdasarkan ID.
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

            // Abstraksi/Polymorphism: Membuat entri pesanan baru di tabel orders.
            // Metode `create` pada model `Order` mengabstraksi query INSERT.
            $order = Order::create([
                'nama_pemesan' => $request->nama_pemesan,
                'meja_nomor' => $request->meja_nomor,
                'total_harga' => $totalHarga,
                'status' => 'pending', // Status awal pesanan
            ]);

            // Abstraksi/Polymorphism: Menyimpan setiap item pesanan ke tabel order_items.
            // Metode `orderItems()` mengabstraksi relasi dan `create()` menambahkan item terkait.
            foreach ($orderItemsData as $itemData) {
                $order->orderItems()->create($itemData);
            }

            // Abstraksi: Commit transaksi jika semua operasi berhasil.
            DB::commit();

            // Enkapsulasi: Mengarahkan pengguna kembali dengan pesan sukses.
            return redirect()->route('laporanpesanan.list')->with('success', 'Pesanan berhasil dibuat!');

        } catch (\Exception $e) {
            // Abstraksi: Rollback transaksi jika terjadi kesalahan.
            DB::rollBack();
            // Enkapsulasi: Mengarahkan pengguna kembali dengan pesan error.
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat pesanan: ' . $e->getMessage());
        }
    }

    /**
     * Menampilkan daftar pesanan yang berstatus 'pending' (Laporan Pesanan Aktif).
     * Enkapsulasi: Mengemas logika pengambilan data pesanan aktif dan menampilkan view.
     * Abstraksi: Menggunakan Eloquent ORM (`Order::with()`, `where()`, `orderBy()`, `get()`)
     * untuk membangun query database secara terstruktur tanpa SQL mentah.
     */
    public function showOrders()
    {
        // Abstraksi: Mengambil pesanan yang statusnya 'pending', dengan eager loading relasi.
        // `where('status', 'pending')` adalah contoh abstraksi filter data.
        // `orderBy('created_at', 'asc')` adalah contoh abstraksi pengurutan data (FIFO: data baru di bawah).
        $orders = Order::with('orderItems.menu')
                        ->where('status', 'pending')
                        ->orderBy('created_at', 'asc')
                        ->get();
        // Enkapsulasi: Mengembalikan view 'laporanpesanan' dengan data pesanan aktif.
        return view('laporanpesanan', compact('orders'));
    }

    /**
     * Menampilkan daftar pesanan yang berstatus 'completed' atau 'cancelled' (Histori Pesanan) dengan filter.
     * Enkapsulasi: Mengemas logika pengambilan data histori pesanan dan menerapkan filter dinamis,
     * kemudian menampilkan view.
     * Abstraksi: Menggunakan metode query builder Eloquent (`whereIn()`, `whereMonth()`, `whereYear()`, `orderBy()`)
     * untuk membangun query yang kompleks secara mudah.
     * Polymorphism: Metode `whereMonth`, `whereYear`, dan `where` dapat digunakan pada kolom yang berbeda
     * dan dengan nilai yang berbeda untuk memfilter data.
     */
    public function showHistory(Request $request)
    {
        // Abstraksi: Memulai query untuk pesanan dengan status 'completed' atau 'cancelled'.
        $query = Order::with('orderItems.menu')
                      ->whereIn('status', ['completed', 'cancelled']);

        // Enkapsulasi/Abstraksi: Menerapkan filter berdasarkan bulan jika ada di request.
        if ($request->filled('month') && $request->month !== 'all') {
            $query->whereMonth('updated_at', $request->month);
        }

        // Enkapsulasi/Abstraksi: Menerapkan filter berdasarkan tahun jika ada di request.
        if ($request->filled('year') && $request->year !== 'all') {
            $query->whereYear('updated_at', $request->year);
        }

        // Enkapsulasi/Abstraksi: Menerapkan filter berdasarkan status jika ada di request.
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Abstraksi: Mengambil hasil query yang sudah difilter, diurutkan terbaru di atas.
        $historyOrders = $query->orderBy('updated_at', 'desc')->get();

        // Enkapsulasi: Mengembalikan view 'historipesanan' dengan data histori pesanan.
        return view('historipesanan', compact('historyOrders'));
    }

    /**
     * Mencetak rekap laporan pesanan histori (completed/cancelled) berdasarkan filter.
     * Enkapsulasi: Mengemas logika untuk mengambil data histori pesanan berdasarkan filter,
     * menghitung ringkasan, dan menyiapkan data untuk tampilan cetak.
     * Abstraksi: Menggunakan Eloquent untuk query data dan Carbon untuk format tanggal.
     * Polymorphism: Metode `whereMonth`, `whereYear`, `where` digunakan untuk filtering,
     * dan `count()`, `sum()` digunakan untuk agregasi data.
     */
    public function printRekap(Request $request)
    {
        // Abstraksi: Memulai query untuk pesanan dengan status 'completed' atau 'cancelled'.
        $query = Order::with('orderItems.menu')
                      ->whereIn('status', ['completed', 'cancelled']);

        // Enkapsulasi/Abstraksi: Menerapkan filter berdasarkan bulan jika ada di request.
        if ($request->filled('month') && $request->month !== 'all') {
            $query->whereMonth('updated_at', $request->month);
        }

        // Enkapsulasi/Abstraksi: Menerapkan filter berdasarkan tahun jika ada di request.
        if ($request->filled('year') && $request->year !== 'all') {
            $query->whereYear('updated_at', $request->year);
        }

        // Enkapsulasi/Abstraksi: Menerapkan filter berdasarkan status jika ada di request.
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $filteredOrders = $query->orderBy('updated_at', 'desc')->get();

        // Enkapsulasi: Menghitung data ringkasan.
        $totalCompletedOrders = $filteredOrders->where('status', 'completed')->count();
        $totalCancelledOrders = $filteredOrders->where('status', 'cancelled')->count();
        $grandTotalRevenue = $filteredOrders->where('status', 'completed')->sum('total_harga');

        // Enkapsulasi: Menyiapkan label filter untuk header laporan.
        $filterLabels = [];
        if ($request->month !== 'all' && $request->filled('month')) {
            $filterLabels[] = 'Bulan: ' . Carbon::create()->month($request->month)->translatedFormat('F');
        }
        if ($request->year !== 'all' && $request->filled('year')) {
            $filterLabels[] = 'Tahun: ' . $request->year;
        }
        if ($request->status !== 'all' && $request->filled('status')) {
            $filterLabels[] = 'Status: ' . ucfirst($request->status);
        }
        $reportTitle = 'Rekap Laporan Histori Pesanan';
        if (!empty($filterLabels)) {
            $reportTitle .= ' (' . implode(', ', $filterLabels) . ')';
        }

        // Enkapsulasi: Mengembalikan view 'rekap_print' dengan semua data yang diperlukan.
        return view('rekap_print', compact('filteredOrders', 'reportTitle', 'totalCompletedOrders', 'totalCancelledOrders', 'grandTotalRevenue'));
    }


    /**
     * Memperbarui status pesanan.
     * Enkapsulasi: Mengemas logika validasi, pencarian pesanan, pembaruan status,
     * dan pengalihan (redirect) berdasarkan status baru.
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

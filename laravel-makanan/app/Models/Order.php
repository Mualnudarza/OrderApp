<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Abstraksi: Mengimpor trait HasFactory untuk factory model
use Illuminate\Database\Eloquent\Model; // Abstraksi: Mengimpor kelas dasar Model dari Eloquent ORM

/**
 * Class Order
 * @p4ckage App\Models
 *
 * Enkapsulasi: Kelas Order merepresentasikan entitas 'pesanan' dalam sistem.
 * Ini mengemas data (nama_pemesan, meja_nomor, total_harga, status) dan perilaku (relasi dengan item pesanan) menjadi satu unit.
 * Pewarisan: Kelas Order mewarisi fungsionalitas dasar dari Illuminate\Database\Eloquent\Model,
 * seperti kemampuan untuk berinteraksi dengan database (CRUD) tanpa menulis SQL mentah.
 * Abstraksi: Pengembang tidak perlu tahu detail bagaimana data disimpan atau diambil dari tabel 'orders',
 * cukup berinteraksi melalui objek Order.
 */
class Order extends Model
{
    use HasFactory; // Pewarisan/Abstraksi: Menggunakan trait HasFactory untuk kemampuan factory.

    /**
     * Kolom yang dapat diisi secara massal.
     * Enkapsulasi: Mengontrol atribut mana yang dapat diisi dari luar, melindungi integritas data.
     * Fungsi: Memungkinkan pengisian atribut model secara massal melalui array, seperti `Order::create([...])`.
     * Cara kerja: Laravel secara otomatis akan mengizinkan atribut yang terdaftar di `$fillable` untuk diisi.
     */
    protected $fillable = [
        'nama_pemesan',
        'meja_nomor',
        'total_harga',
        'status',
    ];

    /**
     * Definisi relasi one-to-many: Sebuah Order memiliki banyak OrderItem.
     * Enkapsulasi: Metode ini mengemas logika untuk mendapatkan item pesanan yang terkait dengan pesanan ini.
     * Abstraksi: Pengembang dapat mengakses item pesanan terkait dengan mudah melalui `$order->orderItems` tanpa menulis JOIN SQL.
     * Polymorphism: Metode `hasMany` adalah bagian dari ORM Eloquent yang dapat digunakan di berbagai model untuk mendefinisikan relasi one-to-many.
     * Fungsi: Mendefinisikan hubungan antara model Order dan OrderItem, di mana satu Order dapat memiliki banyak OrderItem.
     * Cara kerja: Ketika Anda memanggil `$order->orderItems`, Eloquent akan secara otomatis mengambil semua OrderItem yang memiliki `order_id` yang sesuai dengan ID pesanan ini.
     */
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // Abstraksi: Mengimpor trait HasFactory untuk factory model
use Illuminate\Database\Eloquent\Model; // Abstraksi: Mengimpor kelas dasar Model dari Eloquent ORM

/**
 * Class OrderItem
 * @package App\Models
 *
 * Enkapsulasi: Kelas OrderItem merepresentasikan entitas 'item pesanan' dalam sistem.
 * Ini mengemas data (order_id, menu_id, quantity, harga_per_item) dan perilaku (relasi dengan order dan menu) menjadi satu unit.
 * Pewarisan: Kelas OrderItem mewarisi fungsionalitas dasar dari Illuminate\Database\Eloquent\Model,
 * seperti kemampuan untuk berinteraksi dengan database (CRUD) tanpa menulis SQL mentah.
 * Abstraksi: Pengembang tidak perlu tahu detail bagaimana data disimpan atau diambil dari tabel 'order_items',
 * cukup berinteraksi melalui objek OrderItem.
 */
class OrderItem extends Model
{
    use HasFactory; // Pewarisan/Abstraksi: Menggunakan trait HasFactory untuk kemampuan factory.

    /**
     * Kolom yang dapat diisi secara massal.
     * Enkapsulasi: Mengontrol atribut mana yang dapat diisi dari luar, melindungi integritas data.
     * Fungsi: Memungkinkan pengisian atribut model secara massal melalui array, seperti `OrderItem::create([...])`.
     * Cara kerja: Laravel secara otomatis akan mengizinkan atribut yang terdaftar di `$fillable` untuk diisi.
     */
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'harga_per_item',
    ];

    /**
     * Definisi relasi many-to-one: Sebuah OrderItem dimiliki oleh satu Order.
     * Enkapsulasi: Metode ini mengemas logika untuk mendapatkan pesanan induk.
     * Abstraksi: Pengembang dapat mengakses order terkait dengan mudah melalui `$orderItem->order` tanpa menulis JOIN SQL.
     * Polymorphism: Metode `belongsTo` adalah bagian dari ORM Eloquent yang dapat digunakan di berbagai model untuk mendefinisikan relasi many-to-one.
     * Fungsi: Mendefinisikan hubungan antara model OrderItem dan Order, di mana satu OrderItem dimiliki oleh satu Order.
     * Cara kerja: Ketika Anda memanggil `$orderItem->order`, Eloquent akan secara otomatis mengambil Order yang memiliki ID sesuai dengan `order_id` dari OrderItem ini.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Definisi relasi many-to-one: Sebuah OrderItem terkait dengan satu Menu.
     * Enkapsulasi: Metode ini mengemas logika untuk mendapatkan detail menu yang dipesan.
     * Abstraksi: Pengembang dapat mengakses menu terkait dengan mudah melalui `$orderItem->menu` tanpa menulis JOIN SQL.
     * Polymorphism: Metode `belongsTo` juga digunakan di sini, menunjukkan bagaimana metode yang sama dapat beroperasi pada objek yang berbeda (Menu) untuk mencapai tujuan yang berbeda.
     * Fungsi: Mendefinisikan hubungan antara model OrderItem dan Menu, di mana satu OrderItem terkait dengan satu Menu.
     * Cara kerja: Ketika Anda memanggil `$orderItem->menu`, Eloquent akan secara otomatis mengambil Menu yang memiliki ID sesuai dengan `menu_id` dari OrderItem ini.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}

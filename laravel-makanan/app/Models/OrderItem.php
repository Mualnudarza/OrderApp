<?php
// app/Models/OrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    use HasFactory;

    // Kolom yang dapat diisi secara massal
    protected $fillable = [
        'order_id',
        'menu_id',
        'quantity',
        'harga_per_item',
    ];

    /**
     * Definisi relasi: Sebuah OrderItem dimiliki oleh satu Order.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Definisi relasi: Sebuah OrderItem terkait dengan satu Menu.
     */
    public function menu()
    {
        return $this->belongsTo(Menu::class);
    }
}


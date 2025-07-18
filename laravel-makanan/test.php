<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory; 

    protected $fillable = [
        'nama_pemesan',
        'meja_nomor',
        'total_harga',
        'status',
    ];

    
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }
}

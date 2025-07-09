<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleHistory extends Model
{
    use HasFactory;

    // Pastikan semua kolom yang akan diisi secara massal ada di sini
    protected $fillable = [
        'user_id',
        'old_role',
        'new_role',
        'changed_by_user_id',
        'changed_at',
    ];

    /**
     * Tentukan atribut yang harus di-cast ke tipe data tertentu.
     * Ini penting agar 'changed_at' diubah menjadi objek Carbon secara otomatis.
     */
    protected $casts = [
        'changed_at' => 'datetime', // Tambahkan baris ini
    ];

    /**
     * Definisi relasi: Sebuah RoleHistory dimiliki oleh satu User (pengguna yang perannya diubah).
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Definisi relasi: Sebuah RoleHistory diubah oleh satu User (pengguna yang melakukan perubahan).
     */
    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by_user_id');
    }
}

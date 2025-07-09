<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role', // Pastikan 'role' ada di sini jika Anda menggunakannya
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Definisi relasi: Sebuah User memiliki banyak RoleHistory.
     * Relasi ini memungkinkan kita untuk dengan mudah mengambil semua catatan
     * perubahan peran yang terkait dengan pengguna ini.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function roleHistories()
    {
        // Mengurutkan histori dari yang terbaru (descending)
        return $this->hasMany(RoleHistory::class)->orderBy('changed_at', 'desc');
    }

    /**
     * Definisi relasi: Sebuah User memiliki banyak RoleHistory di mana mereka adalah pengubahnya.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function changedByRoleHistories()
    {
        return $this->hasMany(RoleHistory::class, 'changed_by_user_id');
    }

    /**
     * Cek apakah pengguna adalah Admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Cek apakah pengguna adalah Kasir.
     *
     * @return bool
     */
    public function isKasir()
    {
        return $this->role === 'kasir';
    }

    /**
     * Cek apakah pengguna adalah Master.
     *
     * @return bool
     */
    public function isMaster()
    {
        return $this->role === 'master';
    }
}

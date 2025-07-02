<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Buat user Admin
        DB::table('users')->insert([
            'name' => 'Admin Utama',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'role' => 'admin',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat user Master
        DB::table('users')->insert([
            'name' => 'Master User',
            'email' => 'master@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'role' => 'master',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Buat user Kasir
        DB::table('users')->insert([
            'name' => 'Kasir User',
            'email' => 'kasir@example.com',
            'password' => Hash::make('password'), // Ganti dengan password yang kuat
            'role' => 'kasir',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
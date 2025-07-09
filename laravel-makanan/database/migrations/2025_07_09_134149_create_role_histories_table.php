<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('role_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID pengguna yang perannya diubah
            $table->string('old_role')->nullable(); // Peran lama (bisa null jika ini adalah peran awal)
            $table->string('new_role'); // Peran baru
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->onDelete('set null'); // Siapa yang mengubah peran (ID Master), bisa null jika pengguna dihapus
            $table->timestamp('changed_at')->useCurrent(); // Waktu perubahan
            $table->timestamps(); // created_at dan updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('role_histories');
    }
};

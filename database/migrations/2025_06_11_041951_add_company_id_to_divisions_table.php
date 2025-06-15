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
    Schema::table('divisions', function (Blueprint $table) {
        // Menambahkan foreign key ke tabel users (yang kita anggap sebagai tabel perusahaan)
        // 'after('id')' menempatkan kolom ini setelah kolom id. Ini opsional.
        // onDelete('cascade') berarti jika perusahaan dihapus, semua divisinya akan ikut terhapus.
        $table->foreignId('company_id')->after('id')->constrained('users')->onDelete('cascade');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('divisions', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('address')->nullable();
            $table->string('photo_url')->nullable();
            $table->date('birthdate')->nullable();
            $table->enum('gender', ['pria', 'wanita']);
            $table->string('university')->nullable();
            $table->text('skills')->nullable();

            // untuk perusahaan
            $table->string('company_name')->nullable();
            $table->text('company_description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};

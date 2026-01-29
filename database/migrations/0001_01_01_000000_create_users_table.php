<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('email')->unique();
            $table->string('kategori_instansi');
            $table->string('instansi');
            $table->string('nama_pic');
            $table->string('kontak_pic', 20);
            $table->string('password');
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif'); 
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
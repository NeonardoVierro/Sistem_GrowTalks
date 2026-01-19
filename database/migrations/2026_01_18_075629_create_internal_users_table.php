<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('internal_users', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_role')->constrained('roles');
            $table->string('nama_user', 100);
            $table->string('email', 100)->unique();
            $table->string('password', 255);
            $table->string('jabatan', 100);
            $table->string('no_hp', 20);
            $table->enum('status', ['aktif', 'nonaktif'])->default('aktif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('internal_users');
    }
};
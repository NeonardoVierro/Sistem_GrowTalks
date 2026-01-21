<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('podcasts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_user')->constrained('users');
            $table->foreignId('id_kalender')->nullable()->constrained('kalenders');
            $table->date('tanggal');
            $table->string('nama_opd', 100);
            $table->string('nama_pic', 100);
            $table->string('keterangan', 100);
            $table->string('narasumber', 100);
            $table->string('verifikasi', 100)->nullable();
            $table->enum('status_verifikasi', ['pending', 'disetujui', 'ditolak', 'penjadwalan ulang'])->default('pending');
            $table->string('host', 100)->nullable();
            $table->string('waktu', 50)->nullable();
            $table->string('cover_path', 255)->nullable();
            $table->text('catatan')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('podcasts');
    }
};
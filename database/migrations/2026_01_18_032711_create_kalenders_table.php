<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kalenders', function (Blueprint $table) {
            $table->id();
            $table->date('tanggal_kalender');
            $table->time('waktu')->nullable();
            $table->boolean('sudah_dibooking')->default(false);
            $table->enum('jenis_agenda', ['podcast', 'coaching'])->nullable();
            $table->integer('id_agenda')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kalenders');
    }
};
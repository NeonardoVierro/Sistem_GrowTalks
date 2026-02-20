<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->string('no_hp')->nullable()->after('role');
            $table->string('bidang')->nullable()->after('no_hp');
        });
    }

    public function down()
    {
        Schema::table('staffs', function (Blueprint $table) {
            $table->dropColumn(['no_hp','bidang']);
        });
    }
};

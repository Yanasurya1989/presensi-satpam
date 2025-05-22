<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lembur', function (Blueprint $table) {
            $table->integer('total_lembur')->change(); // ubah dari string ke integer
        });
    }

    public function down(): void
    {
        Schema::table('lembur', function (Blueprint $table) {
            //
        });
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('lembur', function (Blueprint $table) {
            $table->date('tanggal')->nullable();
            $table->string('total_lembur')->nullable(); // Misal: "2 jam 30 menit"
        });
    }
    public function down(): void
    {
        Schema::table('lembur', function (Blueprint $table) {
            //
        });
    }
};

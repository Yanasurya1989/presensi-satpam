<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lembur', function (Blueprint $table) {
            $table->id();
            $table->time('mulai_lembur_satu')->nullable();
            $table->time('akhir_lembur_satu')->nullable();
            $table->time('mulai_lembur_dua')->nullable();
            $table->time('akhir_lembur_dua')->nullable();
            $table->time('mulai_lembur_tiga')->nullable();
            $table->time('akhir_lembur_tiga')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lembur');
    }
};

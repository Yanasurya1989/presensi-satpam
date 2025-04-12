<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('overtimes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('start_time');     // jam_mulai
            $table->dateTime('end_time');       // jam_selesai
            $table->integer('total_minutes');   // total_menit
            $table->timestamps();               // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('overtimes');
    }
};

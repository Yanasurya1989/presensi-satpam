<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('invals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->time('time_start');
            $table->time('time_end');
            $table->string('pengganti');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('invals');
    }
};

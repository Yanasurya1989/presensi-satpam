<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('overtimes', function (Blueprint $table) {
            $table->time('start_time_shift1')->nullable();
            $table->time('end_time_shift1')->nullable();
            $table->integer('total_minutes_shift1')->nullable();

            $table->time('start_time_shift2')->nullable();
            $table->time('end_time_shift2')->nullable();
            $table->integer('total_minutes_shift2')->nullable();

            $table->time('start_time_shift3')->nullable();
            $table->time('end_time_shift3')->nullable();
            $table->integer('total_minutes_shift3')->nullable();
        });
    }

    public function down(): void
    {
        //
    }
};

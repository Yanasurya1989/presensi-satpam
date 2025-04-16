<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('presence', function (Blueprint $table) {
            $table->time('jam_2')->nullable()->after('jam');
        });
    }

    public function down()
    {
        Schema::table('presence', function (Blueprint $table) {
            $table->dropColumn('jam_2');
        });
    }
};

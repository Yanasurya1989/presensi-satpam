<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('shifts')->insert([
            ['name' => 'Pagi', 'start_time' => '08:00:00', 'end_time' => '16:00:00'],
            ['name' => 'Siang', 'start_time' => '16:00:00', 'end_time' => '00:00:00'],
            ['name' => 'Malam', 'start_time' => '00:00:00', 'end_time' => '08:00:00'],
        ]);
    }
}

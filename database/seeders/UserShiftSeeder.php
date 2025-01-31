<?php

namespace Database\Seeders;

use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserShiftSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $users = DB::table('users')->pluck('id');
        $shifts = DB::table('shifts')->pluck('id');

        foreach ($users as $user) {
            DB::table('user_shifts')->insert([
                [
                    'user_id' => $user,
                    'shift_id' => $shifts->random(),
                    'shift_date' => Carbon::today()->format('Y-m-d'),
                ],
            ]);
        }
    }
}

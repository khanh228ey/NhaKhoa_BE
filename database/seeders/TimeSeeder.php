<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class TimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
       
$startHour = 8;
$endHour = 20; // Kết thúc lúc 17h

for ($hour = $startHour; $hour < $endHour; $hour++) {
    // Bỏ qua khoảng 12:00 đến 13:00
    if ($hour == 12 || $hour == 13) {
        continue;
    }

    $nextHour = $hour + 1;
    $timeSlot = sprintf('%02d:00 - %02d:00', $hour, $nextHour);

    DB::table('schedule_time')->insert([
        'time' => $timeSlot,
        'status' => 1
    ]);
    }
}
}

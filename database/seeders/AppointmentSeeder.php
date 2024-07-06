<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AppointmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('appointments')->insert([
            'name' => 'Trịnh trần phương tuấn',
            'phone' => '0891924124',
            'date' => '2024-08-22',
            'time' => '14:00-15:00',
            'status' => 0,
            'note' => 'hihi',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

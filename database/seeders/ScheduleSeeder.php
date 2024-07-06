<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('schedule')->insert([
            'time_id' => '1',
            'doctor_id' => 'DH00001',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('schedule')->insert([
            'time_id' => '2',
            'doctor_id' => 'DH00001',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('schedule')->insert([
            'time_id' => '3',
            'doctor_id' => 'DH00001',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('schedule')->insert([
            'time_id' => '4',
            'doctor_id' => 'DH00001',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('schedule')->insert([
            'time_id' => '1',
            'doctor_id' => 'DH00002',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('schedule')->insert([
            'time_id' => '2',
            'doctor_id' => 'DH00002',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('schedule')->insert([
            'time_id' => '3',
            'doctor_id' => 'DH00002',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('schedule')->insert([
            'time_id' => '4',
            'doctor_id' => 'DH00002',
            'date' => '2024-07-07',
            'status' => 0,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

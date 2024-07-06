<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HistorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('histories')->insert([
            'customer_id' => 'BH00001',
            'doctor_id' => 'DH00001',
            'date' => '2024-07-7',
            'time' => '20:15',
            'noted' => 'Bệnh nặng',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('history_detail')->insert([
            'service_id' => '1',
            'history_id' => '1',
            'price' => 5000000,
            'quantity' => 5,

        ]);
        DB::table('histories')->insert([
            'customer_id' => 'BH00002',
            'doctor_id' => 'DH00001',
            'date' => '2024-07-7',
            'time' => '20:15',
            'noted' => 'Bệnh nặng',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('history_detail')->insert([
            'service_id' => '1',
            'history_id' => '2',
            'price' => 5000000,
            'quantity' => 5,

        ]);
    }
}

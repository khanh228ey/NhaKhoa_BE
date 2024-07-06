<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('services')->insert([
            'name' => 'Trồng răng lazer',
            'image' =>'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'description' => 'Mota',
            'min_price' => 4000000,
            'max_price' => 10000000,
            'unit' => '4 trieu viet nam dong',
            'quantity_sold' => 10,
            'category_id' => 1,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('services')->insert([
            'name' => 'Trồng răng tia uv',
            'image' =>'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'description' => 'Mota',
            'min_price' => 4000000,
            'max_price' => 10000000,
            'unit' => '4 trieu viet nam dong',
            'quantity_sold' => 10,
            'category_id' => 1,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('services')->insert([
            'name' => 'Trám răng',
            'image' =>'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'description' => 'Mota',
            'min_price' => 4000000,
            'max_price' => 10000000,
            'unit' => '4 trieu viet nam dong',
            'quantity_sold' => 10,
            'category_id' => 1,
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

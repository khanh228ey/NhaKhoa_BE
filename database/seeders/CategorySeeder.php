<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('categories')->insert([
            'name' => 'Trồng răng',
            'image' =>'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'description' => 'Mota',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('categories')->insert([
            'name' => 'Niềng răng',
            'image' =>'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'description' => 'Mota',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('categories')->insert([
            'name' => 'Trám răng',
            'image' =>'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'description' => 'Mota',
            'status' => 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

    }
}

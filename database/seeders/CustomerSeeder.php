<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('customers')->insert([
            'id' => 'BH00001',
            'name' => 'Hà Nhật Khánh',
            'email' => 'khanh1231@gmail.com',
            'gender' => 1, // 1 là nam 2 là nữ
            'address' => '180 cao lỗ phường 4 quận 8 TPHCM',
            'birthday' => '2002-08-25',
            'phone_number' => '0338235318',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('customers')->insert([
            'id' => 'BH00002',
            'name' => 'Huỳnh Quốc Tuấn',
            'email' => 'khanh1211131@gmail.com',
            'gender' => 1, // 1 là nam 2 là nữ
            'address' => '180 cao lỗ phường 4 quận 8 TPHCM',
            'birthday' => '2002-08-25',
            'phone_number' => '0338230328',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('customers')->insert([
            'id' => 'BH00003',
            'name' => 'Trịnh Trần Phương Tuấn',
            'email' => 'khanh123111@gmail.com',
            'gender' => 1, // 1 là nam 2 là nữ
            'address' => '180 cao lỗ phường 4 quận 8 TPHCM',
            'birthday' => '2002-08-25',
            'phone_number' => '0338230315',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'id' => 'DH00001',
            'name' => 'Hà Nhật Khánh',
            'email' => 'khanh@gmail.com',
            'password' => Hash::make('123456'),
            'gender' => 1, // 1 là nam 2 là nữ
            'address' => '180 cao lỗ phường 4 quận 8 TPHCM',
            'avatar' => 'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'birthday' => '2002-08-25',
            'description' => 'Học tại Stu ',
            'role_id' => 1,
            'phone_number' => '0338230318',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'id' => 'DH00002',
            'name' => 'Huỳnh Quốc Tuấn',
            'email' => 'khanh1@gmail.com',
            'password' => Hash::make('123456'),
            'avatar' => 'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'gender' => 1, // 1 là nam 2 là nữ
            'address' => '180 cao lỗ phường 4 quận 8 TPHCM',
            'birthday' => '2002-08-25',
            'role_id' => 2,
            'phone_number' => '0338230319',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('users')->insert([
            'id' => 'DH00003',
            'name' => 'Trịnh Trần Phương Tuấn',
            'avatar' => 'https://imagev3.vietnamplus.vn/w660/Uploaded/2024/mzdic/2023_03_24/Cristiano_Ronaldo_Portugal_2403.jpg.webp',
            'email' => 'khanh2@gmail.com',
            'password' => Hash::make('123456'),
            'gender' => 1, // 1 là nam 2 là nữ
            'address' => '180 cao lỗ phường 4 quận 8 TPHCM',
            'birthday' => '2002-08-25',
            'role_id' => 3,
            'phone_number' => '0338230317',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

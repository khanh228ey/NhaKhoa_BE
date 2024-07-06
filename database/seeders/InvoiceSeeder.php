<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class InvoiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('invoices')->insert([
            'history_id' => '1',
            'status' => 0,
            'method_payment' => 0,
            'total_price' => 1000000000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
        DB::table('invoices')->insert([
            'history_id' => '2',
            'status' => 0,
            'method_payment' => 0,
            'total_price' => 1000000000,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}

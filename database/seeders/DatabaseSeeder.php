<?php

namespace Database\Seeders;

use App\Models\Invoices;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(RolesSeeder::class);
        $this->call(UsersSeeder::class);
        $this->call(TimeSeeder::class);
        $this->call(PermissionSeeder::class);
        $this->call(RoleHasPermission::class);
        $this->call(CategorySeeder::class);
        $this->call(ServiceSeeder::class);
        $this->call(CustomerSeeder::class);
        $this->call(HistorySeeder::class);
        $this->call(InvoiceSeeder::class);
        $this->call(AppointmentSeeder::class);

    }

}

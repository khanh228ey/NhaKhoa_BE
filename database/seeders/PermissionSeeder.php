<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $permissions = [
            'view role',
            'update role',

            'view category',
            'create category',
            'update category',
            'delete category',

            'view service',
            'create service',
            'update service',
            'delete service',

            'view appointment',
            'create appointment',
            'update appointment',
            'delete appointment',

            'view history',
            'create history',
            'update history',
            'delete history',
        
            'view invoice',
            'create invoice',
            'update invoice',
            'delete invoice',
        
            'view customer',
            'create customer',
            'update customer',
            'delete customer',

            'view user',
            'create user',
            'update user',
            'delete user',

            'view schedule',
            'create schedule',
            'update schedule',
            'delete schedule',

            'create meeting',
    
        ];
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }
    }
}

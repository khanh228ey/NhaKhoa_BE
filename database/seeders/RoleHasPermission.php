<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleHasPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $roles = [
            'Admin' => [

                'view role',
                'update role',

                'create category',
                'update category',
                'delete category',

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
            
                'view invoice',
                'create invoice',
                'update invoice',
    
            
                'view customer',
                'create customer',
                'update customer',
    
                'view user',
                'create user',
                'update user',

                'create schedule',
                'update schedule',
                'delete schedule',

                'create meeting',
            ],
            'Employee' => [
                 'view appointment',
                'create appointment',
                'update appointment',
                'delete appointment',

                'view invoice',
                'create invoice',
                'update invoice',

                'view customer',
                'create customer',
                'update customer',

                'create meeting',

                'view schedule',
                'create schedule',
                'update schedule',
                'delete schedule',

            ],

            'Doctor' => [
                'view customer',
                'create customer',
                'update customer',

                'create meeting',

                'view history',
                'create history',
                'update history',

                'view schedule',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {
            $role = Role::where('name', $roleName)->first();
            
            foreach ($permissions as $permissionName) {
                $permission = Permission::where('name', $permissionName)->first();
                $role->givePermissionTo($permission);
            }
        }


        $user1 = User::find('DH00001'); // Giả sử user_id = 1
        $user2 = User::find('DH00002');
        $user3 = User::find('DH00003'); // Giả sử user_id = 2
        if ($user1) {
            $user1->assignRole('Doctor');
        }

        if ($user2) {
            $user2->assignRole('Employee');
        }
        if($user3){
            $user3->assignRole('Admin');
        }
    }

}

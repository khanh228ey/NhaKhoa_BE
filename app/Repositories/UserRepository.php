<?php
namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role as ModelsRole;

class UserRepository{

    Public function AddUser(Request $request){
        $data = $request->all();
        $roleId = $data['role_id'];
        $role = Role::find($roleId);
        $user = new User();
        $user->name = $data['name'];
        $user->phone_number = $data['phone_number'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->gender = $data['gender'];
        $user->address = $data['address'];
        $user->description = $data['description'];
        $user->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user->birthday = $data['birthday'];
        $user->role_id = $data['role_id'];
        $user->avatar = $data['avatar'];
        if ($user->save()) {
            $user->assignRole($role->name);
            return $user; 
        } else {
            
            return false;
        }
    }

    // Public function Update($data,$user){
    //     $roleId = $data['role_id'];
    //     $role = ModelsRole::find($roleId);
    //     $user->name = $data['name'];
    //     $user->phone_number = $data['phone_number'];
    //     $user->email = $data['email'];
    //     $user->gender = $data['gender'];
    //     $user->address = $data['address'];
    //     $user->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
    //     $user->birthday = $data['birthday'];
    //     $user->status = $data['status'];
    //     if($roleId == 1){
    //         $user->education = $data['education'];
    //         $user->certificate = $data['certificate'];
    //     }
    //     $user->education = null;
    //     $user->certificate = null;
    //     $user->role_id = $data['role_id'];
    //     $user->avatar = $data['avatar'];
    //     if ($user->save()) {
    //         $user->roles()->detach();
    //         $user->assignRole($role->name);
    //         $user->role_id = $roleId;
    //         return $user; 
    //     } else {
            
    //         return false;
    //     }
    // }
    Public function update(Request $request,$user){
        $data1 = $request->all();
        if(count($data1) > 1){
            $roleId = $data1['role_id'];
            $role = ModelsRole::find($roleId);
        }
        $data = $request->only(['id','name','email', 'phone_number','avatar','gender','birthday','description',
                'address','role_id','status']);
        $user->fill($data);
        $user->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        if ($user->save()) {
            if(count($data1) > 1){
                $user->roles()->detach();
                $user->assignRole($role->name);
                $user->role_id = $roleId;
            }
            return $user;
        }
        return false;
    }
}
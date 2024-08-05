<?php
namespace App\Repositories\Manager;

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

    public function update(Request $request, $user)
    {
        $data1 = $request->all();
        if (count($data1) > 1) {
            $roleId = $data1['role_id'];
            $role = ModelsRole::find($roleId);
        }
        $data = $request->only([
            'id', 'name', 'email', 'phone_number', 'avatar', 'gender', 'birthday', 'address', 'role_id', 'status'
        ]);
        if (isset($request->description) && $request->description !== '') {
            $data['description'] = $request->description;
        }
        if (isset($request->password) && $request->password !== '') {
            $data['password'] = Hash::make($request->password);
        }
        $user->fill($data);
        $user->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        
        if ($user->save()) {
            if (count($data1) > 1) {
                $user->roles()->detach();
                $user->assignRole($role->name);
                $user->role_id = $roleId;
            }
            return $user;
        }
        return false;
    }

}
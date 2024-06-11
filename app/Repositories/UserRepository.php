<?php
namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;


class UserRepository{

    Public function AddUser($data){
        $data = $_REQUEST;
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
            return $user; 
        } else {
            
            return false;
        }
    }

    Public function Update($data){
        $user = User::find($data['id']);
        $user->name = $data['name'];
        $user->phone_number = $data['phone_number'];
        $user->email = $data['email'];
        $user->gender = $data['gender'];
        $user->address = $data['address'];
        $user->description = $data['description'];
        $user->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user->birthday = $data['birthday'];
        $user->role_id = $data['role_id'];
        $user->avatar = $data['avatar'];
        if ($user->save()) {
            return $user; 
        } else {
            
            return false;
        }
    }
}
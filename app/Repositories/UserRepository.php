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
        $data = $_REQUEST;
        $user = User::find($data['id']);
        $user->name = $data['name'];
        $user->phone_number = $data['phone_number'];
        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->gender = $data['gender'];
        $user->address = $data['address'];
        $user->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user->birthday = $data['birthday'];
        $user->role_id = $data['role_id'];
        $url = $data['avatar'];
        if(isset($url)){
            $user->avatar = $url;
        }
        if ($user->save()) {
            return $user; 
        } else {
            
            return false;
        }
    }
}
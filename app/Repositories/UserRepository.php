<?php
namespace App\Repositories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;

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
        if ($user->save()) {
            return $user; 
        } else {
            
            return false;
        }
    }
    // public function AddUser(Request $request){
    //     $user = new User();
    //     $user->name = $request->input('name');
    //     $user->phone_number = $request->input('phone_number');
    //     $user->email = $request->input('email');
    //     $user->password = Hash::make($request->input('password'));
    //     $user->gender = $request->input('gender');
    //     $user->address = $request->input('address');
    //     $user->description = $request->input('description');
    //     $user->created_at = Carbon::now('Asia/Ho_Chi_Minh');
    //     $user->birthday = $request->input('birthday');
    //     $user->role_id = $request->input('role_id');
    //     if ($user->save()) {
    //         return $user; 
    //     } else {
    //         return false;
    //     }
    // }
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
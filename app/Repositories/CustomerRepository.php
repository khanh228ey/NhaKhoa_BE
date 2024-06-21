<?php
namespace App\Repositories;

use App\Models\Customer;
use Carbon\Carbon;

class CustomerRepository{

    Public function AddCustomer($data){
        $data = $_REQUEST;
        $customer = new Customer();
        $customer->name = $data['name'];
        $customer->phone_number = $data['phone_number'];
        $customer->email = $data['email'];
        $customer->gender = $data['gender'];
        $customer->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $customer->birthday = $data['birthday'];
        if ($customer->save()) {
            return $customer; 
        } else {
            
            return false;
        }
    }

    
    Public function Update($data,$id){
        $user = Customer::find($id);
        $user->name = $data['name'];
        $user->phone_number = $data['phone_number'];
        $user->email = $data['email'];
        $user->gender = $data['gender'];
        $user->address = $data['address'];
        $user->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $user->birthday = $data['birthday'];
        if ($user->save()) {
            return $user; 
        } else {
            
            return false;
        }
    }


}
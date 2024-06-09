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
}
<?php
namespace App\Repositories;

use App\Models\Customer;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CustomerRepository{

    Public function AddCustomer(Request $request){
        $data = $request->all();
        $customer = new Customer();
        $customer->name = $data['name'];
        $customer->phone_number = $data['phone_number'];
        $customer->email = $data['email'];
        $customer->gender = $data['gender'];
        $customer->address = $data['address'];
        $customer->created_at = Carbon::now('Asia/Ho_Chi_Minh');
        $customer->birthday = $data['birthday'];
        if ($customer->save()) {
            return $customer; 
        } else {
            
            return false;
        }
    }

    
    Public function Update($data,$id){
        $customer = Customer::find($id);
        $customer->name = $data['name'];
        $customer->phone_number = $data['phone_number'];
        $customer->email = $data['email'];
        $customer->gender = $data['gender'];
        $customer->address = $data['address'];
        $customer->updated_at = Carbon::now('Asia/Ho_Chi_Minh');
        $customer->birthday = $data['birthday'];
        if ($customer->save()) {
            return $customer; 
        } else {
            
            return false;
        }
    }


}
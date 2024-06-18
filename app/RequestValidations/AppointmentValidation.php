<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class AppointmentValidation{

    public function Appointment()
    {
        $rules = [
            'name' => 'required|max:100',
            'phone' => 'required|numeric',
        ];
        $messages = [
            'name.required' => 'Tên không được bỏ trống',
            'name.max' => 'Tên không được quá 100 kí tự',
            'phone.required' => 'Số điện thoại không được rỗng',
            'phone.numeric' => 'Số điện thoại phải là số', 
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }
        
}
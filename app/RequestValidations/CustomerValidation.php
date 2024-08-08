<?php

namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class CustomerValidation{
    public function customerValidation()
    {
        $rules = [
            'name' => 'required|max:100',
            'phone_number' => 'required|unique:customers,phone_number,'.request()->id.'|numeric',
            'birthday' => 'required',
        ];
        $messages = [
            'name.required' => 'Tên không được bỏ trống',
            'name.max' => 'Tên không được quá 100 kí tự',
            'birthday.required' => 'Ngày sinh không được để trống',
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }
}
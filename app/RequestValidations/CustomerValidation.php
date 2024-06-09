<?php

namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class CustomerValidation{
    public function create()
    {
        $rules = [
            'email' => 'unique:customers|max:100',
            'name' => 'required|max:100',
            'phone_number' => 'required|unique:customers|numeric',
            'birthday' => 'required',
        ];
        $messages = [
            'name.required' => 'Tên không được bỏ trống',
            'name.max' => 'Tên không được quá 100 kí tự',
            'email.max' => 'Email đã vượt quá 100 kí tự',
            'email.unique' => 'Email đã được sử dụng',
            'phone_number.unique' => 'Số điện thoại đã được sử dụng',
            'phone_number.required' => 'Số điện thoại không được rỗng',
            'phone_number.numeric' => 'Số điện thoại phải là số'
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }
}
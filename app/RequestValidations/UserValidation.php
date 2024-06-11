<?php

namespace App\RequestValidations;

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Validator;

class UserValidation{

    public function create()
    {
        $rules = [
            'email' => 'required|unique:users|max:100',
            'name' => 'required|max:100',
            'password' => 'required|min:6|max:100',
            'phone_number' => 'required|unique:users|numeric',
            'birthday' => 'required',
        ];
        $messages = [
            'name.required' => 'Tên không được bỏ trống',
            'name.max' => 'Tên không được quá 100 kí tự',
            'email.max' => 'Email đã vượt quá 100 kí tự',
            'email.unique' => 'Email đã được sử dụng',
            'email.required' => 'Email không được bỏ trống',
            'password.required' => 'Mật khẩu không được bỏ trống và phải có ít nhất 6 ký tự',
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

   
    public function update()
    {
        $rules = [
            'email' => 'required|unique:users|max:100',
            'name' => 'required|max:100',
            'phone_number' => 'required|unique:users|numeric',
            'birthday' => 'required',
        ];
        $messages = [
            'name.required' => 'Tên không được bỏ trống',
            'name.max' => 'Tên không được quá 100 kí tự',
            'email.max' => 'Email đã vượt quá 100 kí tự',
            'email.unique' => 'Email đã được sử dụng',
            'email.required' => 'Email không được bỏ trống',
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
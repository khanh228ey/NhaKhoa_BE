<?php

namespace App\RequestValidations;

class UserValidation{
    // public function create()
    // {
    //     $rules = [
    //         'email' => 'required|unique:users,username|min:1',
    //         'full_name' => 'required|min:1',
    //         'password' => 'required|min:6|max:100',
    //     ];
    //     $messages = [
    //         'email.unique' => 'Tên tài khoản đã được sử dụng',
    //         'email.required' => 'Tên đăng nhập không được bỏ trống',
    //         'password.required' => 'Mật khẩu không được bỏ trống và phải có ít nhất 6 ký tự',
    //     ];
    //     $validator = Validator::make(
    //         Request::all(),
    //         $rules,
    //         $messages
    //     );
    //     return $validator;
    // }

    // public function update($id)
    // {
    //     $rules = [

    //         'email' => "required|unique:users,username," . $id . ",id|min:1",
    //         'full_name' => 'required|min:1',
    //         'password' => 'required|min:6|max:100',
    //     ];
    //     $messages = [
    //         'email.unique' => 'Tên tài khoản đã được sử dụng',
    //         'email.required' => 'Tên đăng nhập không được bỏ trống',
    //         'password.required' => 'Mật khẩu không được bỏ trống và phải có ít nhất 6 ký tự',
    //     ];
    //     $validator = Validator::make(
    //         Request::all(),
    //         $rules,
    //         $messages
    //     );
    //     return $validator;
    // }

    // public function resetpass()
    // {
    //     $user = Auth::user();
    //     $rules = [
    //         'current_password' => ['required', function ($attribute, $value, $fail) use ($user) {
    //             if (!\Hash::check($value, $user->password)) {
    //                 return $fail(__('Mật khẩu hiện tại không đúng.'));
    //             }
    //         }],
    //         'new_password' => 'required|min:6|max:100',
    //         're_new_password' => 'required|same:new_password',
    //     ];
    //     $messages = [
    //         'new_password.required' => 'Mật khẩu không được bỏ trống và phải có ít nhất 6 ký tự',
    //         'new_password.min' => 'Mật khẩu không được bỏ trống và phải có ít nhất 6 ký tự',
    //         're_new_password.required' => 'Mật khẩu không trung khớp',
    //         're_new_password.same' => 'Mật khẩu không trung khớp',
    //     ];
    //     $validator = Validator::make(
    //         Request::all(),
    //         $rules,
    //         $messages
    //     );
    //     return $validator;
    // }
}
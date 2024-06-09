<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class CategoryValidation{
    public function category()
    {
        $rules = [
            'name' => 'required|unique:categories|max:100',
        ];
        $messages = [
            'name.required' => 'Tên không được bỏ trống',
            'name.unique' => 'Dịch vụ này đã tồn tại',
            'name.max' => 'Không được quá 100 kí tự',
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }
}
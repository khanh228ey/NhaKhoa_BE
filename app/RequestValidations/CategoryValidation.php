<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class CategoryValidation{

    public function categoryValidate()
    {
        $rules = [
            'name' => 'required|max:100|unique:categories,name,'.request()->id.'|max:100',
        ];
        $messages = [
            
            'name.max' => 'Không được quá 100 kí tự',
            'name.required'=>'Tên danh mục không được để trống',
            'name.unique' =>'Danh mục đã tồn tại'
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }
}
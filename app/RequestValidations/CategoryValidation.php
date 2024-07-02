<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class CategoryValidation{

    public function categoryValidate()
    {
        $rules = [
            'name' => 'required|max:100|max:100',
        ];
        $messages = [
            
            'name.max' => 'Không được quá 100 kí tự',
            'name.required'=>'Tên danh mục không được để trống',
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }
}
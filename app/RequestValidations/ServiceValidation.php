<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;


class ServiceValidation{

    public function Service()
    {
        $rules = [
            'name' => 'required|max:100|max:100',
            'min_price' => 'required||numeric',
            'max_price' => 'required|numeric',
            'unit' => 'required',
        ];
        $messages = [
            
            'name.max' => 'Không được quá 100 kí tự',
            'name.required'=>'Tên dịch vụ không dc để trống',
            'min_price.required' => 'Nhập giá của dịch vụ',
            'min_price.numeric' => 'Hãy nhập số',
            'max_price.required' => 'Nhập giá của dịch vụ',
            'max_price.numeric' => 'Hãy nhập số',
            'unit.required' => 'Đơn vị tính không được để trống',
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }

}
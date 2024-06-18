<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class InvoicesValidation{

public function invoices()
{
    $rules = [
        'total_price' => 'required|numeric',
        'time' => 'required'
    ];
    $messages = [
        'total_price.required'=>'Tổng tiền không được để trống ',
        'total_price.numeric'=>'Giá tiền hãy nhập số',
    ];
    $validator = Validator::make(
        request()->all(), 
        $rules,
        $messages
    );
    return $validator;
}
}
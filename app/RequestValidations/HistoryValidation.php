<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class HistoryValidation{

public function history()
{
    $rules = [
        'date' => 'required',
        'time' => 'required'
    ];
    $messages = [
        'date.required'=>'Ngày khám không được để trống ',
        'time.required'=>'Tên danh mục không được để trống',
    ];
    $validator = Validator::make(
        request()->all(), 
        $rules,
        $messages
    );
    return $validator;
}
}
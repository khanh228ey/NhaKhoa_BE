<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class HistoryValidation{

public function history()
{
    $rules = [
        'noted' => 'required',
    ];
    $messages = [
        'noted.required'=>'Ghi chú không được để trống',
    ];
    $validator = Validator::make(
        request()->all(), 
        $rules,
        $messages
    );
    return $validator;
}
}
<?php
namespace App\RequestValidations;
use Illuminate\Support\Facades\Validator;

class ScheduleValidation{
    public function Schedule()
    {
        $rules = [
            'date' => 'required|max:100',
        ];
        $messages = [
            'date.required' => 'Nhập ngày làm việc',
            'date.max' => 'Ngày không được quá 100 kí tự',
        ];
        $validator = Validator::make(
            request()->all(), 
            $rules,
            $messages
        );
        return $validator;
    }
}   
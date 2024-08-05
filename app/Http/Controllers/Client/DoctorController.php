<?php

namespace App\Http\Controllers\Client;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Controllers\Controller;
use App\Models\Schedule;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    //
    Public function getDoctor(){
        $doctor = User::where('role_id',3)->where('status',1)->get();
        $result = $doctor->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'avatar' => $item->avatar,
                'phone_number' => $item->phone_number,
                'description' => $item->description,
            ];
        });
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }

    Public function getDoctorDetail($id){
        try{
            $doctor = User::where('role_id',3)->where('status',1)->findOrFail($id);
            $result = 
                [
                    'id' => $doctor->id,
                    'name' => $doctor->name,
                    'description' => $doctor->description,
                    'avatar' => $doctor->avatar,
                    'birthday' => $doctor->birthday,
                    'email' => $doctor->email,
                    'gender' => (int)$doctor->gender,
                ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
        }catch(ModelNotFoundException $e){
            return JsonResponse::handle(200,"Không tìm thấy bác sĩ",null,200);
        }
        
    }

    
   
    
}

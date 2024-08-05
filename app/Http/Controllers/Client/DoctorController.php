<?php

namespace App\Http\Controllers\Client;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Resources\Translate\DoctorResource;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\Client\DoctorRepository;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    //
    protected $doctorRepo;
    public function __construct(DoctorRepository $doctor)
    {
        $this->doctorRepo = $doctor; 
    }

    public function jsonDoctor($doctor){
        $data = $doctor->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'avatar' => $item->avatar,
                'phone_number' => $item->phone_number,
                'description' => $item->description,
            ];
        });
        return $data;
    }
    public function jsonDoctorDetail($doctor){
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
        return $result;
    }
    Public function getDoctor($lang){
        $doctor = User::where('role_id',3)->where('status',1)->get();
        $result = ($lang == 'vi') ? $this->jsonDoctor($doctor) : DoctorResource::collection($doctor);
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }

    Public function getDoctorDetail($lang,$id){
        try {
            $doctor = $this->doctorRepo->findById($id);
            if($doctor == false){
                return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
            }
            $result = ($lang == 'vi') ? $this->jsonDoctorDetail($doctor)
             :new DoctorResource($doctor);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (Exception $e) {
            return JsonResponse::handle(500, ConstantsMessage::ERROR, null, 500);
        }
        
    }

    
   
    
}

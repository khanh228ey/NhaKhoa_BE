<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Http\Resources\UserResource;
use App\Models\Schedule;
use App\Models\User;
use App\Repositories\UserRepository;
use App\RequestValidations\UserValidation;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    protected $userRepository;
    protected $userValidation;
    public function __construct(UserValidation $userValidation, UserRepository $userRepository)
    {
        $this->userValidation = $userValidation;
        $this->userRepository = $userRepository; 
      
    }

    public function getUsers(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $role = $request->get('role_id');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $query = User::with('role');
        if ($role) {
            $query->where('role_id', $role);
        }
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }
        if($phone){
            $query->where('phone_number', 'LIKE', "%{$phone}%");
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $users = $data->items();
        } else {
            $users = $query->get();
        }
        $result = $users->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'birthday' => $item->birthday,
                'email' => $item->email,
                'phone' => $item->phone_number,
                'avatar' => $item->avatar,
                'role' => $item->role->name,
                
            ];
        });
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS,$result, 200);
    }

    Public function findById($id){
        try {
            $user = User::with('schedule','role')->findOrFail($id); 
            $result = new UserResource($user);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        }
    }


    Public function createUser(Request $request){
        $validator = $this->userValidation->create();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $user = $this->userRepository->AddUser($request);
        if ($user == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $user, 201);
    }

    Public function updateUser(Request $request,$id){
        $validator = $this->userValidation->update();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $user = $this->userRepository->update($request->all(),$id);
        if ($user == false) {
            return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
         return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $user, 201);
    }


    Public function getDoctor(){
        $doctor = User::where('role_id',1)->get();
        $result = $doctor->map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'email' => $item->email,
                'phone' => $item->phone_number,
                'avatar' => $item->avatar,
            ];
        });
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }


    Public function getDoctorId($id){
        $doctor = User::where('role_id',1)->find($id);
        $Schedule = Schedule::where('doctor_id', $id)
        ->select('date')->distinct()->orderBy('date', 'Asc')->get();    
        $result =  [
                'id' => $doctor->id,
                'name' => $doctor->name,
                'email' => $doctor->email,
                'phone' => $doctor->phone_number,
                'avatar' => $doctor->avatar,
                'schedule' => $Schedule,
            ];
    
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }

    Public function getDoctorTimeslotsByDate($id, $date)
{
    $schedule = Schedule::with('time')
        ->where('doctor_id', $id)
        ->where('date', $date)
        ->get();

    if ($schedule->isEmpty()) {
        return JsonResponse::handle(404, 'No timeslots found for this doctor on the given date', null, 404);
    }

    $timeslots = $schedule->map(function ($item) {
        return [
            'id' => $item->time->id,
            'time' => $item->time->time,
        ];
    });

    return JsonResponse::handle(200, ConstantsMessage::SUCCESS, ['date' => $date, 'timeslots' => $timeslots], 200);
}
}
    

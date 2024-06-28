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
            $users = collect($data->items());
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
              return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        }
        $user = $this->userRepository->AddUser($request);
        if ($user == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::Add, $user, 201);
    }

    Public function updateUser(Request $request,$id){
        $validator = $this->userValidation->update();
        if ($validator->fails()) {
              return JsonResponse::handle(400,ConstantsMessage::Bad_Request,$validator->messages(),400);
        }
        $user = $this->userRepository->update($request->all(),$id);
        if ($user == false) {
            return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
         return JsonResponse::handle(201, ConstantsMessage::Update, $user, 201);
    }


   
}
    

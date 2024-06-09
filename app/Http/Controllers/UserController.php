<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\User;
use App\Repositories\UserRepository;
use App\RequestValidations\UserValidation;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;


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
        $query = User::query();
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
    
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $users, 200);
    }

    Public function findById($id){
        try {
            $user = User::findOrFail($id); // Sử dụng findOrFail để ném ngoại lệ nếu không tìm thấy
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS, $user, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, ConstantsMessage::Not_Found, null, 404);
        }
    }


    Public function createUser(Request $request){
        $validator = $this->userValidation->create();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $user = $this->userRepository->AddUser($request->all());
        if ($user == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $user, 201);
    }

    Public function updateUser(Request $request){
        $validator = $this->userValidation->update();
        if ($validator->fails()) {
            return JsonResponse::error(400,$validator->messages(),400);
        }
        $user = $this->userRepository->update($request->all());
        if ($user == false) {
            return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
         return JsonResponse::handle(201, ConstantsMessage::SUCCESS, $user, 201);
    }
}
    


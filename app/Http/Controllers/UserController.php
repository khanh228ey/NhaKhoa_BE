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
        $this->middleware('check.role:3');
      
    }

    public function getUsers(Request $request)
    {
        $perPage = $request->get('limit', 10);
        $page = $request->get('page'); 
        $role = $request->get('role_id');
        $name = $request->get('name');
        $phone = $request->get('phone');
        $status = $request->get('status');
        $query = User::with('role');
        if ($role) {
            $query->where('role_id', $role);
        }
        if ($name) {
            $query->where('name', 'LIKE', "%{$name}%");
        }
        if($status) {$query->where('status',$status);}
        if($phone){
            $query->where('phone_number', 'LIKE', "%{$phone}%");
        }
        if (!is_null($page)) {
            $data = $query->paginate($perPage, ['*'], 'page', $page);
            $users = collect($data->items());
        } else {
            $users = $query->get();
        }
        $result = UserResource::collection($users);
        return JsonResponse::handle(200, ConstantsMessage::SUCCESS,$result, 200);
    }

    Public function findById($id){
        try {
            $user = User::with('schedule','role')->findOrFail($id); 
            $result = new UserResource($user);
            return JsonResponse::handle(200, ConstantsMessage::SUCCESS,  $result, 200);
        } catch (ModelNotFoundException $e) {
            return JsonResponse::handle(404, "Người dùng không tồn tại", null, 404);
        }
    }


    Public function createUser(Request $request){
        $validator = $this->userValidation->create();
        if ($validator->fails()) {
            $firstError = $validator->messages()->first();
              return JsonResponse::handle(400,$firstError,$validator->messages(),400);
        }
        $user = $this->userRepository->AddUser($request);
        if ($user == false) {
                return JsonResponse::error(401,ConstantsMessage::ERROR,401);
        }
        return JsonResponse::handle(200, ConstantsMessage::Add, $user, 200);
    }

    Public function updateUser(Request $request,$id){
        try{
            $user = User::findOrFail($id);
            if(count($request->all()) > 1 ){
                    $validator = $this->userValidation->update();
                    if ($validator->fails()) {
                        $firstError = $validator->messages()->first();
                        return JsonResponse::handle(400,$firstError,$validator->messages(),400);
                    }
            }
            $user = $this->userRepository->update($request,$user);
            return JsonResponse::handle(200, ConstantsMessage::Update, $user, 200);
        } catch (ModelNotFoundException $e) {

            return JsonResponse::handle(404, "Người dùng không tồn tại", null, 404);

        } catch (\Exception $e) {

            return JsonResponse::error(500, ConstantsMessage::ERROR, 500);

        }
    }

   
}
    

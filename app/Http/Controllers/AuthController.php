<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','refresh']]);
    }

    public function login()
    {
        $credentials = request(['phone_number', 'password']);
        $user =User::where('phone_number', $credentials['phone_number'])->first();
        if (!$user) {
            return JsonResponse::handle(401, 'Không tìm thấy tài khoản', null, 401);
        }
        if($user->status != 1){
            return JsonResponse::handle(401,'Tài khoản đã bị khóa',null,401);
        }
        if (! $token = auth()->attempt($credentials)) {
            return JsonResponse::handle(401,'Tài khoản mật khẩu chưa chính xác',null,401);
        }
        // $refreshToken = $this->createRefreshToken();
        return $this->respondWithToken($token);
    }


 

    public function logout()
    {
        auth()->logout();
        return JsonResponse::handle(200,'Đăng xuất thành công',null,200);
        
    }

    // public function refresh()
    // {
    //     $refreshToken = request()->refresh_token;
    //     try{
    //         $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
    //         $user = User::find($decoded['sub']);
    //         if(!$user){
    //             return response()->json(['error'=> "User not found"],404);
    //         }
    //         $token = auth()->user()->token;
    //         JWTAuth::invalidate($token);
    //         $token = auth()->login($user);
    //         $refreshToken = $this->createRefreshToken();
    //         return $this->respondWithToken($token,$refreshToken);
    //     }catch(JWTException $exception){
    //         return response()->json(['error'=> 'Refresh Token Invalid'],500);
    //     }
    // }


    protected function respondWithToken($token)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
        ];
        return JsonResponse::handle(200,"Đăng nhập thành công",$data,200);
    }


    public function profile()
    {
        $profile = auth()->user();
        $result = 
             [
                'id' => $profile->id,
                'name' => $profile->name,
                'email' => $profile->email,
                'avatar' => $profile->avatar,
                'phone_number' => $profile->phone_number,
                'birthday' => $profile->birthday,
                'gender' => $profile->gender,
            ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }
    
}

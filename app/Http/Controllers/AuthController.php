<?php

namespace App\Http\Controllers;

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


    public function profile()
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();
        // return response()->json(['message' => 'Successfully logged out']); 
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
        return response()->json([
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }
    
}

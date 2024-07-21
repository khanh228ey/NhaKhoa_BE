<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie ;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login','refresh']]);
    }

    public function login(Request $request)
    {
        $account = $request->input('account');
        $password = $request->input('password');
        $credentials = [
            'password' => $password,
        ];
        $userQuery = User::query();
        if (filter_var($account, FILTER_VALIDATE_EMAIL)) {
            $userQuery->where('email', $account);
            $credentials['email'] = $account;
        } else {
            $userQuery->where('phone_number', $account);
            $credentials['phone_number'] = $account;
        }
        $user = $userQuery->first();
    
        if (!$user) {
            return JsonResponse::handle(404, 'Không tìm thấy tài khoản', null, 404);
        }
        if ($user->status != 1) {
            return JsonResponse::handle(404, 'Tài khoản đã bị khóa', null, 404);
        }
        if (!$token = auth()->attempt($credentials)) {
            return JsonResponse::handle(404, 'Tài khoản hoặc mật khẩu chưa chính xác', null, 404);
        }
        $role = Hash::make($user->role->name);
        $refreshToken = $this->createRefreshToken($user);
        $cookie = Cookie::make('refresh_token', $refreshToken, 120,'/','localhost');
        $response = $this->respondWithToken($token,$role);
        return $response->withCookie($cookie);
    }

  
    private function createRefreshToken($user){
    return JWTAuth::fromUser($user, ['exp' => Carbon::now()->addDays(2)->timestamp]);
}
    
    public function logout()
    {
        auth()->logout();
        return JsonResponse::handle(200,'Đăng xuất thành công',null,200);
        
    }

    
    protected function respondWithToken($token,$role)
    {
        $data = [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60,
            'role' => $role,
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
                'role' => $profile->role->name,
            ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }
    
    // public function refresh(Request $request)
    // {
    //     $token = $request->bearerToken();
    //     try {
    //         if (JWTAuth::setToken($token)->check()) {
    //             $newToken = JWTAuth::refresh($token);
    //             $user = JWTAuth::setToken($newToken)->toUser();
    //             $newRefreshToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(30)->timestamp]);
    //             $cookie = Cookie::make('refresh_token', $newRefreshToken, 120, '/','localhost');
    //             $role = Hash::make($user->role->name);
    //             $response = $this->respondWithToken($token,$role);
    //             return $response->withCookie($cookie);
    //         }
    //     } catch (TokenExpiredException $e) {
    //         $refreshToken = $request->cookie('refresh_token');
    //         if ($refreshToken) {
    //             try {
    //                 $newToken = JWTAuth::refresh($refreshToken);
    //                 $user = JWTAuth::setToken($newToken)->toUser();
    //                 $newRefreshToken = JWTAuth::fromUser($user, ['exp' => now()->addDays(2)->timestamp]);
    //                 $cookie = Cookie::make('refresh_token', $newRefreshToken, 120, '/','localhost');
    //                 $role = Hash::make($user->role->name);
    //                 $response = $this->respondWithToken($token,$role);
    //                 return $response->withCookie($cookie);
    //             } catch (JWTException $e) {
    //                 return JsonResponse::handle(401,'Unauthorized',null,410);
    //             }
    //         } else {
    //             return JsonResponse::handle(401,'Unauthorized',null,401);
    //         }
    //     } catch (JWTException $e) {
    //         return JsonResponse::handle(401,'Unauthorized',null,401);
    //     }
    // }
    public function changePassword(Request $request)
    {
        $data = $request->all();
        $user = Auth::user();
        if (!Hash::check($data['password'], $user->password)) {
            return JsonResponse::handle(400, 'Mật khẩu cũ không đúng', null, 400);
        }
        $user->password = Hash::make($data['new_password']);
        $user->save();
        return JsonResponse::handle(200, 'Đổi mật khẩu thành công', null, 200);
    }
}

<?php

namespace App\Http\Controllers;

use App\Commons\Messages\ConstantsMessage;
use App\Commons\Responses\JsonResponse;
use App\Models\User;
use Carbon\Carbon;
use Exception;
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
        $cookie = Cookie::make('refresh_token', $refreshToken, 7,'/','localhost');
        $response = $this->respondWithToken($token,$role);
        return $response->withCookie($cookie);
    }

  
    private function createRefreshToken($user){
    return JWTAuth::fromUser($user, ['exp' => Carbon::now()->addDays(7)->timestamp]);
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
    
    public function refresh(Request $request)
    {
        try {
            $refreshToken = Cookie::get('refreshToken');
            if (!$refreshToken) {
                return JsonResponse::handle(401,'Phiên đăng nhập hết hạn',null,401);
            }
            try {
                $token = JWTAuth::refresh($refreshToken);
                // Lưu refresh token mới vào cookie
                // Cookie::queue('refreshToken', $newToken, config('jwt.refresh_ttl'));
                // Trả về access token mới
                // return response()->json(['token' => $newToken]);
                return $this->respondWithToken($token,null);
            } catch (JWTException $e) {
                return JsonResponse::handle(401,'Phiên đăng nhập hết hạn',null,401);
            }
        } catch (Exception $e) {
            // Trả về lỗi chung nếu có lỗi xảy ra
            return JsonResponse::handle(500,'Phiên đăng nhập hết hạn',null,500);
        }
    }

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

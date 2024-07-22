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
        $refreshToken = $this->createRefreshToken($token);
        $cookie = Cookie::make('refresh_token', $refreshToken, 60 * 24 * 24, '/', null, true, false, false, 'none');
        $response = $this->respondWithToken($token,$role);
        return $response->withCookie($cookie);
    }

  
    private function createRefreshToken($user)
    {
        
        $ttl = config('jwt.refresh_ttl'); 
        $payload = JWTAuth::getPayloadFactory()->customClaims([
            'exp' => Carbon::now()->addMinutes($ttl)->timestamp,
        ])->make();
        return JWTAuth::encode($payload)->__toString();
    
    }
    public function logout()
    {
        auth()->logout();
        $cookie = Cookie::forget('refresh_token');
        return JsonResponse::handle(200,'Đăng xuất thành công',null,200)->withCookie($cookie);
        
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
                'address' => $profile->address,
                'role' => $profile->role->name,
            ];
        return JsonResponse::handle(200,ConstantsMessage::SUCCESS,$result,200);
    }
    
    public function refresh(Request $request)
    {
        $refreshToken = $request->cookie('refresh_token');
        if (!$refreshToken) {
            return JsonResponse::handle(401, 'Phiên đăng nhập hết hạn', 1, 401);
        }
        try {
            JWTAuth::setToken($refreshToken);
            $user = JWTAuth::authenticate(); 
            if (!$user) {
                return JsonResponse::handle(401, 'Token không hợp lệ', 2, 401);
            }
            $newToken = JWTAuth::fromUser($user);
            return $this->respondWithToken($newToken, null);
            
        } catch (TokenExpiredException $e) {
            return JsonResponse::handle(401, 'Refresh token đã hết hạn', 3, 401);
        } catch (JWTException $e) {
            return JsonResponse::handle(401, 'Token không hợp lệ', 4, 401);
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

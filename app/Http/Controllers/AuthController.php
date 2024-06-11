<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        if (! $token = auth()->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        $refreshToken = $this->createRefreshToken();
        return $this->respondWithToken($token,$refreshToken);
    }


    public function profile()
    {
        return response()->json(auth()->user());
    }


    public function logout()
    {
        auth()->logout();
        return response()->json(['message' => 'Successfully logged out']); 
    }

    public function refresh()
    {
        $refreshToken = request()->refresh_token;
        try{
            $decoded = JWTAuth::getJWTProvider()->decode($refreshToken);
            $user = User::find($decoded['sub']);
            if(!$user){
                return response()->json(['error'=> "User not found"],404);
            }
            $token = auth()->user()->token;
            JWTAuth::invalidate($token);
            $token = auth()->login($user);
            $refreshToken = $this->createRefreshToken();
            return $this->respondWithToken($token,$refreshToken);
        }catch(JWTException $exception){
            return response()->json(['error'=> 'Refresh Token Invalid'],500);
        }
    }


    protected function respondWithToken($token,$refreshToken)
    {
        return response()->json([
            'access_token' => $token,
            'refresh_token' => $refreshToken,
            'token_type' => 'bearer',
            'expires_in' => Auth::factory()->getTTL() * 60
        ]);
    }

    protected function createRefreshToken(){
        $data = [
            'sub' => Auth::user()->id,
            'random' => rand().time(),
            'exp'=> time() + config('jwt.refresh_ttl'),
            ];
        $refreshToken = JWTAuth::getJWTProvider()->encode($data);
        return $refreshToken;
    }
}

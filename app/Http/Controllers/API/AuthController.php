<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Symfony\Component\HttpFoundation\Response;
use App\Models\User;
class AuthController extends Controller
{
    // 회원가입
    public function register(Request $request) {

        $valid = validator($request->only('email', 'name', 'password'), [
           'email' => 'required|string|email|max:255|unique:users',
           'name' => 'required|string|max:255',
           'password' => 'required|string|max:6',
        ]);

        // 필수 입력 값들에 대한 유효성 검사
        if ($valid->fails()) {
            return response()->json([
                'error' => $valid->errors()->all()
            ], Response::HTTP_BAD_REQUEST);
        }

        $data = request()->only('email', 'name', 'password');

        // 사용자 생성
        $user = \App\Models\User::create([
           'name' => $data['name'],
           'email' => $data['email'],
           'password' => bcrypt($data['password']),
        ]);

        $token = $this->createToken($data['email'], $data['password']);

        return response([
           'token' => $token,
           'message' => 'success'
        ], 201);
        return $token;
    }


    public function login(Request $request) {
        $user = User::where('email',$request->user_id)->first();
        $token = $user->createToken('access-token');
        return response([
            'token' => $token,
            'user' => $user,
            'message' => 'login success'
        ], 200);
    }

    public function user(Request $request)
    {
        return response()->json($request->user());
    }
}

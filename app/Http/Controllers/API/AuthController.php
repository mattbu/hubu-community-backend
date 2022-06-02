<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
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

//        $token = $this->createToken($data['email'], $data['password']);
        $token = $user->createToken('access-token');
        return response([
           'token' => $token,
           'message' => 'success'
        ], 201);
    }


    public function login(Request $request) {
        $user = User::where('email',$request->user_id)->first();

        abort_unless($user, 403, '이메일 또는 비밀번호를 다시 확인해 주세요.');
        abort_unless(
            Hash::check(request('password'), $user->password),
            403,
            '이메일 또는 비밀번호를 다시 확인해 주세요!'
        );

        $token = $user->createToken('access-token');
        return response([
            'token' => $token,
            'user' => $user,
            'message' => 'login success'
        ], 200);
    }
}

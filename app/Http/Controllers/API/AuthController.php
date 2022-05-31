<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use http\Client\Curl\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client;
use Symfony\Component\HttpFoundation\Response;

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

        return $token;
    }

    public function createToken ($userId, $password): string|Response {
        $credentials = array(
            'email' => $userId,
            'password' => $password
        );

        $data = [
            'grant_type' => 'password',
            'client_id' => '2',
            'client_secret' => config('services.passport.secret'),
            'username' => $userId,
            'password' => $password,
            'scope' => '*',
        ];
        $request = Request::create('/oauth/token', 'POST', $data);
        return app()->handle($request);
    }

    public function login(Request $request) {
        $request->validate([
            'user_id' => 'required|string',
            'password' => 'required|string',
        ]);
        return $this->createToken($request->user_id, $request->password);
    }
}

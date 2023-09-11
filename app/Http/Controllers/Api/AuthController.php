<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\BadCredentials;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Services\UserService;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login', 'refresh', 'register']]);
    }

    public function register(RegisterRequest $request)
    {
        $data = $request->validated();
        $user = UserService::createUser($data);

        $token = auth()->login($user);
        return $this->respondWithToken($token);
    }

    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();
        if (!$token = auth()->attempt($credentials)) throw new BadCredentials();

        return $this->respondWithToken($token);
    }

    public function me()
    {
        $user = auth()->user();

        return response()->json([
            'success' => true,
            'data' => collect($user)->except(['created_at', 'updated_at'])
        ])
            ->header('Charset', 'utf-8')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function logout()
    {
        auth()->logout();

        return response()->json([
            'success' => true,
            'message' => 'Вы успешно вышли из своего аккаунта'
        ])
            ->header('Charset', 'utf-8')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }

    public function refresh()
    {
        return $this->respondWithToken(auth()->refresh());
    }

    protected function respondWithToken($token)
    {
        if (!empty(auth()->user()->deleted_at)) {
            return response()->json([
                'success' => false,
                'message' => 'Ваш аккаунт заблокирован или удалён'
            ])
                ->header('Charset', 'utf-8')
                ->setEncodingOptions(JSON_UNESCAPED_UNICODE);;
        }

        return response()->json([
            'success' => true,
            'data' => [
                'access_token' => $token,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ]
        ])
            ->header('Charset', 'utf-8')
            ->setEncodingOptions(JSON_UNESCAPED_UNICODE);
    }
}

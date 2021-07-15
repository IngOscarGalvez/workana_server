<?php

namespace App\Http\Controllers\Api\v1\authentication;

use Carbon\Carbon;
use App\Models\User;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Api\v1\authentication\AuthRequest;
use App\Http\Requests\Api\v1\authentication\RegisterRequest;

class AuthenticationController extends Controller
{
    use ApiResponser;

    /**
     * login user
     *
     * @param  mixed $request
     * @return void
     */
    public function login(AuthRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (!Auth::attempt($credentials)) {
            return response()->json([
                'message' => 'User or Password are invalid.'
            ], 401);
        }
        $access_token = Auth::user()->createToken(config('SECRET_KEY'));
        $expiration = $access_token->token->expires_at->diffInSeconds(Carbon::now());

        return $this->successResponse('ok', 202, 'login success', [
            'user' => Auth::user(),
            'access_token' => $access_token,
            'token_expiration_in_seconds' => $expiration
        ]);
    }

    /**
     * register user
     *
     * @param  mixed $request
     * @return Response
     */
    public function register(RegisterRequest $request)
    {
        $password = Hash::make($request->password);
        $user_data = array_merge($request->except(['password', 'confirmation_password']), ['password' => $password]);
        $user = User::create($user_data);

        $access_token = $user->createToken(config('SECRET_KEY'));

        return $this->successResponse('ok', 201, 'User created successfully', [
            'user' => $user->only('id', 'name', 'email'),
            'access_token' => $access_token
        ]);
    }

    /**
     * revokeToken
     *
     * @param Request $request
     * @return Response
     */
    public function revokeToken(Request $request)
    {
        $result = $request->user()->token()->revoke();
        return $this->successResponse('ok', 202, 'User logout success', [
            'logout' => $result
        ]);
    }
}

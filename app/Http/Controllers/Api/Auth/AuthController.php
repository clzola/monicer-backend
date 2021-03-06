<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\TokenResource;
use App\Http\Resources\UserResource;
use App\Support\Auth\RespondWithTokenTrait;
use App\User;

class AuthController extends Controller
{
    use RespondWithTokenTrait;

    /**
     * Create a new AuthController instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login', 'refresh']]);
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return TokenResource
     */
    public function login()
    {
        $credentials = request(['email', 'password']);
        if (!$token = auth('api')->attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $user = User::where('email', $credentials['email'])->first();
        $user->load('wallet');
        if($user->role === User::ROLE_SHOP)
            $user->load('shop');

        $stdToken = new \stdClass();
        $stdToken->access_token = $token;
        $stdToken->token_type = 'bearer';
        $stdToken->user = $user;

        return new TokenResource($stdToken);
    }

    /**
     * Get the authenticated User.
     *
     * @return UserResource
     */
    public function me()
    {
        /** @var User $user */
        $user = auth('api')->user();
        $user->load('wallet');
        if($user->role === User::ROLE_SHOP)
            $user->load('shop');
        return new UserResource($user);
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function logout()
    {
        auth('api')->logout();
        return response()->json(['message' => 'Successfully logged out']);
    }

    /**
     * Refresh a token.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function refresh()
    {
        return $this->respondWithToken(auth('api')->refresh());
    }
}

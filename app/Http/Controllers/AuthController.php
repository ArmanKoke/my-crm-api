<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User as UserResource;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource
    {
        $user = new User();
        if($request->is_social) {
            //add other from api
            $user->is_social = $request->is_social;
        } else {
            $user->email = $request->email;
            $user->password = ($request->password);
        }
        $user->save();

        return new UserResource($user);
    }

    /**
     * @param LoginRequest $request
     * @return JsonResponse
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        if (!$userId = $this->guard()->attempt($credentials)) {
            return response()->json(['status' => 'error','message' => 'Log in error, check credentials!'], 401);
        }

        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first(); //could assign different client according to resource

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => Auth::user()->email,
            'password' => $request->password, //todo for now open password should change
            'scope' => '*' //could be regulated too
        ];

        $tokenRequest = Request::create(route('passport.token'), 'POST', $data);

        return app()->handle($tokenRequest);
    }

    /**
     * @return JsonResponse
     */
    public function logout(): JsonResponse
    {
        //todo make token revoke
        $this->guard()->logout();
        return response()->json([
            'status' => 'success',
            'message' => 'Logged out Successfully.'
        ], 200);
    }

    /**
     * @return JsonResponse
     */
    public function user(): JsonResponse
    {
        $user = User::find(Auth::user()->id);
        return response()->json([
            'status' => 'success',
            'data' => $user
        ]);
    }

    /**
     * @return JsonResponse
     */
    public function refresh(): JsonResponse
    {
        if ($userId = $this->guard()->refresh()) {
            return response()
                ->json(['status' => 'success'], 200)
                ->header('Authorization', $userId);
        }
        return response()->json(['status' => 'error','message' => 'Token refresh error'], 401);
    }

    /**
     * @return Guard|StatefulGuard
     */
    private function guard()
    {
        return Auth::guard();
    }
}

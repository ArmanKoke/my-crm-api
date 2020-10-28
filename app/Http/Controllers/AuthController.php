<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Http\Resources\User as UserResource;
use App\Models\Social;
use App\Models\User;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Socialite\Contracts\User as SocialiteUser;
use Laravel\Socialite\Facades\Socialite;
use Symfony\Component\HttpFoundation\RedirectResponse;

class AuthController extends Controller
{
    /**
     * @param RegisterRequest $request
     * @return UserResource
     */
    public function register(RegisterRequest $request): UserResource //todo one register method
    {
        $user = User::updateOrCreate(['email' => $request->email]);
        $user->email = $request->email;
        $user->password = ($request->password);
        $user->save();

        $this->guard()->login($user);

        $token = $this->generateToken($request->password); //todo return auth

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

        $token = $this->generateToken($request->password);
        dd($token);

    }

    public function registerWithSocial(SocialiteUser $userData)
    {
        $email = $userData->getEmail();
        $user = User::where('email',$email)->first();

        if (!$user) {
            $user = new User();
            $user->name = $userData->getName();
            $user->email = $email;
            $user->is_social = true;
            $user->save();
        }

        $social = new Social();
        //$social->email = $email;
        $social->social_network_id = 1; //todo change
        $social->data = json_encode($userData, true);
        $social->save();

        $social->users()->attach($social);

        //auth
        $this->guard()->login($user);

        $token = $this->generateToken($user->password);
        dd($token);

        return new UserResource($user);
    }

    /**
     * @param null $password
     * @return mixed
     */
    public function generateToken($password = null)
    {
        $client = DB::table('oauth_clients')
            ->where('password_client', true)
            ->first(); //could assign different client according to resource

        $data = [
            'grant_type' => 'password',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'username' => Auth::user()->email,
            'password' => $password, //todo for now open password should change
            'scope' => '*' //could be regulated too
        ];

        $tokenRequest = Request::create(route('passport.token'), 'POST', $data);

        return app()->handle($tokenRequest);
    }

    /**
     * @return SocialiteUser
     */
    public function handleProviderCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
        } catch (\Exception $exception) {
            return response()->json([
                'status' => 'error',
                'message' => $exception->getMessage()
            ],$exception->getCode());
        }

        $a = $this->registerWithSocial($user);

        return $user;
    }

    /**
     * @return RedirectResponse
     */
    public function redirectToProvider()
    {
        return Socialite::driver('google')->redirect();
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

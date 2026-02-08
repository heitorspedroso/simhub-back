<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Api\AuthRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

use App\Models\User;
use App\Http\Controllers\ActivityController;

class AuthController extends BaseController
{

    public function __construct()
    {
        // $this->middleware('auth:sanctum')->only(['all']);
        // $this->middleware('auth:sanctum', ['except' => ['login', 'register']]);
    }

    public function login(AuthRequest $request)
    {
        $user = User::select('USR_ID')
            ->where('USR_SENHA', '=', $request->password)
            ->where('USR_ATIVO', '=', 'S');

        if (str_contains($request->username, '@'))
            $user = $user->where('USR_EMAIL', '=', $request->username);
        else
            $user = $user->where('USR_USER', '=', $request->username);


        $sql = $user->toSql();

        $user = $user->first();

        // if ( ! Hash::check($request->password, $user->password)) {
        if (!$user) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Login',
                'activity' => 'Credenciais incorretas',
                'query' => $sql,
                'httpCode' => 401,
                'return' => '',
                'ip' => $request->ip()
            ]);

            return response([
                'auth' => 'Credenciais incorretas' // Invalid Credentials
            ], 401);
        }

        $device = $request->device ?? 'device_unknown';

        $token = $user->createToken($device)->plainTextToken;   // ($device, ['x', 'y', 'z'])

        if (!$token) {

            return response([
                'auth' => 'Falha ao criar o token. Tente novamente mais tarde'  // Fail on create token
            ], 500);
        }

        ActivityController::store([
            'user' => $user,
            'module' => 'Login',
            'activity' => 'Login Realizado',
            'query' => $sql,
            'httpCode' => 200,
            'ip' => $request->ip()
        ]);

        return response(['token' => $token], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();
        // $request->user()->tokens()->delete();

        return true;

        // $user->tokens()->where('id', $tokenId)->delete();
    }

    public function register(Request $request)
    {
        $register = $request->only('name', 'email', 'password');

        // $pass = bcrypt($user->pass);

        // return $request->user()->createToken($device, ['x', 'y', 'z'])->plainTextToken

    }

    public function forgotPassword(Request $request)
    {
        //    $request->validate([
        //         'email' => 'required|email',
        //    ]);

        //    $status = Password::sendResetLink(
        //        $request->only('email')
        //    );

        //    return $status === Password::RESET_LINK_SENT
        //        ? back()->with('status', __($status))
        //        : back()->withInput($request->only('email'))->withErrors(['email' => __($status)]);
    }
}



// https://www.youtube.com/watch?v=sa3u4Nyrjcg
// Curso de Laravel 10 - #40 - Autenticação de APIs no Laravel com Sanctum

        // if($request->has('logout_others_devices'))
        //     $user->tokens()->delete();

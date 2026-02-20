<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller as BaseController;
use App\Http\Requests\Api\AuthRequest;
use App\Mail\RecoverPasswordMail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

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

        if( str_contains($request->username, '@') )
            $user = $user->where('USR_EMAIL', '=' , $request->username);
        else
            $user = $user->where('USR_USER', '=' , $request->username);


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

        if(!$token){

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
//        $request->user()->tokens()->delete();

        return true;

        // All
        // $user->tokens()->delete();

        // Specific
        // $user->tokens()->where('id', $tokenId)->delete();
    }

    /**
     * Solicita código de recuperação de senha
     * POST /auth/recover/request
     */
    public function recoverRequest(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255'
        ]);

        // Verificar se email existe e usuário está ativo
        $user = User::where('USR_EMAIL', $request->email)
            ->where('USR_ATIVO', 'S')
            ->first();

        if (!$user) {

            // Log de tentativa com email inexistente
//            ActivityController::store([
//                'user' => null,
//                'module' => 'Recuperação de Senha',
//                'activity' => 'Email não encontrado: ' . $request->email,
//                'query' => '',
//                'httpCode' => 404,
//                'ip' => $request->ip()
//            ]);

            return response()->json([
                'success' => false,
                'message' => 'Email não encontrado'
            ], 404);
        }

        // Gerar código aleatório de 6 dígitos
        $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        try {
            // Atualizar usuário com código e expiração (30 minutos)
            DB::table('USUARIOS')
                ->where('USR_ID', $user->USR_ID)
                ->update([
                    'USR_CODIGO_RECUPERACAO' => $code,
                    'USR_CODIGO_EXPIRACAO' => now()->addMinutes(30)
                ]);


             try {
                 Mail::to($request->email)->send(new RecoverPasswordMail($code, $user->USR_NOME));
             } catch (\Exception $e) {
                 \Log::error('Erro ao enviar email de recuperação: ' . $e->getMessage());
             }

            // Log de sucesso
            ActivityController::store([
                'user' => null,
                'module' => 'Recuperação de Senha',
                'activity' => 'Código solicitado para: ' . $request->email,
                'query' => '',
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Código enviado para seu email',
            ]);

        } catch (\Exception $e) {

            // Log do erro
//            ActivityController::store([
//                'user' => null,
//                'module' => 'Recuperação de Senha',
//                'activity' => 'Erro ao gerar código: ' . $e->getMessage(),
//                'query' => '',
//                'httpCode' => 500,
//                'ip' => $request->ip()
//            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar solicitação. Tente novamente.'
            ], 500);
        }
    }

    /**
     * Reseta a senha usando o código recebido
     * POST /auth/recover/reset
     */
    public function recoverReset(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:255',
            'code' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        try {
            // Buscar usuário com código válido (não expirado)
            $user = User::where('USR_EMAIL', $request->email)
                ->where('USR_ATIVO', 'S')
                ->where('USR_CODIGO_RECUPERACAO', $request->code)
                ->first();

            if (!$user) {

                // Log de tentativa com código inválido
                ActivityController::store([
                    'user' => null,
                    'module' => 'Recuperação de Senha',
                    'activity' => 'Código inválido para: ' . $request->email,
                    'query' => '',
                    'httpCode' => 400,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Código inválido'
                ], 400);
            }

            // Verificar se o código expirou
            if ($user->USR_CODIGO_EXPIRACAO && $user->USR_CODIGO_EXPIRACAO < now()) {

                // Limpar código expirado
                DB::table('USUARIOS')
                    ->where('USR_ID', $user->USR_ID)
                    ->update([
                        'USR_CODIGO_RECUPERACAO' => null,
                        'USR_CODIGO_EXPIRACAO' => null
                    ]);

                // Log de código expirado
                ActivityController::store([
                    'user' => null,
                    'module' => 'Recuperação de Senha',
                    'activity' => 'Código expirado para: ' . $request->email,
                    'query' => '',
                    'httpCode' => 400,
                    'ip' => $request->ip()
                ]);

                return response()->json([
                    'success' => false,
                    'message' => 'Código expirado. Solicite um novo código'
                ], 400);
            }

            // Atualizar senha e limpar código
            DB::table('USUARIOS')
                ->where('USR_ID', $user->USR_ID)
                ->update([
                    'USR_SENHA' => $request->password,
                    // Para usar com hash: 'USR_SENHA' => bcrypt($request->password)
                    'USR_CODIGO_RECUPERACAO' => null,
                    'USR_CODIGO_EXPIRACAO' => null
                ]);

            // Invalidar todos os tokens do usuário (força novo login)
            DB::table('personal_access_tokens')
                ->where('tokenable_id', $user->USR_ID)
                ->where('tokenable_type', 'App\\Models\\User')
                ->delete();

            // Log de sucesso
            ActivityController::store([
                'user' => $user,
                'module' => 'Recuperação de Senha',
                'activity' => 'Senha alterada via recuperação',
                'query' => '',
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Senha alterada com sucesso'
            ]);

        } catch (\Exception $e) {

            // Log do erro
//            ActivityController::store([
//                'user' => null,
//                'module' => 'Recuperação de Senha',
//                'activity' => 'Erro ao resetar senha: ' . $e->getMessage(),
//                'query' => '',
//                'httpCode' => 500,
//                'ip' => $request->ip()
//            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erro ao processar solicitação. Tente novamente.'
            ], 500);
        }
    }

    public function register(Request $request)
    {
        $register = $request->only('name', 'email', 'password');

        // $pass = bcrypt($user->pass);

        // return $request->user()->createToken($device, ['x', 'y', 'z'])->plainTextToken

    }
}



// https://www.youtube.com/watch?v=sa3u4Nyrjcg
// Curso de Laravel 10 - #40 - Autenticação de APIs no Laravel com Sanctum

// if($request->has('logout_others_devices'))
//     $user->tokens()->delete();

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class UserController extends BaseController
{
    public function all(Request $request)
    {
        if ($request->user()->USR_MASTER === 'S') {
            return User::get();
        }

        // return User::limit(1000)->orderBy('USR_NOME')->get();
    }

    public function me()
    {
        return request()->user();
    }

    public function store(Request $request, $email = '') // ver
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $userId = User::select('USR_ID')->max('USR_ID') + 1;

        $user = new User();
        $user->USR_ID = $userId;
        $user->USR_USER = $request->input('USR_USER');
        $user->USR_NOME = $request->input('USR_NOME');
        $user->USR_EMAIL = $request->input('USR_EMAIL');
        $user->USR_SENHA = $request->input('USR_SENHA');
        $user->USR_MASTER = $request->input('USR_MASTER');
        // bcrypt('password123');
        // $user->password = Hash::make( '123456' );  // $request->input('password')

        $user->USR_CEP = $request->input('USR_CEP');
        $user->USR_ESTADO = $request->input('USR_ESTADO');
        $user->USR_CIDADE = $request->input('USR_CIDADE');
        $user->USR_ENDERECO = $request->input('USR_ENDERECO');
        $user->USR_NUMERO = $request->input('USR_NUMERO');
        $user->USR_BAIRRO = $request->input('USR_BAIRRO');
        $user->USR_COMPLEMENTO = $request->input('USR_COMPLEMENTO');
        $user->USR_FONE = $request->input('USR_FONE');
        // $user->USR_CPF = ;
        // $user->USR_RG = ;
        $user->USR_DATA_CADASTRO = now();
        // $user->USR_DATA_NASC = ;
        $user->USR_ATIVO = $request->input('USR_ATIVO');

        $save = $user->save();

        if ($save) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Usuário',
                'activity' => 'Usuário Criado',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        }

        ActivityController::store([
            'user' => $request->user(),
            'module' => 'Usuário',
            'activity' => 'Erro ao criar Usuário',
            'query' => '', // $sql,
            'httpCode' => 400,
            'ip' => $request->ip()
        ]);

        return false;

        // $user = User::findOrFail($userId);

    }

    public function getPut(Request $request, $id)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        return User::find($id);
    }

    public function update(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $user = User::findOrFail($request->USR_ID);
        $user->USR_USER = $request->input('USR_USER');
        $user->USR_NOME = $request->input('USR_NOME');
        // $user->USR_NOME_REDUZIDO = $request->input('USR_NOME_REDUZIDO');
        $user->USR_EMAIL = $request->input('USR_EMAIL');

        if ($request->input('USR_SENHA'))
            $user->USR_SENHA = $request->input('USR_SENHA');

        $user->USR_MASTER = $request->input('USR_MASTER');
        $user->USR_CEP = preg_replace('/[^0-9]/', '', $request->input('USR_CEP'));
        $user->USR_ESTADO = $request->input('USR_ESTADO');
        $user->USR_CIDADE = $request->input('USR_CIDADE');
        $user->USR_ENDERECO = $request->input('USR_ENDERECO');
        $user->USR_NUMERO = $request->input('USR_NUMERO');
        $user->USR_BAIRRO = $request->input('USR_BAIRRO');
        $user->USR_COMPLEMENTO = $request->input('USR_COMPLEMENTO');
        $user->USR_FONE = preg_replace('/[^0-9]/', '', $request->input('USR_FONE'));
        // $user->USR_CONTATO = $request->input('USR_CONTATO');
        $user->USR_ATIVO = $request->input('USR_ATIVO');
        // $user->USR_OBSERVACOES = $request->input('USR_OBSERVACOES');

        $save = $user->save();

        if ($save) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Usuário',
                'activity' => 'Usuário alterado',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        }

        ActivityController::store([
            'user' => $request->user(),
            'module' => 'Usuário',
            'activity' => 'Erro ao alterar Usuário',
            'query' => '', // $sql,
            'httpCode' => 400,
            'ip' => $request->ip()
        ]);

        return false;
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $result = false;

        DB::transaction(function () use ($request, $id, &$result) {

            try {
                $user = User::find($id);

                $user->client()->detach();

                $user->device()->detach();

                $user->destroy($id); // ->delete();

                ActivityController::store([
                    'user' => $request->user(),
                    'module' => 'Usuário',
                    'activity' => 'Usuário excluído',
                    'query' => '', // $sql,
                    'httpCode' => 200,
                    'ip' => $request->ip()
                ]);

                $result = true;
            } catch (Exception $e) {

                ActivityController::store([
                    'user' => $request->user(),
                    'module' => 'Usuário',
                    'activity' => 'Erro ao excluir Usuário',
                    'query' => '', // $sql,
                    'httpCode' => 400,
                    'ip' => $request->ip()
                ]);
            }
        }, 3);

        if ($result) {
            return true;
        } else {
            return ['err' => 'Falha ao remover ou retirar permissão'];
        }
    }

    public function exists(Request $request, $user)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $user = User::where('USR_NOME', $user)->first('USR_ID');

        if ($user) return true;

        return false;
    }
}

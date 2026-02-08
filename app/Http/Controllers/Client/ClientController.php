<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\ActivityController;
use App\Http\Controllers\Controller as BaseController;
use App\Models\Client;
use App\Models\User;
use App\Services\ClientService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class ClientController extends BaseController
{
    public function index(Request $request)
    {
        return ClientService::getAll($request->user());
    }

    public function show(Request $request, $id)
    {
        if ($request->user()->USR_MASTER === 'S')
            return Client::find($id);
        else
            return $request->user()->client()->find($id);
    }

    public function store(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $this->valid($request);

        $this->uploadImage($request);

        // $role = ['S', 'M', 'N'];

        // if(!in_array($request->USR_MASTER, $role))
        //     throw new \ErrorException("Permissão de usuário: $request->USR_MASTER inválida", 1);

        // try {

        // DB::transaction(function () use ($request) {

        // $email = $request->input('CLI_EMAIL');
        $request->request->remove('CLI_EMAIL');

        $cliId = Client::select('CLI_ID')->max('CLI_ID') + 1;

        $request->request->add(['CLI_ID' => $cliId]);

        $client = Client::create($request->all());

        if ($client) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Cliente',
                'activity' => 'Cliente criado',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        } else {
            return false;
        }

        // $user->client()->attach($cliId);
    }

    private function uploadImage(Request &$request)
    {
        // if($request->hasFile('file')){
        if ($request->file('file')) {
            $file = $request->file('file');
            $filename = str_replace(' ', '-', $request->CLI_NOME) . '_' . date('YmdHi') . '.' . $file->extension();

            $file->move(env('DIR_IMG'), $filename);

            // if($request->file)
            //     $file->move( env('DIR_IMG'), $filename );
            // $file->storeAs(env('DIR_IMG'), $filename);

            $request->request->add(['CLI_FOTO' => $filename]);
        }
    }

    public function update(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        // return $request;
        // return $request->request;
        // return $request->getContent();
        // return $request->all();
        // return $request->file('file');
        // return $request->input('CLI_NOME');

        $this->valid($request);

        $this->uploadImage($request);

        // return $request->all();

        $client = Client::findOrFail($request->CLI_ID);
        $client->CLI_NOME = $request->input('CLI_NOME');
        $client->CLI_NOME_REDUZIDO = $request->input('CLI_NOME_REDUZIDO');
        $client->CLI_RAZAO_SOCIAL = $request->input('CLI_RAZAO_SOCIAL');
        $client->CLI_NOME_FANTASIA = $request->input('CLI_NOME_FANTASIA');
        $client->CLI_CEP = preg_replace('/[^0-9]/', '', $request->input('CLI_CEP'));
        $client->CLI_ESTADO = $request->input('CLI_ESTADO');
        $client->CLI_CIDADE = $request->input('CLI_CIDADE');
        $client->CLI_ENDERECO = $request->input('CLI_ENDERECO');
        $client->CLI_NUMERO = $request->input('CLI_NUMERO');
        $client->CLI_BAIRRO = $request->input('CLI_BAIRRO');
        $client->CLI_COMPLEMENTO = $request->input('CLI_COMPLEMENTO');
        $client->CLI_FONE = preg_replace('/[^0-9]/', '', $request->input('CLI_FONE'));
        $client->CLI_CONTATO = $request->input('CLI_CONTATO');
        $client->CLI_ATIVO = $request->input('CLI_ATIVO');
        $client->CLI_OBSERVACOES = $request->input('CLI_OBSERVACOES');
        $client->CLI_FOTO = $request->input('CLI_FOTO') ?? null;

        if ($client->save()) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Cliente',
                'activity' => 'Cliente atualizado',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        }
        // if( $client->save() )
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $client = Client::destroy($id);

        if ($client) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Cliente',
                'activity' => 'Cliente excluido',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        }

        return false;
    }

    private function valid(Request &$request)
    {
        $request->validate([
            'CLI_NOME' => 'required|max:255',
            'CLI_NOME_REDUZIDO' => '',
            'CLI_RAZAO_SOCIAL' => 'max:100',
            'CLI_NOME_FANTASIA' => 'max:100',
            'CLI_CEP' => 'max:12',
            'CLI_ESTADO' => '',
            'CLI_CIDADE' => '',
            'CLI_ENDERECO' => '',
            'CLI_NUMERO' => '',
            'CLI_BAIRRO' => '',
            'CLI_COMPLEMENTO' => '',
            'CLI_FONE' => '',
            'CLI_CONTATO' => '',
            'CLI_ATIVO' => '',
            'CLI_OBSERVACOES' => '',
            'file' => 'nullable|image|mimes:jpeg,jpg,png,gif,svg,webp|max:25600'
            // 'USR_MASTER' => 'required',
        ]);
    }
}

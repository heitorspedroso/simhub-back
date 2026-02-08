<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Client;
use App\Models\Device;
use App\Models\PermissionUserClient;
use App\Models\PermissionUserDevice;
use Illuminate\Http\Request;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;

class PermissionController extends BaseController
{

    public function getUserClientFront(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $get['user'] = User::select(['USR_ID', 'USR_NOME'])->orderBy('USR_NOME')->get();
        $get['client'] = Client::select(['CLI_ID', 'CLI_NOME'])->orderBy('CLI_NOME')->get();
        $get['relation'] = PermissionUserClient::select(['PRM_USR_ID', 'PRM_CLI_ID'])->get();

        // $get['user'] = $get['user']->reduce(function($carry, $d){
        //     $carry[$d['USR_ID']] = $d['USR_NOME'] ?? '';
        //     return $carry;
        // }, []);

        // $get['client'] = $get['client']->reduce(function($carry, $d){
        //     $carry[$d['CLI_ID']] = $d['CLI_NOME'] ?? '';
        //     return $carry;
        // }, []);

        // $get['relation'] = $get['relation']->reduce(function($carry, $d){
        //     $carry[$d['PRM_USR_ID']][] = $d['PRM_CLI_ID'] ?? '';
        //     return $carry;
        // }, []);

        return $get;
    }

    // public function getUserDeviceFront(Request $request)
    // {
    //     if($request->user()->USR_MASTER !== 'S') return;

    //     $get['user'] = User::select(['USR_ID','USR_NOME'])->orderBy('USR_NOME')->with('')->get();
    //     $get['client'] = Client::select(['CLI_ID','CLI_NOME'])->orderBy('CLI_NOME')->get();
    //     $get['relation'] = PermissionUserClient::select(['PRM_USR_ID','PRM_CLI_ID'])->get();

    //     $get['user'] = $get['user']->reduce(function($carry, $d){
    //         $carry[$d['USR_ID']] = $d['USR_NOME'] ?? '';
    //         return $carry;
    //     }, []);

    //     $get['client'] = $get['client']->reduce(function($carry, $d){
    //         $carry[$d['CLI_ID']] = $d['CLI_NOME'] ?? '';
    //         return $carry;
    //     }, []);

    //     // $get['relation'] = $get['relation']->reduce(function($carry, $d){
    //     //     $carry[$d['PRM_USR_ID']][] = $d['PRM_CLI_ID'] ?? '';
    //     //     return $carry;
    //     // }, []);

    //     return $get;
    // }

    public function getUserAll(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        return User::select(['USR_ID', 'USR_NOME'])->orderBy('USR_NOME')->get();
    }

    public function getClientAll(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        return User::select(['USR_ID', 'USR_NOME'])->with('client', function ($q) {
            return $q->select(['CLI_ID', 'CLI_NOME'])->orderBy('CLI_NOME');
        })->orderBy('USR_NOME')->get();
    }

    public function getDeviceClient(Request $request, $id)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $idsCli = Client::select('CLIENTES.CLI_ID')
            ->join('PERM_USR_CLI', 'PERM_USR_CLI.PRM_CLI_ID', '=', 'CLIENTES.CLI_ID')
            ->where('PERM_USR_CLI.PRM_USR_ID', '=', $id)
            ->get() ?? [];

        $idsCli = $idsCli->reduce(function ($in, $id) {
            $in[] = $id['CLI_ID'];
            return $in;
        }, []);

        return Device::select(['EQP_ID', 'EQP_NOME'])
            ->whereIn('EQP_CLI_ID', $idsCli)
            ->where('EQP_GATEWAY', 'N')
            ->get();
    }

    public function setClient(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $request->validate([
            'user' => 'required|int|max:1000000',
            'client' => 'required|int|max:1000000',
        ]);

        try {
            $user = User::find($request->user);
            $user->client()->attach($request->client);

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Permissão Usuário/Cliente',
                'activity' => 'Permissão concedida',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        } catch (Exception $e) {
            return ['err' => 'Falha ao dar permissão'];
        }
    }

    public function removeClient(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $request->validate([
            'user' => 'required|int|max:1000000',
            'client' => 'required|int|max:1000000',
        ]);

        $result = false;

        DB::transaction(function () use ($request, &$result) {

            try {
                $user = User::find($request->user);

                $user->device()->detach();

                $user->client()->detach($request->client);

                $result = true;
            } catch (Exception $e) {
            }
        }, 3);

        if ($result) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Permissão Usuário/Cliente',
                'activity' => 'Permissão removida',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        } else {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Permissão Usuário/Cliente',
                'activity' => 'Erro ao remover a Permissão',
                'query' => '', // $sql,
                'httpCode' => 400,
                'ip' => $request->ip()
            ]);

            return ['err' => 'Falha ao dar permissão'];
        }
    }


    public function getDeviceAllSimple(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        return Device::select(['EQP_ID', 'EQP_NOME'])->where('EQP_GATEWAY', 'N')->limit(1000)->orderBy('EQP_NOME')->get();
        // return User::with('device')->get();
    }

    public function getUserDeviceAll(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $get['user'] = User::select(['USR_ID', 'USR_NOME', 'USR_MASTER'])->orderBy('USR_NOME')->get();
        $get['device'] = Device::select(['EQP_ID', 'EQP_NOME', 'EQP_CLI_ID'])
            ->addSelect(['CLI_NOME' => Client::select('CLI_NOME')
                ->whereColumn('CLI_ID', 'EQP_CLI_ID')])
            ->where('EQP_GATEWAY', 'N')->orderBy('EQP_NOME')->get();
        $get['relation'] = PermissionUserDevice::select(['PRM_USR_ID', 'PRM_EQP_ID'])->get();
        $get['relation-client'] = PermissionUserClient::select(['PRM_USR_ID', 'PRM_CLI_ID'])->get();

        return $get;

        // return User::select(['USR_ID','USR_NOME'])->with('device', function($q){
        //     return $q->select(['EQP_ID','EQP_NOME'])->orderBy('EQP_NOME');
        // })->orderBy('USR_NOME')->get();
    }

    public function setDevice(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $request->validate([
            'user' => 'required|int|max:1000',
            'device' => 'required|string|max:1000',
        ]);

        try {

            $user = User::find($request->user);
            $user->device()->attach($request->device);

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Permissão Usuário/Dispositivo',
                'activity' => 'Permissão concedida',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        } catch (Exception $e) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Permissão Usuário/Dispositivo',
                'activity' => 'Erro ao dar Permissão',
                'query' => '', // $sql,
                'httpCode' => 400,
                'ip' => $request->ip()
            ]);

            return response(['err' => 'Falha ao dar permissão'], 400);
        }
    }

    public function removeDevice(Request $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $request->validate([
            'user' => 'required|int|max:1000',
            'device' => 'required|string|max:1000',
        ]);

        $user = User::find($request->user);
        $r = $user->device()->detach($request->device);

        if ($r) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Permissão Usuário/Dispositivo',
                'activity' => 'Permissão removida',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return $r;
        } else {
            return ['err' => 'Falha ao dar permissão'];
        }
    }
}

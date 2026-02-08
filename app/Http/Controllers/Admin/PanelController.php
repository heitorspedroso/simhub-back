<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Alert;
use App\Models\Client;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Order;
use App\Services\ClientService;

class PanelController extends BaseController
{
    public function panel(Request $request)
    {
        $devAttr = ['EQP_ID', 'EQP_CLI_ID', 'EQP_NOME', 'EQP_ATIVO'];

        $panel['client'] = ClientService::getAll($request->user(), ['CLI_ID', 'CLI_NOME', 'CLI_ATIVO'], 'CLI_NOME');

        if ($request->user()->USR_MASTER === 'S') {

            $panel['device'] = Device::select($devAttr)
                ->with('client', function ($q) {
                    $q->select('CLI_ID', 'CLI_NOME');
                })
                ->where('EQP_GATEWAY', 'N')
                ->orderBy('EQP_ORDEM_EXIBICAO')
                ->get();

            $panel['alert'] = Alert::with(['device' => function ($q) {
                $q->select('EQP_ID', 'EQP_NOME');
            }])->get()->toArray();
        } else {

            $panel['device'] = $request->user()->device()->select($devAttr)
                ->with(['client' => function ($q) {
                    $q->select('CLI_ID', 'CLI_NOME');
                }])
                ->where('EQP_GATEWAY', 'N')
                ->orderBy('EQP_ORDEM_EXIBICAO')
                ->get();

            $deviceIds = $panel['device']->reduce(function ($acc, $device) {
                $acc[] = $device['EQP_ID'];
                return $acc;
            }, []);

            $panel['alert'] = Alert::whereIn('ALT_EQP_ID', $deviceIds)
                ->with('device', function ($q) {
                    $q->select('EQP_ID', 'EQP_NOME');
                })
                ->get()->toArray();

            // return Device::clearMany( $device->with(['client:CLI_ID,CLI_NOME,CLI_CONTATO'])->limit(1000)->get()->toArray() );
        }


        if (!isset($panel['alert']) || empty($panel['alert']))
            return $panel;

        $panel['alert'] = Alert::clearMany($panel['alert']);


        return $panel;
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\DeviceStoreRequest;
use App\Models\Alert;
use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\DeviceStatusHistoric;
use App\Models\HistoricTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use PDO;

class DeviceController extends Controller
{
    public function index(Request $request)
    {
        if ($request->user()->USR_MASTER === 'S') {
            $device = new Device; // 'statusLast'
        } else {
            $device = $request->user()->device();
        }

        $device = $device->with(['client:CLI_ID,CLI_NOME,CLI_CONTATO', 'alertOne'])
            ->limit(1000)
            ->orderBy('EQP_ORDEM_EXIBICAO')
            ->orderBy('EQP_NOME');

        if ($request->input('no-gate'))
            $device->where('EQP_GATEWAY', 'N');

        return Device::clearMany($device->get()->toArray());
    }

    public function getPut(Request $request, $id)
    {
        if ($request->user()->USR_MASTER === 'N') return [];

        if ($request->user()->USR_MASTER === 'S') {
            $put = Device::where('EQP_ID', $id)->first();
        } else
        if ($request->user()->USR_MASTER === 'M') {
            $put = $request->user()->device(function ($q) use ($id) {
                $q->where('EQP_ID', $id);
            })->first();
        }

        if (!$put) return [];

        $put = $put->toArray();

        $put = DeviceStoreRequest::convertStrToBool($put);

        return Device::clear($put);
        exit;
    }

    public function getDetail(Request $request, $id)
    {
        if ($request->user()->USR_MASTER === 'S')
            $device = new Device;
        else
            $device = $request->user()->device();

        $device = $device->with(['statusLast', 'client:CLI_ID,CLI_NOME', 'alertOne'])->find($id); // , 'historic'

        if (!$device) return null;

        $device = $device->toArray();

        $detail['client'] = $device['client'];
        unset($device['client']);

        if ($device['status_last']) {
            $detail += DeviceStatus::clear($device['status_last']);
            unset($device['status_last']);
        }

        if ($device['alert_one']) {
            $alerts = [];
            $alertOne = Alert::clear($device['alert_one']);
            if (!empty($alertOne['port'])) {
                $alert = Alert::clear($alertOne['port']);
                unset($device['alert_one']);
                foreach ($alert as $kA => $a) {
                    $port = (int) substr($kA, 0, 2);
                    if ($port > 0)
                        $alerts[$port] = $port;
                }
            }
        }

        $device = Device::clear($device);

        $detail = array_replace_recursive($detail, $device);  // $detail += $device;
        unset($device);

        $detail['analog'] = Device::removePortIntInactive($detail['analog']);
        $detail['temperature'] = Device::removePortIntInactive($detail['temperature']);
        $detail['digital'] = Device::removePortBoolInactive($detail['digital']);

        if (!empty($detail['digital'])) {

            foreach ($detail['digital'] as $kD => $d) {

                $name = explode(' ', trim($d['NOME']));
                $status = array_pop($name);
                $nameKey = implode('-', $name);

                if (!isset($digital[$nameKey])) {
                    $d['NOME'] = implode(' ', $name);
                    $digital[$nameKey] = $d;
                }

                if ($status == 'ERRO') {
                    if ($d['value'] == 'S')
                        $digital[$nameKey]['value'] = 'E';
                } else {
                    if ($digital[$nameKey] != 'E')
                        $digital[$nameKey]['value'] = $d['value'];
                }

                $digital[$nameKey]['ports'][] = $kD;
            }

            if (isset($alerts)) {
                foreach ($alerts as $a) {
                    foreach ($digital as $kD => $d) {
                        if (in_array($a, $d['ports']))
                            $digital[$kD]['value'] = 'A';
                    }
                }
            }

            $detail['digital'] = $digital;
        }

        // $detail['digital'] = array_reduce($digital, function($acc, $d){
        //     $acc[] = $d;
        //     return $acc;
        // },[]);

        return $detail;

        // $detail['historic'] = HistoricTime::select(['HST_EQP_ID'], ['HST_STATUS'], ['HST_STATUS_VLR'], ['SUM(HST_SEGUNDOS) AS HST_SEGUNDO'])
        //     // ->where('HST_DATA', '>', 'CONVERT(DATETIME, "2024-02-11 00:00:00", 102)')
        //     // ->where('HST_DATA', '<', 'CONVERT(DATETIME, "2024-02-13 00:00:00", 102)')
        //     ->groupBy(['HST_EQP_ID', 'HST_STATUS', 'HST_STATUS_VLR'])
        //     ->having('HST_EQP_ID', '=', '"'.$id.'"')
        //     ->having('HST_STATUS', '=', '"RL1"')
        //     ->having('HST_STATUS_VLR', '=', '"S"')
        //     ->limit(1000);
        //     // ->toSql();
        // return $detail;

    }

    public function showChart(Request $request, $id)
    {

        if (!$id)
            return ['err' => 'Id do dispositivo obrigatório'];

        if ($request->user()->USR_MASTER === 'S')
            $device = new Device;
        else
            $device = $request->user()->device();


        $date['start'] = $request->input('start') ?? null;
        $date['end']   = $request->input('end') ?? null;
        $date['now']   = null;

        if ($date['start'] && $date['end']) {
            $date['start'] = str_replace('T', ' ', $date['start']) . ':00.000';
            $date['end'] = str_replace('T', ' ', $date['end']) . ':59.999';
        } else {
            $date['now'] = date('Y-m-d') . ' 00:00:00.000';
        }

        $device = $device->with(['statusHistoric' => function ($q) use ($date) {

            if ($date['now']) {
                $q->whereDate('STT_DATA_HORA', '=', $date['now']);
            } else {
                $q->where('STT_DATA_HORA', '>=', $date['start']);
                $q->where('STT_DATA_HORA', '<=', $date['end']);
            }

            // $q->limit(1000);
            $q->orderBy('STT_DATA_HORA', 'asc');
        }])->find($id);

        if (!$device)
            return [];

        $device = $device->toArray();

        if (empty($device['status_historic']))
            return [];

        $device = Device::clear($device);

        $device['analog'] = Device::clearToHistoric($device['analog']);
        $device['digital'] = Device::clearToHistoric($device['digital']);
        $device['temperature'] = Device::clearToHistoric($device['temperature']);

        $device['analog'] = Device::removePortBoolInactive($device['analog']);
        $device['digital'] = Device::removePortBoolInactive($device['digital']);
        $device['temperature'] = Device::removePortIntInactive($device['temperature']);

        $device['status_historic'] = DeviceStatusHistoric::clearMany($device['status_historic']);
        $device['status_historic'] = DeviceStatusHistoric::dataChart($device);

        $qt = HistoricTime::selectRaw('SUM(HST_SEGUNDOS) AS HST_SEGUNDO') // HST_EQP_ID, HST_STATUS, HST_STATUS_VLR,
            ->groupBy(['HST_EQP_ID', 'HST_STATUS', 'HST_STATUS_VLR'])
            ->having('HST_EQP_ID', '=', $id)
            ->having('HST_STATUS_VLR', '=', 'S');
        // return [
        //     $qt->having('HST_STATUS', '=', '05_EDP')->first()->HST_SEGUNDO ?? 0,
        //     $qt->having('HST_STATUS', '=', '06_EDP')->first()->HST_SEGUNDO ?? 0
        // ];
        // return $qt->get();
        // $digitalIds = array_keys($device['digital']);
        // $qd = clone $qt;
        foreach ($device['digital'] as $kD => $d) {

            $qd = clone $qt;
            $qd = $qd->having('HST_STATUS', '=', (str_pad($kD, 2, '0', STR_PAD_LEFT) . '_EDP'));

            $ht['all'] = $qd->first()->HST_SEGUNDO ?? 0;

            if ($date['now']) {
                $ht['filter'] = $qd
                    ->whereDate('HST_DATA', '>=', $date['now']) // '='
                    ->first()
                    ->HST_SEGUNDO ?? 0;
            } else {
                $ht['filter'] = ($qd
                    ->where('HST_DATA', '>=', $date['start'])
                    ->where('HST_DATA', '<=', $date['end'])
                    ->first()
                )->HST_SEGUNDO ?? 0;
            }

            $historic_time[$kD] = [
                'NOME' => $d['NOME'],
                'SEGUNDO' => $ht,
            ];
        }

        $chart = [
            'ID' => $device['EQP_ID'],
            'NOME' => $device['EQP_NOME'],
            'analog' => $device['analog'],
            'digital' => $device['digital'],
            'temperature' => $device['temperature'],
            'historic_time' => $historic_time ?? [],
            'status_historic' => $device['status_historic'],
        ];

        // unset($device);


        return $chart;

        // foreach($historic as $kH => $h){

        //     foreach($h['digital'] as $kD => $d){

        //         $name = explode(' ', trim($d['NOME']));
        //         $status = array_pop($name);
        //         $nameKey = implode('-', $name);

        //         if( !isset($digital[$nameKey]) ){
        //             $d['NOME'] = implode(' ', $name);
        //             $digital[$nameKey] = $d;
        //         }

        //         if($status == 'ERRO'){
        //             if($d['value'] == 'S')
        //                 $digital[$nameKey]['value'] = 'E';
        //         }else{
        //             if($digital[$nameKey] != 'E')
        //                 $digital[$nameKey]['value'] = $d['value'];
        //         }
        //     }

        //     $historic[$kH] = $digital;
        // }

        // return $historic;
    }

    public function activesByEqp(Request $request)
    {

        if ($request->user()->USR_MASTER === 'S')
            $device = new Device;
        else
            $device = $request->user()->device();

        $device = $device->limit(1000)->get()->toArray();

        $device = Device::clearMany($device);

        return array_reduce($device, function ($acc, $d) {

            if ($d['EQP_GATEWAY'] != 'S') {
                $this->sumImgId($acc, $d['temperature'] ?? []);
                $this->sumImgId($acc, $d['analog'] ?? []);
            }
            // else{
            //     $acc[9999]++;
            // }

            return $acc;
        }, []);
    }

    private function sumImgId(&$acc, $port)
    {

        foreach ($port as $eqp) {

            foreach ($eqp as $attr => $value) {

                if (!$value) continue;

                $attrFinal = substr($attr, -6);

                if ($attrFinal == 'IMG_ID') {

                    if (isset($acc[$value]) && $acc[$value])
                        $acc[$value]++;
                    else
                        $acc[$value] = 1;
                }
            }
        }
    }

    public function store(DeviceStoreRequest $request)
    {
        if ($request->user()->USR_MASTER !== 'S') return;
        // if ($request->user()->USR_MASTER === 'N') return;

        $device = Device::create($request->all());

        if ($device) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Dispositivo',
                'activity' => 'Dispositivo criado',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        } else {
            return false;
        }


        // $validated = $request->validated();

        // $plus = $request->except( [
        //     'USR_ID', 'EQP_LITROS_TOTAL', 'EQP_maintenance_last_1', 'EQP_maintenance_last_2',
        //     'EQP_maintenance_next_1', 'EQP_maintenance_next_2'
        // ] );
    }

    public function update(DeviceStoreRequest $request)
    {
        if ($request->user()->USR_MASTER == 'N') return;

        $device = Device::findOrFail($request->EQP_ID);

        $device = $device->fill($request->all())->save();

        if ($device) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Dispositivo',
                'activity' => 'Dispositivo atualizado',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        } else {
            return false;
        }
    }

    public function destroy(Request $request, $id)
    {
        if ($request->user()->USR_MASTER !== 'S') return;

        $device = Device::destroy($id);

        if ($device) {

            ActivityController::store([
                'user' => $request->user(),
                'module' => 'Dispositivo',
                'activity' => 'Dispositivo excluido',
                'query' => '', // $sql,
                'httpCode' => 200,
                'ip' => $request->ip()
            ]);

            return true;
        } else {
            return false;
        }
    }
}

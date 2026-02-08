<?php

namespace App\Http\Controllers;

use App\Http\Requests\Api\DeviceStoreRequest;
use App\Models\Alert;
use App\Models\Device;
use App\Models\DeviceStatus;
use App\Models\DeviceStatusHistoric;
use App\Models\HistoricTime;
use App\Services\BenchmarkService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

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
            $put = $request->user()->device()->where('EQP_ID', $id)->first();
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

        $device = $device->with(['statusLast', 'client:CLI_ID,CLI_NOME', 'alertOne'])->find($id);

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

        return $detail;
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





    // public function showChart(Request $request, string $id)
    // {

    // }

    private function getDate(Request $request): array
    {
        if ($request->start && $request->end) {
            return [
                'start' => Carbon::parse($request->start)->format('Y-m-d H:i:s.000'),
                'end'   => Carbon::parse($request->end)->format('Y-m-d H:i:s.999')
            ];
        } else {
            return [
                'now' => Carbon::now()->toDateString()  // Carbon::today()->format('Y-m-d')    date('Y-m-d');
            ];
        }
    }

    // private function getDeviceWithData($request, string $id, array $dateRange)
    // {

    // }

    // private function processDeviceData(array $device): array
    // {

    // }


    public function showChart(Request $request, $id)
    {
        // BenchmarkService::start('all');

        if (!$id)
            return response()->json(['err' => 'Device ID is required'], 422);

        $device = $request->user()->USR_MASTER === 'S' ? new Device : $request->user()->device(); // Device::query()

        $date = $this->getDate($request);


        // BenchmarkService::start('device_status_historic');

        $device = $this->getDeviceStatusHistoric($id, $date, $device);

        if (!$device || empty($device['status_historic']))
            return response()->json([]);

        $device = Device::clear($device);

        // BenchmarkService::stop('device_status_historic');


        // BenchmarkService::start('dataChart');

        $device['analog']       = Device::removePortBoolInactive(Device::clearToHistoric($device['analog']));
        $device['digital']      = Device::removePortBoolInactive(Device::clearToHistoric($device['digital']));
        $device['temperature']  = Device::removePortIntInactive(Device::clearToHistoric($device['temperature']));

        $device['status_historic'] = DeviceStatusHistoric::clearMany($device['status_historic']);
        $device['status_historic'] = DeviceStatusHistoric::dataChart($device);

        // BenchmarkService::stop('dataChart');


        // BenchmarkService::start('HistoricTime');
        $device['historic_time'] = $this->getHistoricTimeData($id, $date, $device['digital']);
        // BenchmarkService::stop('HistoricTime');

        $device = [
            'ID'    => $device['EQP_ID'],
            'NOME'  => $device['EQP_NOME'],
            'analog' => $device['analog'],
            'digital' => $device['digital'],
            'temperature' => $device['temperature'],
            'historic_time' => $device['historic_time'] ?? [],
            'status_historic' => $device['status_historic'],
        ];


        // BenchmarkService::stop('all');

        // $device['results'] = BenchmarkService::getResults();

        return response()->json($device);
    }


    private function getDeviceStatusHistoric(string $id, array $date, $device)
    {
        return $device->with(['statusHistoric' => function ($q) use ($date) {

            if (isset($date['now']))
                $q->where('STT_DATA_HORA', '>=', $date['now']);
            else
                $q->whereBetween('STT_DATA_HORA', [$date['start'], $date['end']]);

            $q->orderBy('STT_DATA_HORA', 'asc');
        }])
            ->find($id)->toArray();

        // BenchmarkService::start('device-get');
        // $device = $device->find($id)->toArray();
        // BenchmarkService::stop('device-get');

        // $qSH = DeviceStatusHistoric::where('STT_EQP_ID', $id);

        // if (isset($date['now'])) {
        //     $qSH->where('STT_DATA_HORA', '>=', $date['now']);
        // } else {
        //     $qSH->whereBetween('STT_DATA_HORA', [$date['start'], $date['end']]);
        // }

        // $device['status_historic'] = $qSH->orderBy('STT_DATA_HORA', 'asc')->get()->toArray();

        // return $device;

        //     return DB::table('TAB_STATUS_HISTORICO')
        //     ->where('STT_EQP_ID', $id)
        //     ->whereBetween('STT_DATA_HORA', [$date['start'], $date['end']])
        //     ->orderBy('STT_DATA_HORA', 'asc')
        //     ->toSql();
        // // ->get();
    }

    private function getHistoricTimeData(string $id, array $date, array $digitalData): array
    {
        $historicQuery = HistoricTime::query()
            // historicQuery = HistoricTime::selectRaw('SUM(HST_SEGUNDOS) AS HST_SEGUNDO, HST_STATUS')
            ->selectRaw('SUM(HST_SEGUNDOS) AS HST_SEGUNDO, HST_STATUS')
            ->where('HST_EQP_ID', $id)
            ->where('HST_STATUS_VLR', 'S')
            ->where('HST_STATUS', 'like', '%_EDP')
            ->groupBy(['HST_EQP_ID', 'HST_STATUS', 'HST_STATUS_VLR']);

        $historicAll = (clone $historicQuery)->get()
            ->keyBy(function ($item) {
                return (int) $item['HST_STATUS'];
            })
            ->map(fn($item) => $item['HST_SEGUNDO']);

        if (isset($date['now'])) {
            $historicQuery->where('HST_DATA', '>=', $date['now']);
        } else {
            $historicQuery->whereBetween('HST_DATA', [$date['start'], $date['end']]);
        }

        $historicDataFiltered = $historicQuery->get()
            ->keyBy(function ($item) {
                return (int) $item['HST_STATUS'];
            })
            ->map(fn($item) => $item['HST_SEGUNDO']);

        foreach ($digitalData as $key => $digital) {
            // $key =  $key;
            // dd($key);
            $historicTime[$key] = [
                'SEGUNDO' => [
                    'NOME' => $digital['NOME'],
                    'all' => $historicAll->get($key) ?? 0,
                    'filter' => $historicDataFiltered->get($key) ?? 0
                ]
            ];
        }

        return $historicTime ?? [];
    }
}





















// foreach($historic as $kH => $h){

    //     //     foreach($h['digital'] as $kD => $d){

    //     //         $name = explode(' ', trim($d['NOME']));
    //     //         $status = array_pop($name);
    //     //         $nameKey = implode('-', $name);

    //     //         if( !isset($digital[$nameKey]) ){
    //     //             $d['NOME'] = implode(' ', $name);
    //     //             $digital[$nameKey] = $d;
    //     //         }

    //     //         if($status == 'ERRO'){
    //     //             if($d['value'] == 'S')
    //     //                 $digital[$nameKey]['value'] = 'E';
    //     //         }else{
    //     //             if($digital[$nameKey] != 'E')
    //     //                 $digital[$nameKey]['value'] = $d['value'];
    //     //         }
    //     //     }

    //     //     $historic[$kH] = $digital;
    //     // }

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceStatusHistoric extends Model
{
    use HasFactory;

    protected $table = 'TAB_STATUS_HISTORICO';
    // protected $fillable = [ 'name', 'status' ];
    // protected $primaryKey = 'STT_ID';

    protected $hidden = [
        'STT_ID',
        'STT_EQP_ID',
        'STT_OP_NIVEL',
        'STT_NIVEL_INF',
        'STT_NIVEL_SUP',
        'STT_01_REG',
        'STT_02_REG',
        'STT_03_REG',
        'STT_04_REG',
        'STT_05_HIDR',
        'STT_06_HIDR',
        'STT_07_HIDR',
        'STT_08_HIDR',
        'STT_09_HIDR',
        'STT_10_HIDR',
        'STT_11_HIDR',
        'STT_12_HIDR',
        'STT_13_HIDR',
        'STT_14_HIDR',
        'STT_15_HIDR',
        'STT_16_HIDR',
        'STT_17_HIDR',
        'STT_18_HIDR',
        'STT_19_HIDR',
        'STT_20_HIDR',
        'STT_21_HIDR',
        'STT_22_HIDR',
        'STT_23_HIDR',
        'STT_25_SAP',
        'STT_26_SAP',
        'STT_LORA_DB',
        'STT_RL1',
        'STT_RL2',
        'STT_RL3',
        'STT_RL4',
        'STT_RL5',
        'STT_RL6',
    ];


    public static function clear(&$deviceHistoric)
    {
        foreach ($deviceHistoric as $kD => $d) {

            if (substr($kD, 0, 3) != 'STT') {
                $ds[$kD] = $d;
                continue;
            }

            $kD = substr($kD, 4);
            $sub = (int) substr($kD, 0, 2);

            // if($sub > 23) continue;

            if ($sub) {
                // dd($kD, $sub, $d);
                $type = $sub < 5 ? 'analog' : 'digital';

                $attr = substr($kD, 3);

                if (in_array($attr, ['EAE', 'EAP', 'EDP']))
                    $ds[$type][$sub]['value'] = $d;
            } else
            if (substr($kD, 0, 4) == 'TEMP') {
                // if($d == 0) continue;
                $sub = substr($kD, 4);
                // $ds['port'][24][$sub]['status']['value'] = $d;
                $ds['temperature'][$sub]['value'] = $d;
            } else {

                // if($kD == 'DATA_HORA')
                //     $d = date('Y/m/d H:i:s', strtotime($d));

                $ds[$kD] = $d;
            }

            // else
            // if( $sub == 'RL' ){
            //     $sub = substr($kD, 6,1);
            //     $sub2 = substr($kD, 8);
            //     $r[$kD]['rl'][$sub][$sub2] = $a;
            // }
        }

        return $ds;
    }
    public static function clearMany(&$deviceHistoric)
    {
        foreach ($deviceHistoric as $kD => $d) {
            $ds[$kD] = self::clear($d);
        }

        return $ds;
    }

    public static function dataChart($device)
    {
        $acc = [
            'time' => [],
            'data' => [
                'analog' => [],
                'temperature' => [],
                'digital' => [],
            ],
        ];

        $validAnalogKeys = array_keys($device['analog']);
        $validTemperatureKeys = array_keys($device['temperature']);
        $validDigitalKeys = array_keys($device['digital']);

        foreach ($device['status_historic'] as $h) {
            $acc['time'][] = $h['DATA_HORA'];

            foreach ($validAnalogKeys as $kD) {
                if (isset($h['analog'][$kD])) {
                    $acc['data']['analog'][$kD][] = $h['analog'][$kD]['value'];
                }
            }

            foreach ($validTemperatureKeys as $kD) {
                if (isset($h['temperature'][$kD])) {
                    $acc['data']['temperature'][$kD][] = $h['temperature'][$kD]['value'];
                }
            }

            foreach ($validDigitalKeys as $kD) {
                if (isset($h['digital'][$kD])) {
                    $acc['data']['digital'][$kD][] = $h['digital'][$kD]['value'] == 'N' ? 0 : 1;
                }
            }
        }

        return $acc;
    }
}

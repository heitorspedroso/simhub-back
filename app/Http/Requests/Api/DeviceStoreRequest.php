<?php

namespace App\Http\Requests\Api;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;

class DeviceStoreRequest extends FormRequest
{
    protected static $attrBool = [
        'EQP_ATIVO',
        'EQP_GATEWAY',
        'EQP_TELA',
        'EQP_SUP_NIVEL_REMOTO',
        'EQP_OP_REMOTO',
        'EQP_LIGAR_RL1',
        'EQP_SO_NIVEL',
        'EQP_LIGAR_RL2',
        'EQP_LIGAR_RL3',
        'EQP_LIGAR_RL4',
        'EQP_LIGAR_RL5',
        'EQP_LIGAR_RL6',
        'EQP_OP_SENSOR_NIVEL',
        'EQP_OP_EXTRAVAZAO',
        'EQP_SO_MONITORAMENTO',
        'EQP_01_EAE_ENVIA_ALERTA',
        'EQP_02_EAE_ENVIA_ALERTA',
        'EQP_03_EAP_ENVIA_ALERTA',
        'EQP_04_EAP_ENVIA_ALERTA',
        'EQP_05_EDP_ENVIA_ALERTA',
        'EQP_06_EDP_ENVIA_ALERTA',
        'EQP_07_EDP_ENVIA_ALERTA',
        'EQP_08_EDP_ENVIA_ALERTA',
        'EQP_09_EDP_ENVIA_ALERTA',
        'EQP_10_EDP_ENVIA_ALERTA',
        'EQP_11_EDP_ENVIA_ALERTA',
        'EQP_12_EDP_ENVIA_ALERTA',
        'EQP_13_EDP_ENVIA_ALERTA',
        'EQP_14_EDP_ENVIA_ALERTA',
        'EQP_15_EDP_ENVIA_ALERTA',
        'EQP_16_EDP_ENVIA_ALERTA',
        'EQP_17_EDP_ENVIA_ALERTA',
        'EQP_18_EDP_ENVIA_ALERTA',
        'EQP_19_EDP_ENVIA_ALERTA',
        'EQP_20_EDP_ENVIA_ALERTA',
        'EQP_21_EDP_ENVIA_ALERTA',
        'EQP_22_EDP_ENVIA_ALERTA',
        'EQP_23_EDP_ENVIA_ALERTA',
        'EQP_05_HIDR_ZERA_HIDR',
        'EQP_06_HIDR_ZERA_HIDR',
        'EQP_07_HIDR_ZERA_HIDR',
        'EQP_08_HIDR_ZERA_HIDR',
        'EQP_09_HIDR_ZERA_HIDR',
        'EQP_10_HIDR_ZERA_HIDR',
        'EQP_11_HIDR_ZERA_HIDR',
        'EQP_12_HIDR_ZERA_HIDR',
        'EQP_13_HIDR_ZERA_HIDR',
        'EQP_14_HIDR_ZERA_HIDR',
        'EQP_15_HIDR_ZERA_HIDR',
        'EQP_16_HIDR_ZERA_HIDR',
        'EQP_17_HIDR_ZERA_HIDR',
        'EQP_18_HIDR_ZERA_HIDR',
        'EQP_19_HIDR_ZERA_HIDR',
        'EQP_20_HIDR_ZERA_HIDR',
        'EQP_21_HIDR_ZERA_HIDR',
        'EQP_22_HIDR_ZERA_HIDR',
        'EQP_23_HIDR_ZERA_HIDR',
        'EQP_24_TEMP1_ENVIA_ALERTA',
        'EQP_24_TEMP2_ENVIA_ALERTA',
        'EQP_24_TEMP3_ENVIA_ALERTA',
        'EQP_24_TEMP4_ENVIA_ALERTA',
        'EQP_24_TEMP5_ENVIA_ALERTA',
        'EQP_24_TEMP6_ENVIA_ALERTA',
        'EQP_24_TEMP7_ENVIA_ALERTA',
        'EQP_24_TEMP8_ENVIA_ALERTA'
    ];

    public function authorize()
    {
        return $this->user()->USR_MASTER != 'N';
    }

    function convertDateMulti(&$data)
    {

        foreach ($data as $k => $d) {
            if ($d && substr($k, -12) == 'DT_ULT_MANUT') {
                $data[$k] = Carbon::parse($d)->format('Y-m-d H:i:s.v'); // Y-m-d    yyyy-MM-ddThh:mm:ss.sss
            }
        }
    }

    function convertBoolToStr()
    {

        foreach ($this->toArray() as $k => $v) {

            if (in_array($k, self::$attrBool)) {

                if ($v == 1 || $v == true)
                    $this->merge([$k => 'S']);
                else
                    $this->merge([$k => 'N']);
            }
        }
    }

    static function convertStrToBool($data)
    {

        foreach ($data as $k => $v) {

            if (in_array($k, self::$attrBool)) {
                if ($v == 'S')
                    $data[$k] = true;
                else
                    $data[$k] = false;
            }
        }

        return $data;
    }

    function addPorts($data, $prefix = '', $except = [])
    {

        if (!$data) return [];

        foreach ($data as $number => $p) {

            if ($prefix)
                $number = str_pad($number, 2, '0', STR_PAD_LEFT);

            foreach ($p as $kAttr => $value) {

                if ($prefix) {
                    if ($except && in_array($kAttr, $except)) {
                        $ports['EQP_' . $number . '_' . $kAttr] = $value;
                    } else {
                        $ports['EQP_' . $number . $prefix . $kAttr] = $value;
                    }
                } else {
                    $ports['EQP_' . '24_TEMP' . $number . '_' . $kAttr] = $value;
                }
            }
        }

        return $ports ?? [];
    }

    public function rules()
    {
        // $this->convertBoolToStr($this->request);
        // if( $this->EQP_GATEWAY == 'S' ){
        if ($this->EQP_GATEWAY) {

            $this->convertBoolToStr();

            return [
                'EQP_ID' => ['required'],
                'EQP_NOME' => ['required'],
                'EQP_CLI_ID' => ['required'],
                'EQP_GATEWAY' => ['required'],
                'EQP_SENDER_IDS' => ['required'],
                'EQP_MILLIS_CONSULTA' => ['nullable'],
                'EQP_ATIVO' => ['nullable'],
            ];
        }

        $ports  = $this->addPorts($this->only(['analog.1', 'analog.2'])['analog'] ?? [], '_EAE_');
        $ports += $this->addPorts($this->only(['analog.3', 'analog.4'])['analog'] ?? [], '_EAP_');
        $ports += $this->addPorts($this->digital, '_EDP_', ['HIDR_UN', 'HIDR_VLR_PROP', 'HIDR_VLR_PROP', 'HIDR_ZERA_HIDR']);
        $ports += $this->addPorts($this->temperature);

        $this->convertDateMulti($ports);

        $this->replace($this->except(['analog', 'digital', 'temperature']));
        $this->merge($ports);

        $this->convertBoolToStr();

        return [
            // 'USR_ID' => [],
            'EQP_ID' => ['required'],
            'EQP_NOME' => ['required'],
            'EQP_CLI_ID' => ['required'],
            'EQP_ORDEM_EXIBICAO' => ['nullable'],
            // 'EQP_SENDER_IDS' => ['nullable'],
            'EQP_GATEWAY' => ['nullable'],
            'EQP_TELA' => ['nullable'],
            'EQP_INF_NIVEL_MIN' => ['nullable'],
            'EQP_INF_NIVEL_MAX' => ['nullable'],
            'EQP_SUP_NIVEL_MIN' => ['nullable'],
            'EQP_SUP_NIVEL_MAX' => ['nullable'],
            'EQP_SUP_NIVEL_REMOTO' => ['nullable'],
            'EQP_SUP_NIVEL_REMOTO_EQP_IDS' => ['nullable'],
            'EQP_MILLIS_MEDIA' => ['nullable'],
            'EQP_SO_NIVEL' => ['nullable'],
            'EQP_MILLIS_CONSULTA' => ['nullable'],
            'EQP_SENHA' => ['nullable'],
            'EQP_OP_REMOTO' => ['nullable'],
            'EQP_LIGAR_RL1' => ['nullable'],
            'EQP_LIGAR_RL2' => ['nullable'],
            'EQP_LIGAR_RL3' => ['nullable'],
            'EQP_LIGAR_RL4' => ['nullable'],
            'EQP_LIGAR_RL5' => ['nullable'],
            'EQP_LIGAR_RL6' => ['nullable'],
            'EQP_REF_AN0' => ['nullable'],
            'EQP_REF_AN1' => ['nullable'],
            'EQP_OP_SENSOR_NIVEL' => ['nullable'],
            'EQP_INF_NIVEL_LIGA' => ['nullable'],
            'EQP_INF_NIVEL_DESLIGA' => ['nullable'],
            'EQP_SUP_NIVEL_LIGA' => ['nullable'],
            'EQP_SUP_NIVEL_DESLIGA' => ['nullable'],
            'EQP_OP_EXTRAVAZAO' => ['nullable'],
            'EQP_SUP_EXTRAVAZAO_LIGA' => ['nullable'],
            'EQP_SUP_EXTRAVAZAO_DESLIGA' => ['nullable'],
            'EQP_SO_MONITORAMENTO' => ['nullable'],
            'EQP_ATIVO' => ['nullable'],
        ];
    }
}

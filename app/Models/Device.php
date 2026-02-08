<?php

namespace App\Models;

use App\Scopes\ExampleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    use HasFactory;

    protected $table = 'DISPOSITIVOS';
    // protected $table = 'DISPOSITIVOS2';

    protected $primaryKey = 'EQP_ID';
    public $incrementing = false;
    protected $keyType = 'string';

    public $timestamps = false;
    // protected $dateFormat = 'U';

    // protected $attributes = [
    //     'EQP_NOME' => false,
    // ];

    protected $fillable = [
        'USR_ID',
        'EQP_ID',
        'EQP_CLI_ID',
        'EQP_ORDEM_EXIBICAO',
        'EQP_NOME',
        // 'EQP_ATIVO',
        'EQP_SENDER_IDS',
        'EQP_GATEWAY',
        'EQP_TELA',
        'EQP_CLASSIFIC_1',
        'EQP_CLASSIFIC_2',
        'EQP_CLASSIFIC_3',
        // 'EQP_INF_NIVEL_MIN',
        // 'EQP_INF_NIVEL_MAX',
        // 'EQP_SUP_NIVEL_MIN',
        // 'EQP_SUP_NIVEL_MAX',
        'EQP_SO_NIVEL',
        'EQP_SUP_NIVEL_REMOTO',
        'EQP_SUP_NIVEL_REMOTO_EQP_IDS',
        'EQP_SUP_NIVEL_REMOTO_LIGA',
        'EQP_SUP_NIVEL_REMOTO_DESLIGA',
        'EQP_MILLIS_MEDIA',
        'EQP_MILLIS_CONSULTA',
        'EQP_SENHA',
        'EQP_OP_REMOTO',
        'EQP_LIGAR_RL1',
        'EQP_LIGAR_RL2',
        'EQP_LIGAR_RL3',
        'EQP_LIGAR_RL4',
        'EQP_LIGAR_RL5',
        'EQP_LIGAR_RL6',
        // 'EQP_REF_AN0',
        // 'EQP_REF_AN1',
        'EQP_OP_SENSOR_NIVEL',
        // 'EQP_INF_NIVEL_LIGA',
        // 'EQP_INF_NIVEL_DESLIGA',
        // 'EQP_SUP_NIVEL_LIGA',
        // 'EQP_SUP_NIVEL_DESLIGA',
        // 'EQP_OP_EXTRAVAZAO',
        // 'EQP_SUP_EXTRAVAZAO_LIGA',
        // 'EQP_SUP_EXTRAVAZAO_DESLIGA',
        'EQP_SO_MONITORAMENTO',
        'EQP_RL1_IMG_ID',
        'EQP_RL1_NOME',
        'EQP_RL1_REG_HAB',
        'EQP_RL2_IMG_ID',
        'EQP_RL2_NOME',
        'EQP_RL2_REG_HAB',
        'EQP_RL3_IMG_ID',
        'EQP_RL3_NOME',
        'EQP_RL3_REG_HAB',
        'EQP_RL4_IMG_ID',
        'EQP_RL4_NOME',
        'EQP_RL4_REG_HAB',
        'EQP_RL5_IMG_ID',
        'EQP_RL5_NOME',
        'EQP_RL5_REG_HAB',
        'EQP_RL6_IMG_ID',
        'EQP_RL6_NOME',
        'EQP_RL6_REG_HAB',
        'EQP_01_EAE_IMG_ID',
        'EQP_01_EAE_NIVEL_LIGA',
        'EQP_01_EAE_NIVEL_DESLIGA',
        'EQP_01_EAE_NIVEL_LIGA',
        'EQP_01_EAE_NIVEL_DESLIGA',
        'EQP_01_EAE_NOME',
        'EQP_01_EAE_UN',
        'EQP_01_EAE_VLR_PROP',
        'EQP_01_EAE_ERR_1_FIM',
        'EQP_01_EAE_ERR_1_INI',
        'EQP_01_EAE_ERR_2_FIM',
        'EQP_01_EAE_ERR_2_INI',
        'EQP_01_EAE_REG_MIN',
        'EQP_01_EAE_REG_MAX',
        'EQP_02_EAE_IMG_ID',
        'EQP_02_EAE_NIVEL_LIGA',
        'EQP_02_EAE_NIVEL_DESLIGA',
        'EQP_02_EAE_NOME',
        'EQP_02_EAE_UN',
        'EQP_02_EAE_VLR_PROP',
        'EQP_02_EAE_ERR_1_FIM',
        'EQP_02_EAE_ERR_1_INI',
        'EQP_02_EAE_ERR_2_FIM',
        'EQP_02_EAE_ERR_2_INI',
        'EQP_02_EAE_REG_MIN',
        'EQP_02_EAE_REG_MAX',
        'EQP_03_EAP_IMG_ID',
        'EQP_03_EAP_NIVEL_LIGA',
        'EQP_03_EAP_NIVEL_DESLIGA',
        'EQP_03_EAP_NOME',
        'EQP_03_EAP_UN',
        'EQP_03_EAP_VLR_PROP',
        'EQP_03_EAP_ERR_1_FIM',
        'EQP_03_EAP_ERR_1_INI',
        'EQP_03_EAP_ERR_2_FIM',
        'EQP_03_EAP_ERR_2_INI',
        'EQP_03_EAP_REG_MIN',
        'EQP_03_EAP_REG_MAX',
        'EQP_04_EAP_IMG_ID',
        'EQP_04_EAP_NIVEL_LIGA',
        'EQP_04_EAP_NIVEL_DESLIGA',
        'EQP_04_EAP_NOME',
        'EQP_04_EAP_UN',
        'EQP_04_EAP_VLR_PROP',
        'EQP_04_EAP_ERR_1_FIM',
        'EQP_04_EAP_ERR_1_INI',
        'EQP_04_EAP_ERR_2_FIM',
        'EQP_04_EAP_ERR_2_INI',
        'EQP_04_EAP_REG_MIN',
        'EQP_04_EAP_REG_MAX',
        'EQP_05_EDP_DT_ULT_MANUT',
        'EQP_05_EDP_HORAS_PROX_MANUT',
        'EQP_05_EDP_IMG_ID',
        'EQP_05_EDP_NOME',
        'EQP_05_EDP_CTT',
        'EQP_06_EDP_DT_ULT_MANUT',
        'EQP_06_EDP_HORAS_PROX_MANUT',
        'EQP_06_EDP_IMG_ID',
        'EQP_06_EDP_NOME',
        'EQP_06_EDP_CTT',
        'EQP_07_EDP_DT_ULT_MANUT',
        'EQP_07_EDP_HORAS_PROX_MANUT',
        'EQP_07_EDP_IMG_ID',
        'EQP_07_EDP_NOME',
        'EQP_07_EDP_CTT',
        'EQP_08_EDP_DT_ULT_MANUT',
        'EQP_08_EDP_HORAS_PROX_MANUT',
        'EQP_08_EDP_IMG_ID',
        'EQP_08_EDP_NOME',
        'EQP_08_EDP_CTT',
        'EQP_09_EDP_DT_ULT_MANUT',
        'EQP_09_EDP_HORAS_PROX_MANUT',
        'EQP_09_EDP_IMG_ID',
        'EQP_09_EDP_NOME',
        'EQP_09_EDP_CTT',
        'EQP_10_EDP_DT_ULT_MANUT',
        'EQP_10_EDP_HORAS_PROX_MANUT',
        'EQP_10_EDP_IMG_ID',
        'EQP_10_EDP_NOME',
        'EQP_10_EDP_CTT',
        'EQP_11_EDP_DT_ULT_MANUT',
        'EQP_11_EDP_HORAS_PROX_MANUT',
        'EQP_11_EDP_IMG_ID',
        'EQP_11_EDP_NOME',
        'EQP_11_EDP_CTT',
        'EQP_12_EDP_DT_ULT_MANUT',
        'EQP_12_EDP_HORAS_PROX_MANUT',
        'EQP_12_EDP_IMG_ID',
        'EQP_12_EDP_CTT',
        'EQP_12_EDP_NOME',
        'EQP_13_EDP_DT_ULT_MANUT',
        'EQP_13_EDP_HORAS_PROX_MANUT',
        'EQP_13_EDP_IMG_ID',
        'EQP_13_EDP_CTT',
        'EQP_13_EDP_NOME',
        'EQP_14_EDP_DT_ULT_MANUT',
        'EQP_14_EDP_HORAS_PROX_MANUT',
        'EQP_14_EDP_IMG_ID',
        'EQP_14_EDP_NOME',
        'EQP_14_EDP_CTT',
        'EQP_15_EDP_DT_ULT_MANUT',
        'EQP_15_EDP_HORAS_PROX_MANUT',
        'EQP_15_EDP_IMG_ID',
        'EQP_15_EDP_NOME',
        'EQP_15_EDP_CTT',
        'EQP_16_EDP_DT_ULT_MANUT',
        'EQP_16_EDP_HORAS_PROX_MANUT',
        'EQP_16_EDP_IMG_ID',
        'EQP_16_EDP_NOME',
        'EQP_16_EDP_CTT',
        'EQP_17_EDP_DT_ULT_MANUT',
        'EQP_17_EDP_HORAS_PROX_MANUT',
        'EQP_17_EDP_IMG_ID',
        'EQP_17_EDP_NOME',
        'EQP_17_EDP_CTT',
        'EQP_18_EDP_DT_ULT_MANUT',
        'EQP_18_EDP_HORAS_PROX_MANUT',
        'EQP_18_EDP_IMG_ID',
        'EQP_18_EDP_NOME',
        'EQP_18_EDP_CTT',
        'EQP_19_EDP_DT_ULT_MANUT',
        'EQP_19_EDP_HORAS_PROX_MANUT',
        'EQP_19_EDP_IMG_ID',
        'EQP_19_EDP_NOME',
        'EQP_19_EDP_CTT',
        'EQP_20_EDP_DT_ULT_MANUT',
        'EQP_20_EDP_HORAS_PROX_MANUT',
        'EQP_20_EDP_IMG_ID',
        'EQP_20_EDP_NOME',
        'EQP_20_EDP_CTT',
        'EQP_21_EDP_DT_ULT_MANUT',
        'EQP_21_EDP_HORAS_PROX_MANUT',
        'EQP_21_EDP_IMG_ID',
        'EQP_21_EDP_NOME',
        'EQP_21_EDP_CTT',
        'EQP_22_EDP_DT_ULT_MANUT',
        'EQP_22_EDP_HORAS_PROX_MANUT',
        'EQP_22_EDP_IMG_ID',
        'EQP_22_EDP_NOME',
        'EQP_22_EDP_CTT',
        'EQP_23_EDP_DT_ULT_MANUT',
        'EQP_23_EDP_HORAS_PROX_MANUT',
        'EQP_23_EDP_IMG_ID',
        'EQP_23_EDP_NOME',
        'EQP_23_EDP_CTT',
        'EQP_24_TEMP1_IMG_ID',
        'EQP_24_TEMP1_NOME',
        'EQP_24_TEMP1_UN',
        // 'EQP_24_TEMP1_VLR_PROP',
        'EQP_24_TEMP1_ERR_1_FIM',
        'EQP_24_TEMP1_ERR_1_INI',
        'EQP_24_TEMP1_ERR_2_FIM',
        'EQP_24_TEMP1_ERR_2_INI',
        'EQP_24_TEMP2_IMG_ID',
        'EQP_24_TEMP2_NOME',
        'EQP_24_TEMP2_UN',
        // 'EQP_24_TEMP2_VLR_PROP',
        'EQP_24_TEMP2_ERR_1_FIM',
        'EQP_24_TEMP2_ERR_1_INI',
        'EQP_24_TEMP2_ERR_2_FIM',
        'EQP_24_TEMP2_ERR_2_INI',
        'EQP_24_TEMP3_IMG_ID',
        'EQP_24_TEMP3_NOME',
        'EQP_24_TEMP3_UN',
        // 'EQP_24_TEMP3_VLR_PROP',
        'EQP_24_TEMP3_ERR_1_FIM',
        'EQP_24_TEMP3_ERR_1_INI',
        'EQP_24_TEMP3_ERR_2_FIM',
        'EQP_24_TEMP3_ERR_2_INI',
        'EQP_24_TEMP4_IMG_ID',
        'EQP_24_TEMP4_NOME',
        'EQP_24_TEMP4_UN',
        // 'EQP_24_TEMP4_VLR_PROP',
        'EQP_24_TEMP4_ERR_1_FIM',
        'EQP_24_TEMP4_ERR_1_INI',
        'EQP_24_TEMP4_ERR_2_FIM',
        'EQP_24_TEMP4_ERR_2_INI',
        'EQP_24_TEMP5_IMG_ID',
        'EQP_24_TEMP5_NOME',
        'EQP_24_TEMP5_UN',
        // 'EQP_24_TEMP5_VLR_PROP',
        'EQP_24_TEMP5_ERR_1_FIM',
        'EQP_24_TEMP5_ERR_1_INI',
        'EQP_24_TEMP5_ERR_2_FIM',
        'EQP_24_TEMP5_ERR_2_INI',
        'EQP_24_TEMP6_IMG_ID',
        'EQP_24_TEMP6_NOME',
        'EQP_24_TEMP6_UN',
        // 'EQP_24_TEMP6_VLR_PROP',
        'EQP_24_TEMP6_ERR_1_FIM',
        'EQP_24_TEMP6_ERR_1_INI',
        'EQP_24_TEMP6_ERR_2_FIM',
        'EQP_24_TEMP6_ERR_2_INI',
        'EQP_24_TEMP7_IMG_ID',
        'EQP_24_TEMP7_NOME',
        'EQP_24_TEMP7_UN',
        // 'EQP_24_TEMP7_VLR_PROP',
        'EQP_24_TEMP7_ERR_1_FIM',
        'EQP_24_TEMP7_ERR_1_INI',
        'EQP_24_TEMP7_ERR_2_FIM',
        'EQP_24_TEMP7_ERR_2_INI',
        'EQP_24_TEMP8_IMG_ID',
        'EQP_24_TEMP8_NOME',
        'EQP_24_TEMP8_UN',
        // 'EQP_24_TEMP8_VLR_PROP',
        'EQP_24_TEMP8_ERR_1_FIM',
        'EQP_24_TEMP8_ERR_1_INI',
        'EQP_24_TEMP8_ERR_2_FIM',
        'EQP_24_TEMP8_ERR_2_INI',
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
        'EQP_24_TEMP1_ENVIA_ALERTA',
        'EQP_24_TEMP2_ENVIA_ALERTA',
        'EQP_24_TEMP3_ENVIA_ALERTA',
        'EQP_24_TEMP4_ENVIA_ALERTA',
        'EQP_24_TEMP5_ENVIA_ALERTA',
        'EQP_24_TEMP6_ENVIA_ALERTA',
        'EQP_24_TEMP7_ENVIA_ALERTA',
        'EQP_24_TEMP8_ENVIA_ALERTA',
        'EQP_05_HIDR_UN',
        'EQP_06_HIDR_UN',
        'EQP_07_HIDR_UN',
        'EQP_08_HIDR_UN',
        'EQP_09_HIDR_UN',
        'EQP_10_HIDR_UN',
        'EQP_11_HIDR_UN',
        'EQP_12_HIDR_UN',
        'EQP_13_HIDR_UN',
        'EQP_14_HIDR_UN',
        'EQP_15_HIDR_UN',
        'EQP_16_HIDR_UN',
        'EQP_17_HIDR_UN',
        'EQP_18_HIDR_UN',
        'EQP_19_HIDR_UN',
        'EQP_20_HIDR_UN',
        'EQP_21_HIDR_UN',
        'EQP_22_HIDR_UN',
        'EQP_23_HIDR_UN',
        'EQP_05_HIDR_VLR_PROP',
        'EQP_06_HIDR_VLR_PROP',
        'EQP_07_HIDR_VLR_PROP',
        'EQP_08_HIDR_VLR_PROP',
        'EQP_09_HIDR_VLR_PROP',
        'EQP_10_HIDR_VLR_PROP',
        'EQP_11_HIDR_VLR_PROP',
        'EQP_12_HIDR_VLR_PROP',
        'EQP_13_HIDR_VLR_PROP',
        'EQP_14_HIDR_VLR_PROP',
        'EQP_15_HIDR_VLR_PROP',
        'EQP_16_HIDR_VLR_PROP',
        'EQP_17_HIDR_VLR_PROP',
        'EQP_18_HIDR_VLR_PROP',
        'EQP_19_HIDR_VLR_PROP',
        'EQP_20_HIDR_VLR_PROP',
        'EQP_21_HIDR_VLR_PROP',
        'EQP_22_HIDR_VLR_PROP',
        'EQP_23_HIDR_VLR_PROP',
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
    ];

    // protected $casts = [
    //     'EQP_05_EDP_DT_ULT_MANUT' => 'datetime:Y-m-d\TH:i'
    // ];

    protected $hidden = [
        // 'EQP_GATEWAY',
        // 'EQP_TELA',
        // 'EQP_CLASSIFIC_1',
        // 'EQP_CLASSIFIC_2',
        // 'EQP_CLASSIFIC_3',
        // 'EQP_SENDER_IDS',
        // 'EQP_SENHA',
        // 'EQP_OP_REMOTO',
        // 'EQP_LIGAR_RL1','EQP_LIGAR_RL2','EQP_LIGAR_RL3','EQP_LIGAR_RL4','EQP_LIGAR_RL5','EQP_LIGAR_RL6',
        // 'EQP_SO_MONITORAMENTO',
        // 'EQP_ATIVO',
        'EQP_INF_NIVEL_MIN',
        'EQP_INF_NIVEL_MAX',
        'EQP_SUP_NIVEL_MIN',
        'EQP_SUP_NIVEL_MAX',
        // 'EQP_SUP_NIVEL_REMOTO_EQP_IDS',
        // 'EQP_SUP_NIVEL_REMOTO_LIGA',
        // 'EQP_SUP_NIVEL_REMOTO_DESLIGA',
        'EQP_REF_AN0',
        'EQP_REF_AN1',
        'EQP_INF_NIVEL_LIGA',
        'EQP_INF_NIVEL_DESLIGA',
        'EQP_SUP_NIVEL_LIGA',
        'EQP_SUP_NIVEL_DESLIGA',
        'EQP_OP_EXTRAVAZAO',
        'EQP_SUP_EXTRAVAZAO_LIGA',
        'EQP_SUP_EXTRAVAZAO_DESLIGA',
        'EQP_RL1_DT_ULT_STATUS',
        // 'EQP_RL1_IMG_ID',
        // 'EQP_RL1_NOME',
        // 'EQP_RL1_REG_HAB',
        'EQP_RL2_DT_ULT_STATUS',
        // 'EQP_RL2_IMG_ID',
        // 'EQP_RL2_NOME',
        // 'EQP_RL2_REG_HAB',
        'EQP_RL3_DT_ULT_STATUS',
        // 'EQP_RL3_IMG_ID',
        // 'EQP_RL3_NOME',
        // 'EQP_RL3_REG_HAB',
        'EQP_RL4_DT_ULT_STATUS',
        // 'EQP_RL4_IMG_ID',
        // 'EQP_RL4_NOME',
        // 'EQP_RL4_REG_HAB',
        'EQP_RL5_DT_ULT_STATUS',
        // 'EQP_RL5_IMG_ID',
        // 'EQP_RL5_NOME',
        // 'EQP_RL5_REG_HAB',
        'EQP_RL6_DT_ULT_STATUS',
        // 'EQP_RL6_IMG_ID',
        // 'EQP_RL6_NOME',
        // 'EQP_RL6_REG_HAB',
        // 'EQP_05_HIDR_UN',
        // 'EQP_06_HIDR_UN',
        // 'EQP_07_HIDR_UN',
        // 'EQP_08_HIDR_UN',
        // 'EQP_09_HIDR_UN',
        // 'EQP_10_HIDR_UN',
        // 'EQP_11_HIDR_UN',
        // 'EQP_12_HIDR_UN',
        // 'EQP_13_HIDR_UN',
        // 'EQP_14_HIDR_UN',
        // 'EQP_15_HIDR_UN',
        // 'EQP_16_HIDR_UN',
        // 'EQP_17_HIDR_UN',
        // 'EQP_18_HIDR_UN',
        // 'EQP_19_HIDR_UN',
        // 'EQP_20_HIDR_UN',
        // 'EQP_21_HIDR_UN',
        // 'EQP_22_HIDR_UN',
        // 'EQP_23_HIDR_UN',
        // 'EQP_05_HIDR_VLR_PROP',
        // 'EQP_06_HIDR_VLR_PROP',
        // 'EQP_07_HIDR_VLR_PROP',
        // 'EQP_08_HIDR_VLR_PROP',
        // 'EQP_09_HIDR_VLR_PROP',
        // 'EQP_10_HIDR_VLR_PROP',
        // 'EQP_11_HIDR_VLR_PROP',
        // 'EQP_12_HIDR_VLR_PROP',
        // 'EQP_13_HIDR_VLR_PROP',
        // 'EQP_14_HIDR_VLR_PROP',
        // 'EQP_15_HIDR_VLR_PROP',
        // 'EQP_16_HIDR_VLR_PROP',
        // 'EQP_17_HIDR_VLR_PROP',
        // 'EQP_18_HIDR_VLR_PROP',
        // 'EQP_19_HIDR_VLR_PROP',
        // 'EQP_20_HIDR_VLR_PROP',
        // 'EQP_21_HIDR_VLR_PROP',
        // 'EQP_22_HIDR_VLR_PROP',
        // 'EQP_23_HIDR_VLR_PROP',
        // 'EQP_05_HIDR_ZERA_HIDR',
        // 'EQP_06_HIDR_ZERA_HIDR',
        // 'EQP_07_HIDR_ZERA_HIDR',
        // 'EQP_08_HIDR_ZERA_HIDR',
        // 'EQP_09_HIDR_ZERA_HIDR',
        // 'EQP_10_HIDR_ZERA_HIDR',
        // 'EQP_11_HIDR_ZERA_HIDR',
        // 'EQP_12_HIDR_ZERA_HIDR',
        // 'EQP_13_HIDR_ZERA_HIDR',
        // 'EQP_14_HIDR_ZERA_HIDR',
        // 'EQP_15_HIDR_ZERA_HIDR',
        // 'EQP_16_HIDR_ZERA_HIDR',
        // 'EQP_17_HIDR_ZERA_HIDR',
        // 'EQP_18_HIDR_ZERA_HIDR',
        // 'EQP_19_HIDR_ZERA_HIDR',
        // 'EQP_20_HIDR_ZERA_HIDR',
        // 'EQP_21_HIDR_ZERA_HIDR',
        // 'EQP_22_HIDR_ZERA_HIDR',
        // 'EQP_23_HIDR_ZERA_HIDR',
        'EQP_RL1_ULT_STATUS',
        'EQP_RL2_ULT_STATUS',
        'EQP_RL3_ULT_STATUS',
        'EQP_RL4_ULT_STATUS',
        'EQP_RL5_ULT_STATUS',
        'EQP_RL6_ULT_STATUS',
        // 'EQP_01_EAE_ENVIA_ALERTA','EQP_02_EAE_ENVIA_ALERTA',
        // 'EQP_03_EAP_ENVIA_ALERTA','EQP_04_EAP_ENVIA_ALERTA',
        // 'EQP_05_EDP_ENVIA_ALERTA','EQP_06_EDP_ENVIA_ALERTA','EQP_07_EDP_ENVIA_ALERTA','EQP_08_EDP_ENVIA_ALERTA','EQP_09_EDP_ENVIA_ALERTA','EQP_10_EDP_ENVIA_ALERTA','EQP_11_EDP_ENVIA_ALERTA',
        // 'EQP_12_EDP_ENVIA_ALERTA','EQP_13_EDP_ENVIA_ALERTA','EQP_14_EDP_ENVIA_ALERTA','EQP_15_EDP_ENVIA_ALERTA','EQP_16_EDP_ENVIA_ALERTA','EQP_17_EDP_ENVIA_ALERTA','EQP_18_EDP_ENVIA_ALERTA',
        // 'EQP_19_EDP_ENVIA_ALERTA','EQP_20_EDP_ENVIA_ALERTA','EQP_21_EDP_ENVIA_ALERTA','EQP_22_EDP_ENVIA_ALERTA','EQP_23_EDP_ENVIA_ALERTA',
        'EQP_05_DT_ULT_STATUS',
        'EQP_06_DT_ULT_STATUS',
        'EQP_07_DT_ULT_STATUS',
        'EQP_08_DT_ULT_STATUS',
        'EQP_09_DT_ULT_STATUS',
        'EQP_10_DT_ULT_STATUS',
        'EQP_11_DT_ULT_STATUS',
        'EQP_12_DT_ULT_STATUS',
        'EQP_13_DT_ULT_STATUS',
        'EQP_14_DT_ULT_STATUS',
        'EQP_15_DT_ULT_STATUS',
        'EQP_16_DT_ULT_STATUS',
        'EQP_17_DT_ULT_STATUS',
        'EQP_18_DT_ULT_STATUS',
        'EQP_19_DT_ULT_STATUS',
        'EQP_20_DT_ULT_STATUS',
        'EQP_21_DT_ULT_STATUS',
        'EQP_22_DT_ULT_STATUS',
        'EQP_23_DT_ULT_STATUS',
        'EQP_05_ULT_STATUS',
        'EQP_06_ULT_STATUS',
        'EQP_07_ULT_STATUS',
        'EQP_08_ULT_STATUS',
        'EQP_09_ULT_STATUS',
        'EQP_10_ULT_STATUS',
        'EQP_11_ULT_STATUS',
        'EQP_12_ULT_STATUS',
        'EQP_13_ULT_STATUS',
        'EQP_14_ULT_STATUS',
        'EQP_15_ULT_STATUS',
        'EQP_16_ULT_STATUS',
        'EQP_17_ULT_STATUS',
        'EQP_18_ULT_STATUS',
        'EQP_19_ULT_STATUS',
        'EQP_20_ULT_STATUS',
        'EQP_21_ULT_STATUS',
        'EQP_22_ULT_STATUS',
        'EQP_23_ULT_STATUS',
        // 'EQP_01_EAE_VLR_PROP',
        // 'EQP_02_EAE_VLR_PROP',
        // 'EQP_03_EAP_VLR_PROP',
        // 'EQP_04_EAP_VLR_PROP',
        // 'EQP_24_TEMP1_ENVIA_ALERTA','EQP_24_TEMP2_ENVIA_ALERTA','EQP_24_TEMP3_ENVIA_ALERTA','EQP_24_TEMP4_ENVIA_ALERTA','EQP_24_TEMP5_ENVIA_ALERTA','EQP_24_TEMP6_ENVIA_ALERTA',
        // 'EQP_24_TEMP7_ENVIA_ALERTA','EQP_24_TEMP8_ENVIA_ALERTA',
        // 'EQP_24_TEMP1_VLR_PROP',
        // 'EQP_24_TEMP2_VLR_PROP',
        // 'EQP_24_TEMP3_VLR_PROP',
        // 'EQP_24_TEMP4_VLR_PROP',
        // 'EQP_24_TEMP5_VLR_PROP',
        // 'EQP_24_TEMP6_VLR_PROP',
        // 'EQP_24_TEMP7_VLR_PROP',
        // 'EQP_24_TEMP8_VLR_PROP',
        'MONIT_INF',
        'MONIT_SUP',
        // 'SUP_NIVEL_REMOTO',
        'MILLIS_CONSULTA',
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'CLI_ID', 'EQP_CLI_ID');
    }

    public function statusAll()
    {
        return $this->hasMany(DeviceStatus::class, 'STT_EQP_ID', 'EQP_ID'); //->limit(100);
    }
    public function alertPanel()
    {
        return $this->hasMany(Alert::class, 'ALT_EQP_ID', 'EQP_ID'); //->limit(30);
    }
    public function alertOne()
    {
        return $this->hasOne(Alert::class, 'ALT_EQP_ID', 'EQP_ID');
    }
    public function statusLast()
    {
        return $this->hasOne(DeviceStatus::class, 'STT_EQP_ID', 'EQP_ID')->orderBy('STT_DATA_HORA', 'desc'); //->limit(300);
    }

    public function historic()
    {
        return $this->hasMany(DeviceHistoric::class, 'HST_EQP_ID', 'EQP_ID'); //->limit(1500);
    }
    public function statusHistoric()
    {
        return $this->hasMany(DeviceStatusHistoric::class, 'STT_EQP_ID', 'EQP_ID'); //->limit(6000);
    }

    public function user()
    {
        return $this->belongsToMany(User::class, 'PERM_USR_EQP', 'PRM_EQP_ID', 'PRM_USR_ID');
        // return $this->belongsToMany(User::class)->using(RoleUser::class);
    }

    public static function clearMany($device)
    {
        foreach ($device as $kD => $d) {
            $c[$kD] = self::clear($d);
        }

        return $c ?? [];
    }
    public static function clear($device)
    {
        $master = auth()->user()->USR_MASTER;

        if (isset($device['last_status'])) {
            $c['last_status'] = $device['last_status'];
            unset($device['last_status']);
        }

        foreach ($device as $kD => $d) {

            // if($d === '' || $d === null || $d == 'NA') continue;

            if ($master != 'S') {
                if (in_array($kD, ['EQP_TELA', 'EQP_SENHA', 'EQP_SO_NIVEL', 'EQP_OP_SENSOR_NIVEL', 'EQP_SO_MONITORAMENTO']))
                    continue;
            }
            if ($master == 'N') {
                if (in_array($kD, [
                    'EQP_RL1_IMG_ID',
                    'EQP_RL1_NOME',
                    'EQP_RL1_REG_HAB',
                    'EQP_RL2_IMG_ID',
                    'EQP_RL2_NOME',
                    'EQP_RL2_REG_HAB',
                    'EQP_RL3_IMG_ID',
                    'EQP_RL3_NOME',
                    'EQP_RL3_REG_HAB',
                    'EQP_RL4_IMG_ID',
                    'EQP_RL4_NOME',
                    'EQP_RL4_REG_HAB',
                    'EQP_RL5_IMG_ID',
                    'EQP_RL5_NOME',
                    'EQP_RL5_REG_HAB',
                    'EQP_RL6_IMG_ID',
                    'EQP_RL6_NOME',
                    'EQP_RL6_REG_HAB',
                ]))
                    continue;
            }

            $begin = substr($kD, 0, 4);

            if ($begin != 'EQP_') {
                $c[$kD] = $d;
                continue;
            }

            $kD = substr($kD, 4);

            $port = (int) substr($kD, 0, 2);

            if ($port) {

                if ($port < 24) { // Port

                    $type = $port < 5 ? 'analog' : 'digital';

                    if (substr($kD, 3, 1) == 'E')
                        $attr = substr($kD, 7);
                    else
                        $attr = substr($kD, 3); // Attr no common "HIDR,..."

                    if ($type == 'digital' && $master != 'S') {
                        if (in_array($attr, ['HIDR_UN', 'HIDR_VLR_PROP', 'HIDR_ZERA_HIDR']))
                            continue;
                    }

                    if ($type == 'digital' && $master == 'N') {
                        if (in_array($kD, ['EDP_CTT']))
                            continue;
                    }
                    if ($type == 'analog' && $master == 'N') {
                        if (in_array($kD, ['EAE_REG_MIN', 'EAE_REG_MAX']))
                            continue;
                    }

                    $c[$type][$port][$attr] = $d;
                } else { // Temperature
                    $attr = substr($kD, 7, 1); // index
                    $sub3 = substr($kD, 9); // attr
                    $c['temperature'][$attr][$sub3] = $d;
                    // $c['temperature'][$sub][$attr][$sub3] = $d;

                }
            } else {
                $c[$begin . $kD] = $d;
            }
            // else
            // if( $sub == 'RL' ){
            //     $sub = substr($kA, 6,1);
            //     $sub2 = substr($kA, 8);
            //     $c[$kD]['rl'][$sub][$sub2] = $d;
            // }
        }

        // $c['EQP_ATIVO'] = 'S';

        return $c;
    }

    static function removePortBoolInactive($ports)
    {

        return array_filter($ports, function ($p) {
            // return true;
            // return in_array($p['IMG_ID'], ['N', 'S']);
            return !empty($p['IMG_ID']);
        });
    }

    static function removePortIntInactive($ports)
    {

        return array_filter($ports, function ($p) {
            return (int) $p['IMG_ID'];
        });
    }

    static function clearToHistoric($historic)
    {
        return array_map(function ($h) {
            $ret = [
                'NOME' => $h['NOME'],
                'IMG_ID' => $h['IMG_ID']
            ];

            if (isset($h['ERR_1_INI'])) $ret['ERR_1_INI'] = $h['ERR_1_INI'];
            if (isset($h['ERR_1_FIM'])) $ret['ERR_1_FIM'] = $h['ERR_1_FIM'];
            if (isset($h['ERR_2_INI'])) $ret['ERR_2_INI'] = $h['ERR_2_INI'];
            if (isset($h['ERR_2_FIM'])) $ret['ERR_2_FIM'] = $h['ERR_2_FIM'];

            return $ret;
        }, $historic);
    }
    // static function portsActive($ports, $type){
    //     return array_map(function($h) use ($type) {
    //         return [
    //             'NOME' => $h['NOME'],
    //             'IMG_ID' => $h['IMG_ID']
    //         ];
    //     }, $ports);
    // }

    // protected static function unHide()
    // {
    //     $this->hidden=[];
    // }


    // public function scopeActive($query)
    // {
    //     $query->where('active', 1);
    // }

    protected static function booted()
    {
        // static::addGlobalScope(new ExampleScope);

        // static::addGlobalScope('permission', function (Builder $builder) {
        //     $builder->where('created_at', '<', now()->subYears(2000));
        // });
    }
}

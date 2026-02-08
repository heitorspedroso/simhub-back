<?php

namespace App\Models;

use App\Scopes\ExampleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceHistoric extends Model
{
    use HasFactory;

    protected $table = 'HISTORICO_TEMPO';

    protected $primaryKey = 'HST_ID';
    public $incrementing = false;
    protected $keyType = 'string';
    
    public $timestamps = false;

    // protected $attributes = [
    //     'EQP_NOME' => false,
    // ];

    // protected $fillable = [
    //     'EQP_ID',
    //     'EQP_CLI_ID',
    //     'EQP_NOME',
    //     'EQP_SUP_NIVEL_MIN',
    //     'EQP_SUP_NIVEL_MAX',
    //     'EQP_LITROS_TOTAL',
    //     'EQP_ORDEM_EXIBICAO',
    //     'EQP_CLASSIFIC_1',
    //     'EQP_CLASSIFIC_2',
    // ];

    protected $hidden = [
        'EQP_GATEWAY',
        'EQP_TELA',
        'EQP_SENDER_IDS',
        'EQP_SENHA',
        'EQP_OP_REMOTO',
        'EQP_LIGAR_RL1',
        'EQP_LIGAR_RL2',
        'EQP_LIGAR_RL3',
        'EQP_LIGAR_RL4',
        'EQP_LIGAR_RL5',
        'EQP_LIGAR_RL6',
        'EQP_SO_MONITORAMENTO',
        'EQP_RL1_DT_ULT_STATUS','EQP_RL1_IMG_ID','EQP_RL1_NOME',
        'EQP_RL2_DT_ULT_STATUS','EQP_RL2_IMG_ID','EQP_RL2_NOME',
        'EQP_RL3_DT_ULT_STATUS','EQP_RL3_IMG_ID','EQP_RL3_NOME',
        'EQP_RL4_DT_ULT_STATUS','EQP_RL4_IMG_ID','EQP_RL4_NOME',
        'EQP_RL5_DT_ULT_STATUS','EQP_RL5_IMG_ID','EQP_RL5_NOME',
        'EQP_RL6_DT_ULT_STATUS','EQP_RL6_IMG_ID','EQP_RL6_NOME',
        'MONIT_INF',
        'MONIT_SUP',

        'SUP_NIVEL_REMOTO',
        'MILLIS_CONSULTA',
    ];

    // public function client()
    // {
    //     return $this->hasOne(Client::class, 'CLI_ID', 'EQP_CLI_ID');
    // }

    // public function statusAll()
    // {
    //     return $this->hasMany(DeviceStatus::class, 'STT_EQP_ID', 'EQP_ID')->limit(100);
    // }
    // public function statusLast()
    // {
    //     return $this->hasOne(DeviceStatus::class, 'STT_EQP_ID', 'EQP_ID')->limit(1000)->orderBy('STT_DATA_HORA', 'desc');
    // }

    // public function user()
    // {
    //     return $this->belongsToMany(User::class, 'PERM_USR_EQP', 'PRM_EQP_ID', 'PRM_USR_ID');
    //     // return $this->belongsToMany(User::class)->using(RoleUser::class);
    // }

    public static function clearMany($device)
    {
        foreach( $device as $kD => $d ){
            $c[$kD] = self::clear($d);
        }

        return $c;
    }
    public static function clear($device)
    {
        $c['EQP_ATIVO'] = 'S'; // rand(0, 1) ? 'S' : 'N';

        if( isset($device['last_status']) ){
            $c['last_status'] = $device['last_status'];
            unset($device['last_status']);
        }

        foreach( $device as $kD => $d ){

            if($d === '' || $d === null || $d == 'NA') continue;

            $begin = substr($kD, 0,4);

            if( $begin != 'EQP_' ){
                $c[$kD] = $d;
                continue;
            }
            
            $kD = substr($kD, 4);

            $sub = (int) substr($kD, 0,2);

            if( $sub ){ 
                
                if($sub == 24){ // Temperature
                    $sub2 = substr($kD, 7,1);
                    $sub3 = substr($kD, 9);
                    $c['port'][$sub][$sub2][$sub3] = $d;
                }else{
                    $sub2 = substr($kD, 3);
                    $c['port'][$sub][$sub2] = $d;
                }
            }
            else{
                $c[$begin.$kD] = $d;
            }
            // else
            // if( $sub == 'RL' ){
            //     $sub = substr($kA, 6,1);
            //     $sub2 = substr($kA, 8);
            //     $c[$kD]['rl'][$sub][$sub2] = $d;
            // }
        }

        return $c;
    }

    

    // protected static function booted()
    // {
    //     static::addGlobalScope(new ExampleScope);

    //     // static::addGlobalScope('example', function (Builder $builder) {
    //     //     $builder->where('created_at', '<', now()->subYears(2000));
    //     // });
    // }

}

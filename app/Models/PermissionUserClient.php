<?php

namespace App\Models;

use App\Scopes\ExampleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionUserClient extends Model
{
    use HasFactory;

    protected $table = 'PERM_USR_CLI';

    protected $primaryKey = 'PRM_ID';
    public $timestamps = false;
    // protected $hidden = [];

    // public static function clearMany($alerts)
    // {
    //     foreach( $alerts as $kA => $a ){
            
    //         if($a = self::clear($a))
    //             $c[$kA] = $a;
    //     }

    //     return $c ?? [];
    // }

    // public static function clear($alert)
    // {
    //     foreach($alert as $k => $v){

    //         if( substr($k, 0,4) == 'ALT_')
    //             $k = substr($k, 4);

    //         if($v == 'N')
    //             continue;

    //         if( in_array( substr($k, 0,2), ['NI', 'TE']) || (int) $k){
    //             $r['port'][$k] = $v;
    //         }else{
    //             $r[$k] = $v;
    //         }
    //     }

    //     if( !isset($r['port']) ) return [];

    //     return $r;
    // }

    // public function device()
    // {
    //     return $this->belongsTo(Device::class, 'ALT_EQP_ID', 'EQP_ID');
    // }

    // public static function byDeviceId($device)
    // {
    //     $deviceIds = $device->reduce(function($acc, $d){
    //         $acc[] = $d['EQP_ID'];
    //         return $acc;
    //     });

    //     $alert = Alert:: // select('ALT_ID')
    //         whereIn('ALT_EQP_ID', $deviceIds)
    //         ->limit(100)
    //         ->get();

    //     return $alert->map( function( $al ){

    //         foreach( $al->getAttributes() as $kA => $a ){
    //             if($a) $ret[$kA] = $a;
    //         }

    //         if( isset($ret['ALT_ID']) ) unset($ret['ALT_ID']);

    //         return $ret;
    //     });

    //     // $alertField = ['ALT_1AN', 'ALT_2AN', 'ALT_3AN', 'ALT_4AN', 'ALT_5D', 'ALT_6D', 'ALT_8D', 'ALT_9D', 'ALT_10D', 'ALT_11D', 'ALT_12D', 'ALT_13D', 'ALT_14D', 'ALT_15D', 'ALT_16D', 'ALT_17D', 'ALT_18D', 'ALT_19D', 'ALT_20D', 'ALT_21D', 'ALT_22D', 'ALT_23D', 'ALT_TEMP1', 'ALT_TEMP2', 'ALT_TEMP3', 'ALT_TEMP4', 'ALT_TEMP5', 'ALT_TEMP6', 'ALT_TEMP7', 'ALT_TEMP8'];
    // }


}

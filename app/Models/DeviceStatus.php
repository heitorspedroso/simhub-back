<?php

namespace App\Models;

use App\Scopes\ExampleScope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceStatus extends Model
{
    use HasFactory;

    protected $table = 'TAB_STATUS';
    // protected $table = 'TAB_STATUS2';
    protected $primaryKey = 'STT_ID';

    protected $hidden = [
        'DT_ULT_STATUS',
        'STT_05_HIDR','STT_06_HIDR','STT_07_HIDR','STT_08_HIDR','STT_09_HIDR','STT_10_HIDR','STT_11_HIDR',
        'STT_12_HIDR','STT_13_HIDR','STT_14_HIDR','STT_15_HIDR','STT_16_HIDR','STT_17_HIDR','STT_18_HIDR','STT_19_HIDR',
        'STT_20_HIDR','STT_21_HIDR','STT_22_HIDR','STT_23_HIDR',
        'STT_25_SAP', 'STT_26_SAP',
        'STT_LORA_DB',
        'STT_RL1', 'STT_RL2', 'STT_RL3', 'STT_RL4', 'STT_RL5', 'STT_RL6',
    ];


    public static function clear(&$deviceStatus)
    {   
        foreach( $deviceStatus as $kD => $d ){
        // foreach( $deviceStatus->getAttributes() as $kD => $d ){
            
            // if(!$d) continue;  // || $d == 'NA') continue;
                        
            if( substr($kD, 0,3) != 'STT' ){
                $ds[$kD] = $d;
                continue;
            }
           
            $kD = substr($kD, 4);
            $sub = (int) substr($kD, 0,2);

            // if($sub > 23) continue;

            if( $sub ){
                // dd($kD, $sub, $d);
                $type = $sub < 5 ? 'analog' : 'digital';

                $attr = substr($kD, 3);

                if( in_array($attr, ['EAE','EAP','EDP']) )
                    $ds[$type][$sub]['value'] = $d;
                // else if( $attr == 'REG' )
                //     $ds[$type][$sub]['raw'] = $d;
            }
            else
            if( substr($kD, 0,4) == 'TEMP'){
                // if($d == 0) continue;
                $sub = substr($kD, 4);
                // $ds['port'][24][$sub]['status']['value'] = $d;
                $ds['temperature'][$sub]['value'] = $d;
            }
            else{
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
    public static function clearMany(&$deviceStatus)
    {
        foreach( $deviceStatus as $kD => $d ){
            $ds[$kD] = self::clear($d);
        }

        return $ds;
    }

    static function removeTemperatureInactive($temp){

        return array_filter($temp, function($p){

            return !empty($p['IMG_ID']);

        });
    }

    public static function clearChartMany(&$deviceStatus)
    {
        foreach( $deviceStatus as $kD => $d ){
            $ds[$kD] = self::clear($d);
        }

        return $ds;
    }

    public static function clearChart(&$deviceStatus)
    {   
        foreach( $deviceStatus as $kD => $d ){
            
        }
    }

    
    // public function scopeActive($query)
    // {
    //     $query->where('active', 1);
    // }

    // protected static function booted()
    // {
    //     static::addGlobalScope(new ExampleScope);

    //     // static::addGlobalScope('example', function (Builder $builder) {
    //     //     $builder->where('created_at', '<', now()->subYears(2000));
    //     // });
    // }

}

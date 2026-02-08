<?php

namespace App\Models;

use App\Scopes\ExampleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $table = 'DISPOSITIVOS_COM_ALERTAS';

    protected $primaryKey = 'ALT_ID';
    public $timestamps = false;
    // protected $dateFormat = 'U';

    // protected $attributes = [
    //     'EQP_NOME' => false,
    // ];
    // protected $fillable = [];

    protected $hidden = [
        'ALT_DATA',
        'ALT_MENSAGEM',
        'ALT_MSG_NORMALIZACAO_ENVIAR',
        'ALT_MSG_ERRO_ENVIADA',
        'ALT_ULTIMA_LEITURA'
    ];

    public static function clearMany($alerts)
    {
        foreach ($alerts as $kA => $a) {

            if ($a = self::clear($a))
                $c[$kA] = $a;
        }

        return $c ?? [];
    }

    public static function clear($alert)
    {
        foreach ($alert as $k => $v) {

            if (substr($k, 0, 4) == 'ALT_')
                $k = substr($k, 4);
            else
                $r[$k] = $v;

            if (in_array(substr($k, 0, 2), ['EA', 'TE']) || (int) $k) {

                if ($v == 'N')
                    continue;

                $r['port'][$k] = $v;
            } else {
                $r[$k] = $v;
            }
        }

        // if (!isset($r['port'])) return [];

        return $r ?? [];
    }

    public function device()
    {
        return $this->belongsTo(Device::class, 'ALT_EQP_ID', 'EQP_ID');
    }

    public static function byDeviceId($device)
    {
        $deviceIds = $device->reduce(function ($acc, $d) {
            $acc[] = $d['EQP_ID'];
            return $acc;
        });

        $alert = Alert:: // select('ALT_ID')
            whereIn('ALT_EQP_ID', $deviceIds)
            ->limit(1000)
            ->get();

        return $alert->map(function ($al) {

            foreach ($al->getAttributes() as $kA => $a) {
                if ($a) $ret[$kA] = $a;
            }

            if (isset($ret['ALT_ID'])) unset($ret['ALT_ID']);

            return $ret;
        });
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

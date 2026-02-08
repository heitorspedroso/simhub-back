<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'PEDIDOS';

    protected $primaryKey = 'ID';
    
    public $timestamps = false;

    // protected $attributes = [
    //     'EQP_NOME' => false,
    // ];

    public static function countActive()
    {
        return self::where('ATIVO', '=', 'S')->count();
    }

    protected $fillable = [
        'ID',
        'USR_ID',
        'EQP_TIPO_ID',
        'ATIVO',
    ];

}

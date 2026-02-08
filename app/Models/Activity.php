<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Activity extends Model
{
    use HasFactory;

    protected $table = 'ATIVIDADES';
    public $timestamps = false;
    protected $fillable = [ 'AT_ATIVIDADE', 'AT_USR_ID', 'AT_QUERY', 'AT_CODE_RETURN', 'AT_DATA', 'AT_MODULO', 'AT_IP' ];

    function user()
    {
        return $this->hasOne(User::class, 'USR_ID', 'AT_USR_ID');
    }

}

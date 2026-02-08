<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $table = 'CLIENTES';

    protected $primaryKey = 'CLI_ID';
    public $incrementing = true;
    // protected $keyType = 'string';
    public $timestamps = false;

    // protected $table = 'client';
    protected $fillable = [
        'CLI_ID',
        'CLI_NOME',
        'CLI_NOME_REDUZIDO',
        'CLI_EMAIL',
        'CLI_CEP',
        'CLI_ESTADO',
        'CLI_CIDADE',
        'CLI_ENDERECO',
        'CLI_NUMERO',
        'CLI_BAIRRO',
        'CLI_COMPLEMENTO',
        'CLI_FONE',
        'CLI_CONTATO',
        'CLI_ATIVO',
        'CLI_OBSERVACOES',
        'CLI_FOTO'
    ];

    protected $hidden = [
        'CLI_RPS_ID',
    ];

    public function user()
    {
        return $this->belongsToMany(User::class, 'PERM_USR_CLI', 'PRM_USR_ID', 'PRM_CLI_ID');
    }

    public function contact()
    {
        return $this->hasMany(Contact::class, 'CTT_CLI_ID', 'CLI_ID');
    }

    public function device()
    {
        return $this->hasMany(Device::class, 'EQP_CLI_ID', 'CLI_ID');
    }


    // protected static function booted()
    // {
    //     static::where('created_at', '<', now()->subYears(2000));
    // }
}

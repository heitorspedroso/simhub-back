<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    use HasFactory;

    protected $table = 'CLIENTES_CONTATOS';

    protected $primaryKey = 'CTT_ID';
    public $incrementing = true;
    public $timestamps = false;

    protected $fillable = [
        'CTT_CLI_ID',
        'CTT_FORMA_TRATAMENTO',
        'CTT_CONTATO',
        'CTT_DDI',
        'CTT_DDD',
        'CTT_FONE',
        'CTT_EMAIL',
        'CTT_ATIVO',
        'CTT_MASTER',
    ];

    protected $hidden = [
        'CTT_RPS_ID',
    ];

    public function client()
    {
        return $this->hasOne(Client::class, 'CLI_ID', 'CTT_CLI_ID');
    }

    // protected static function booted()
    // {
    //     static::where('created_at', '<', now()->subYears(2000));
    // }
}

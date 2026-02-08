<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'USUARIOS';

    protected $primaryKey = 'USR_ID';
    public $timestamps = false;

    // protected $fillable = [
    //     'name',
    //     'email',
    //     'password',
    //     'role'
    // ];

    protected $hidden = [
        'USR_SENHA',
        'remember_token',
    ];

    // protected $casts = [
    //     'email_verified_at' => 'datetime',
    // ];

    // public function getLoginField($loginValue)
    // {
    //     return filter_var($loginValue, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
    // }

    public function client()
    {
        return $this->belongsToMany(Client::class, 'PERM_USR_CLI', 'PRM_USR_ID', 'PRM_CLI_ID');
    }

    public function device()
    {
        return $this->belongsToMany(Device::class, 'PERM_USR_EQP', 'PRM_USR_ID', 'PRM_EQP_ID');
    }
    
    public function order()
    {
        return $this->hasMany(Order::class, 'USR_ID', 'USR_ID');
    }
    // public function alert()
    // {
    //     return $this->hasMany(Order::class, 'USR_ID', 'USR_ID');
    // }
}
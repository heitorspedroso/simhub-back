<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeviceType extends Model
{
    use HasFactory;

    protected $table = 'IMAGENS';
    protected $hidden = [
        'IMG_CAMINHO',
    ];

    // protected $fillable = [ 'name', 'status' ];

}

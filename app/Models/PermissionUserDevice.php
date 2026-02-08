<?php

namespace App\Models;

use App\Scopes\ExampleScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermissionUserDevice extends Model
{
    use HasFactory;

    protected $table = 'PERM_USR_EQP';

    protected $primaryKey = 'PRM_ID';
    public $timestamps = false;

}

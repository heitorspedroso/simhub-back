<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;

class ActivityController extends Controller
{
    public function index()
    {
        if(request()->user()->USR_MASTER !== 'S') return;

        return Activity::with('user:USR_ID,USR_USER,USR_NOME')->orderByDesc('AT_ID')->limit(100)->get();
    }


    public static function store($data)
    {
        $activity = new Activity();

        $activity->AT_ID = Activity::select('AT_ID')->max('AT_ID') + 1;
        $activity->AT_ATIVIDADE = $data['activity'];
        $activity->AT_USR_ID = $data['user']->USR_ID ?? '';
        $activity->AT_QUERY = $data['query'];
        $activity->AT_CODE_RETURN = $data['httpCode'];
        $activity->AT_DATA = date('Y-m-d H:i:s');
        $activity->AT_MODULO = $data['module'];
        $activity->AT_IP = $data['ip'];

        $activity = $activity->save();

        return $activity ? true : false;
    }
}

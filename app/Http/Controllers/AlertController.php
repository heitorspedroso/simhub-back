<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller as BaseController;
use App\Models\Alert;
use Illuminate\Http\Request;

class AlertController extends BaseController
{
    public function index(Request $request)
    {
        if($request->user()->USR_MASTER === 'S'){

            $a = Alert::limit(1000)->with('device', function($q){
                $q->select('EQP_ID', 'EQP_NOME');
            })->get()->toArray();
            
            return Alert::clearMany($a);

        }else{
            // return $request->user()->client()->get();
        }
    }

    // public function show(Request $request, $id)
    // {
    //     if($request->user()->USR_MASTER === 'S')
    //         return Alert::find($id);
    //     else
    //         return $request->user()->client()->find($id);
    // }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller as BaseController;
use Illuminate\Http\Request;

class BootController extends BaseController
{
    public function boot(Request $request)
    {
        $boot = [
            'me' => $request->user()->only(['USR_ID','USR_USER','USR_NOME','USR_MASTER']),
            'admin' => [
                'dark' => true
            ]
        ];

        return $boot;

        // $boot['me'] = $request->user();

        // if($request->user()->USR_MASTER == 'S'){

        //     // $boot['client'] = Client::all();
        //     // $boot['device'] = Device::all()->toArray();
        //     // $boot['device'] = Device::clearMany( $boot['device'] );
        //     $boot['me'] = $request->user();

        // }else{

        //     // $request->user()->client;
        //     // $request->user()->device;
        //     // $request->user()->with(['client','device'])->get();
        //     // $boot['user'] = User::with(['client', 'device'])->where('USR_ID', '1')->get();
           
        //     $all = $request->user()->toArray();
    
        //     $boot['client'] = $all['client'];
        //     unset($all['client']);
    
        //     $boot['device'] = Device::clearMany($all['device']);
        //     unset($all['device']);
    
        //     $boot['me'] = $request->user();
        //     // $boot['me'] = $all;
        // }

    }
}



// return DB::table('DISPOSITIVOS')
//     ->select('CLIENTES.*', 'DISPOSITIVOS.*', 'TAB_STATUS.*')
//     ->join('PERM_USR_EQP', 'PERM_USR_EQP.PRM_EQP_ID','=','DISPOSITIVOS.EQP_ID')
//     // ->join('PERM_USR_EQP', 'PERM_USR_EQP.PRM_USR_ID','=','DISPOSITIVOS.EQP_ID')
//     ->join('CLIENTES', 'DISPOSITIVOS.EQP_CLI_ID','=','CLIENTES.CLI_ID')
//     ->join('TAB_STATUS', 'TAB_STATUS.STT_EQP_ID','=','DISPOSITIVOS.EQP_ID')
//     ->where('PERM_USR_EQP.PRM_USR_ID','=', $request->user()->USR_ID )
//     ->limit(1)
//     ->get();
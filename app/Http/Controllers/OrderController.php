<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        if($request->user()->USR_MASTER === 'S')
            return Order::all();
        else
            return $request->user()->order()->get();
    }

    

    /*public function show($id)
    {
        $device = Device::with(['statusLast', 'client:CLI_ID,CLI_NOME'])->find($id);
        if(!$device) return null;

        $device = $device->toArray();
    // return $device['status_last'];
    // $device = Device::clear($device);
    // return $device;
        $detail['client'] = $device['client'];
        unset($device['client']);

        if($device['status_last']){
            $detail += DeviceStatus::clear($device['status_last']);
            unset($device['status_last']);
        }
    // return $detail['status'];
    // return $detail;
        $device = Device::clear($device);
        // $detail['status'] = array_replace_recursive($detail['status'], $device['sensor']);
        // unset($device['sensor']);
    // return $device['sensor'];
        
        $detail = array_replace_recursive($detail, $device);
        // $detail += $device;
        unset($device);
        
        // sort($detail);

        return $detail;
        
        // $ds = DeviceStatus::limit(2)->get();
        // $ds = $ds->toArray();
        // $detail['status'] = $ds;
        
        // return Device::with(['status'])->limit(10)->get();
        // return Device::with(['status'])->get();
    }

    public function store(Request $request)
    {
        // $request->validate([
        //     'client_id' => 'required',
        //     'device_type_id' => 'required',
        // ]);

        $request->request->add(['ATIVO' => 'S']);
        
        return Order::create($request->all());
    }

    public function edit(Device $device)
    {
    }

    public function update(Request $request)
    {
        $device = Device::findOrFail( $request->id );
        $device->name = $request->input('name');
        $device->device_type_id = $request->input('device_type_id');
        $device->code = $request->input('code');
        $device->alert_level_1 = $request->input('alert_level_1');
        $device->alert_level_2 = $request->input('alert_level_2');
        $device->total_liter = $request->input('total_liter');
        $device->ordem = $request->input('ordem');
        $device->classification_1 = $request->input('classification_1');
        $device->classification_2 = $request->input('classification_2');
        $device->classification_3 = $request->input('classification_3');
        $device->maintenance_last_1 = $request->input('maintenance_last_1');
        $device->maintenance_last_2 = $request->input('maintenance_last_2');
        $device->maintenance_next_1 = $request->input('maintenance_next_1');
        $device->maintenance_next_2 = $request->input('maintenance_next_2');
        $device->status = $request->input('status');

        if( $device->save() ){
            return true;
        }
    }

    public function destroy($id)
    {
        $device = Device::destroy($id);
        if( $device )
          return true;
    }*/
}

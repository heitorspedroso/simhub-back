<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use Illuminate\Http\Request;

class DeviceTypeController extends Controller
{
    public function index()
    {
        return DeviceType::get()->reduce(function ($acc, $d){
            $acc[ $d['IMG_ID'] ] = $d['IMG_DESCRICAO'];
            return $acc;
        }, []);
    }

    public function show(DeviceType $deviceType)
    {
    }

    public function create()
    {
    }

    public function edit(DeviceType $deviceType)
    {
    }

    public function update(Request $request, DeviceType $deviceType)
    {
    }

    public function destroy(DeviceType $deviceType)
    {
    }
}

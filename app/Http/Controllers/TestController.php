<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Test;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function index()
    {
        return Test::all();
    }

    public function test(Request $request)
    {
        return $request->user()->client()->limit(1000)->get();
        // return Client::all()->user()->limit(1000)->get();
        // return Client::select('CLI_ID', 'CLI_NOME', 'CLI_ATIVO')->limit(1000)->get();
    }

    // public function show(DeviceType $deviceType)
    // {
    // }

    // public function create()
    // {
    // }

    // public function edit(DeviceType $deviceType)
    // {
    // }

    // public function update(Request $request, DeviceType $deviceType)
    // {
    // }

    // public function destroy(DeviceType $deviceType)
    // {
    // }
}

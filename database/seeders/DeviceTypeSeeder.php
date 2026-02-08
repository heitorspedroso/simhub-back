<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;

class DeviceTypeSeeder extends Seeder
{
    public static function run()
    {
        $now = Carbon::now('utc')->toDateTimeString();

        \App\Models\DeviceType::insert([
            [
                'name' => 'Bomba',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Caixa D\'água',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Porta',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Gerador',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Vagas/Veículos',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],
            [
                'name' => 'Ar Condicionado',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]/*,
            [
                'name' => 'Bomba autoescorvante',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Bomba submersível',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Bomba multiestágio',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Bomba Monoestágio',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Bomba Submersa',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Bomba mancal',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Multiestágios-OLD',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Submersíveis-OLD',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Chiller',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Torre resfriamento',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Branco',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ],[
                'name' => 'Pressurizador',
                'status' => 1,
                'created_at' => $now,
                'updated_at' => $now
            ]*/
        ]);
    }
}

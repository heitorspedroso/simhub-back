<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
// use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        UserDevSeeder::run();
        // \App\Models\User::factory(2)->create();

        // \App\Models\Client::factory(10)->create();
        
        // DB::table('client_user')->create(
            //     [
                //         'client_id' => 1,
                //         'user_id' => 1,
                //     ],
                //     [
                    //         'client_id' => 2,
                    //         'user_id' => 1,
                    //     ],
        //     [
            //         'client_id' => 3,
        //         'user_id' => 2,
        //     ]
        // );

        DeviceTypeSeeder::run();
        DeviceSeeder::run();
    }
}
<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserDevSeeder extends Seeder
{
    public static function run()
    {
        // \App\Models\User::factory(3)->create();

        \App\Models\User::create([
            'name' => 'Sim Monitoramento',
            'email' => 'sim@sim.com',
            'role' => 2, // Admin
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            // 'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
        \App\Models\User::create([
            'name' => 'Jhonatan',
            'email' => 'jho@sim.com',
            'role' => 1, // Dev
            'email_verified_at' => now(),
            'password' => Hash::make('123456'),
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ]);
    }
}
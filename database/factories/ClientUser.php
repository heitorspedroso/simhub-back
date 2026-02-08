<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ClientFactory extends Factory
{
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'nick' => $this->faker->firstName(),
            'zip' => '02407050', // 
            'state' => 'SP',
            'city' => 'São Paulo',
            'address' => 'Rua Rafael de Oliveira',
            'number' => '310',
            'district' => 'Água Fria',
            'complement' => '',
            'phone' => '(11) 2971-5699',
            'contact_name' => $this->faker->firstNameMale(),
            'status' => (bool)random_int(0, 1),
            'observation' => $this->faker->observation
        ];
    }
}

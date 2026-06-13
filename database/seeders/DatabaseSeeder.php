<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::factory()->create([
            'name'     => 'João Silva',
            'email'    => 'joao@empresa.com',
            'role'     => 'employee',
            'country'  => 'Brasil',
            'currency' => 'BRL',
        ]);

        User::factory()->create([
            'name'     => 'John Smith',
            'email'    => 'john@empresa.com',
            'role'     => 'employee',
            'country'  => 'EUA',
            'currency' => 'USD',
        ]);

        User::factory()->create([
            'name'     => 'Pierre Dubois',
            'email'    => 'pierre@empresa.com',
            'role'     => 'employee',
            'country'  => 'França',
            'currency' => 'EUR',
        ]);

        User::factory()->create([
            'name'     => 'Akira Tanaka',
            'email'    => 'akira@empresa.com',
            'role'     => 'employee',
            'country'  => 'Japão',
            'currency' => 'JPY',
        ]);

        User::factory()->create([
            'name'     => 'Carlos Garcia',
            'email'    => 'carlos@empresa.com',
            'role'     => 'employee',
            'country'  => 'México',
            'currency' => 'MXN',
        ]);

        User::factory()->create([
            'name'     => 'Sarah Johnson',
            'email'    => 'sarah@empresa.com',
            'role'     => 'employee',
            'country'  => 'Reino Unido',
            'currency' => 'GBP',
        ]);

        User::factory()->create([
            'name'     => 'Finance Team',
            'email'    => 'finance@empresa.com',
            'role'     => 'finance',
            'country'  => 'França',
            'currency' => 'EUR',
        ]);
    }
}

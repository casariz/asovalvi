<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuarios específicos del sistema
        User::create([
            'first_name' => 'Administrador',
            'last_name' => 'Sistema',
            'email' => 'admin@asovalvi.com',
            'password' => Hash::make('password'),
            'position' => 'Administrador',
            'phone' => '+57 300 123 4567',
            'status' => 1
        ]);

        User::create([
            'first_name' => 'Gerente',
            'last_name' => 'General',
            'email' => 'gerente@asovalvi.com',
            'password' => Hash::make('password'),
            'position' => 'Gerente',
            'phone' => '+57 301 234 5678',
            'status' => 1
        ]);

        User::create([
            'first_name' => 'Coordinador',
            'last_name' => 'Operaciones',
            'email' => 'coordinador@asovalvi.com',
            'password' => Hash::make('password'),
            'position' => 'Coordinador',
            'phone' => '+57 302 345 6789',
            'status' => 1
        ]);

        User::create([
            'first_name' => 'Supervisor',
            'last_name' => 'Campo',
            'email' => 'supervisor@asovalvi.com',
            'password' => Hash::make('password'),
            'position' => 'Supervisor',
            'phone' => '+57 303 456 7890',
            'status' => 1
        ]);

        // Crear usuarios adicionales usando factory
        User::factory(10)->create();
    }
}
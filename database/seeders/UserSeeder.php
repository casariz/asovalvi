<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Crear usuario administrador
        DB::table('users')->insert([
            'first_name' => 'Administrador',
            'last_name' => 'Sistema',
            'document_number' => '12345678A',
            'user_type' => 'admin',
            'password' => Hash::make('password'),
            'status' => 'ACTIVE',
        ]);

        // Crear presidente
        DB::table('users')->insert([
            'first_name' => 'Juan',
            'last_name' => 'Pérez',
            'document_number' => '23456789B',
            'user_type' => 'president',
            'password' => Hash::make('password'),
            'status' => 'ACTIVE',
        ]);

        // Crear secretario
        DB::table('users')->insert([
            'first_name' => 'María',
            'last_name' => 'García',
            'document_number' => '34567890C',
            'user_type' => 'secretary',
            'password' => Hash::make('password'),
            'status' => 'ACTIVE',
        ]);

        // Crear tesorero
        DB::table('users')->insert([
            'first_name' => 'Carlos',
            'last_name' => 'Rodríguez',
            'document_number' => '45678901D',
            'user_type' => 'treasurer',
            'password' => Hash::make('password'),
            'status' => 'ACTIVE',
        ]);

        // Crear algunos miembros
        DB::table('users')->insert([
            'first_name' => 'Ana',
            'last_name' => 'López',
            'document_number' => '56789012E',
            'user_type' => 'member',
            'password' => Hash::make('password'),
            'status' => 'ACTIVE',
        ]);

        DB::table('users')->insert([
            'first_name' => 'Pedro',
            'last_name' => 'Martínez',
            'document_number' => '67890123F',
            'user_type' => 'member',
            'password' => Hash::make('password'),
            'status' => 'ACTIVE',
        ]);

        DB::table('users')->insert([
            'first_name' => 'Lucía',
            'last_name' => 'Fernández',
            'document_number' => '78901234G',
            'user_type' => 'member',
            'password' => Hash::make('password'),
            'status' => 'ACTIVE',
        ]);
    }
}
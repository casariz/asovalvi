<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{    public function run(): void
    {
        $this->call([
            StatusDescriptionSeeder::class,
            UserSeeder::class,
            MeetingSeeder::class,           // Primero las reuniones
            MeetingTopicSeeder::class,      // Luego los temas de reuniones
            TaskSeeder::class,              // Luego las tareas (que referencian reuniones)
        ]);
    }
}
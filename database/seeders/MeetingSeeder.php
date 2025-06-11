<?php

namespace Database\Seeders;

use App\Models\Meeting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create 10 sample meetings
        Meeting::factory()->count(10)->create();

        // You can also create specific meetings if needed
        Meeting::create([
            'meeting_date' => '2024-12-01',
            'start_hour' => '10:00:00',
            'called_by' => 'Juan Pérez',
            'director' => 'María García',
            'secretary' => 'Carlos López',
            'placement' => 'Sala de Juntas Principal',
            'meeting_description' => 'Reunión mensual de coordinación',
            'topics' => 'Revisión de actividades, planificación próximo mes',
            'created_by' => 'Admin',
            'creation_date' => now(),
            'status' => 1,
        ]);
    }
}
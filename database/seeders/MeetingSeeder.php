<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingSeeder extends Seeder
{    public function run(): void
    {
        $meetings = [
            [
                'meeting_date' => now()->addDays(5)->toDateString(),
                'start_hour' => '18:00:00',
                'called_by' => 2, // Presidente
                'director' => 2, // Presidente
                'secretary' => 3, // Secretario
                'placement' => 'Sede de la Asociación',
                'meeting_description' => 'Reunión ordinaria mensual para revisar actividades y planificar el mes',
                'topics' => 'Revisión de actividades del mes anterior, planificación de eventos, presupuesto.',
                'created_by' => 2,
                'creation_date' => now(),
                'status' => 'SCHEDULED',
            ],
            [
                'meeting_date' => now()->addDays(15)->toDateString(),
                'start_hour' => '19:30:00',
                'called_by' => 2, // Presidente
                'director' => 2, // Presidente
                'secretary' => 3, // Secretario
                'placement' => 'Sala de reuniones virtual',
                'meeting_description' => 'Reunión trimestral de la junta directiva',
                'topics' => 'Revisión financiera, aprobación de nuevos miembros, planificación estratégica.',
                'created_by' => 2,
                'creation_date' => now(),
                'status' => 'SCHEDULED',
            ],
            [
                'meeting_date' => now()->addDays(20)->toDateString(),
                'start_hour' => '10:00:00',
                'called_by' => 5, // Miembro
                'director' => 5, // Miembro
                'secretary' => 3, // Secretario
                'placement' => 'Vivero La Esperanza',
                'meeting_description' => 'Taller educativo para miembros sobre técnicas de propagación',
                'topics' => 'Técnicas de esquejes, injertos, y semilleros. Práctica hands-on.',
                'created_by' => 5,
                'creation_date' => now(),
                'status' => 'SCHEDULED',
            ],
            [
                'meeting_date' => now()->subDays(10)->toDateString(),
                'start_hour' => '17:00:00',
                'called_by' => 2, // Presidente
                'director' => 2, // Presidente
                'secretary' => 3, // Secretario
                'placement' => 'Sede de la Asociación',
                'meeting_description' => 'Reunión urgente para discutir nuevas normativas del sector',
                'topics' => 'Análisis de nuevas normativas sanitarias, plan de adaptación.',
                'created_by' => 2,
                'creation_date' => now()->subDays(15),
                'status' => 'COMPLETE',
            ],
        ];

        foreach ($meetings as $meeting) {
            DB::table('meetings')->insert($meeting);
        }
    }
}
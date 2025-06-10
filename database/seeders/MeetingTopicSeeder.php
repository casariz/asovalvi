<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MeetingTopicSeeder extends Seeder
{    public function run(): void
    {
        $topics = [
            [
                'meeting_id' => 1,
                'type' => 'discussion',
                'topic' => 'Planificación del evento anual de intercambio de plantas',
                'created_by' => 2, // Presidente
                'creation_date' => now(),
                'status' => 'PENDING',
            ],
            [
                'meeting_id' => 1,
                'type' => 'proposal',
                'topic' => 'Propuesta de aumento de cuota anual para 2024',
                'created_by' => 4, // Tesorero
                'creation_date' => now(),
                'status' => 'PENDING',
            ],
            [
                'meeting_id' => 1,
                'type' => 'information',
                'topic' => 'Nuevas normativas sanitarias para viveros',
                'created_by' => 3, // Secretario
                'creation_date' => now(),
                'status' => 'PENDING',
            ],
            [
                'meeting_id' => 2,
                'type' => 'decision',
                'topic' => 'Aprobación de nuevos miembros solicitantes',
                'created_by' => 2, // Presidente
                'creation_date' => now(),
                'status' => 'PENDING',
            ],
            [
                'meeting_id' => 2,
                'type' => 'review',
                'topic' => 'Revisión del presupuesto del primer trimestre',
                'created_by' => 4, // Tesorero
                'creation_date' => now(),
                'status' => 'PENDING',
            ],
            [
                'meeting_id' => 4,
                'type' => 'urgent',
                'topic' => 'Adaptación a nuevas normativas de etiquetado',
                'created_by' => 2, // Presidente
                'creation_date' => now()->subDays(10),
                'status' => 'COMPLETE',
            ],
            [
                'meeting_id' => 4,
                'type' => 'decision',
                'topic' => 'Contratación de asesoría legal especializada',
                'created_by' => 2, // Presidente
                'creation_date' => now()->subDays(10),
                'status' => 'COMPLETE',
            ],
        ];

        foreach ($topics as $topic) {
            DB::table('meeting_topics')->insert($topic);
        }
    }
}
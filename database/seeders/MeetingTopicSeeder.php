<?php

namespace Database\Seeders;

use App\Models\MeetingTopic;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class MeetingTopicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunos topics para meetings existentes si los hay
        if (Meeting::count() > 0 && User::count() > 0) {
            $meetings = Meeting::all();
            $users = User::all();

            foreach ($meetings as $meeting) {
                // Crear entre 2 y 5 topics por meeting
                $topicsCount = rand(2, 5);
                
                for ($i = 0; $i < $topicsCount; $i++) {
                    MeetingTopic::create([
                        'meeting_id' => $meeting->meeting_id,
                        'type' => collect(['General', 'Informativo', 'Decisión', 'Seguimiento'])->random(),
                        'topic' => collect([
                            'Revisión del presupuesto anual',
                            'Aprobación de nuevas políticas',
                            'Evaluación de resultados trimestrales',
                            'Planificación de actividades',
                            'Revisión de procesos internos',
                            'Análisis de mercado',
                            'Propuestas de mejora',
                            'Seguimiento de proyectos'
                        ])->random(),
                        'created_by' => $users->random()->id,
                        'creation_date' => now()->subDays(rand(1, 30)),
                        'status' => collect([1, 2])->random(),
                    ]);
                }
            }
        } else {
            // Si no hay meetings o users, crear algunos topics básicos usando factories
            MeetingTopic::factory(10)->create();
        }
    }
}
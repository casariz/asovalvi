<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call([
            // 1. Estados primero (requerido por casi todas las tablas)
            StateSeeder::class,
            
            // 2. Usuarios (requerido por tasks, meetings, obligations, etc.)
            UserSeeder::class,
            
            // 3. Reuniones (requerido por tasks, meeting_topics, meeting_assistants)
            MeetingSeeder::class,
            
            // 4. Topics de reuniones (depende de meetings y users)
            MeetingTopicSeeder::class,
            
            // 5. Asistentes de reuniones (depende de meetings y users)
            MeetingAssistantSeeder::class,
            
            // 6. Tareas (depende de meetings, users y states)
            TaskSeeder::class,
            
            // 7. Obligaciones (depende de users)
            ObligationSeeder::class,
            
            // 8. Pagos (depende de obligations y users) - último
            PaymentSeeder::class,
        ]);
    }
}
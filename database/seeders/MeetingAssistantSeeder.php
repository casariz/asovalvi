<?php

namespace Database\Seeders;

use App\Models\MeetingAssistant;
use App\Models\Meeting;
use App\Models\User;
use Illuminate\Database\Seeder;

class MeetingAssistantSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Verificar que existan meetings y users
        $meetings = Meeting::all();
        $users = User::all();

        if ($meetings->isEmpty()) {
            $this->command->warn('No meetings found. Creating some meetings first...');
            Meeting::factory(5)->create();
            $meetings = Meeting::all();
        }

        if ($users->isEmpty()) {
            $this->command->warn('No users found. Creating some users first...');
            User::factory(10)->create();
            $users = User::all();
        }

        // Crear asistentes para cada meeting
        foreach ($meetings as $meeting) {
            // Crear entre 3 y 8 asistentes por meeting
            $assistantCount = rand(3, 8);
            
            for ($i = 0; $i < $assistantCount; $i++) {
                MeetingAssistant::factory()
                    ->create([
                        'meeting_id' => $meeting->meeting_id,
                        'user_id' => rand(1, 10) <= 7 ? $users->random()->id : null, // 70% probabilidad de tener user_id
                        'status' => 2 // Por defecto activo
                    ]);
            }
        }

        // Crear algunos asistentes adicionales con estados específicos
        MeetingAssistant::factory(10)->active()->create();
        MeetingAssistant::factory(5)->inactive()->create();
        MeetingAssistant::factory(8)->withUser()->create();
        MeetingAssistant::factory(3)->withoutUser()->create();

        $this->command->info('MeetingAssistant seeder completed successfully!');
    }
};
<?php

namespace Database\Seeders;

use App\Models\Obligation;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ObligationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Crear algunas obligaciones de ejemplo
        $users = User::all();
        
        if ($users->count() === 0) {
            // Si no hay usuarios, crear uno básico
            $user = User::factory()->create([
                'name' => 'Admin User',
                'email' => 'admin@example.com',
            ]);
            $users = collect([$user]);
        }

        // Obligaciones activas
        Obligation::factory()
            ->count(5)
            ->active()
            ->create([
                'created_by' => $users->random()->id,
                'reviewed_by' => null,
            ]);

        // Obligaciones que necesitan atención
        Obligation::factory()
            ->count(3)
            ->needsAttention()
            ->create([
                'created_by' => $users->random()->id,
                'reviewed_by' => $users->random()->id,
            ]);

        // Obligaciones vencidas
        Obligation::factory()
            ->count(2)
            ->expired()
            ->create([
                'created_by' => $users->random()->id,
                'reviewed_by' => null,
            ]);

        // Obligaciones específicas de ejemplo
        Obligation::create([
            'obligation_description' => 'Pago de servicios públicos - Agua',
            'quantity' => 150000,
            'period' => 'MENSUAL',
            'alert_time' => 5,
            'created_by' => $users->first()->id,
            'observations' => 'Pago mensual del servicio de agua potable',
            'status' => 10,
        ]);

        Obligation::create([
            'obligation_description' => 'Renovación de licencia de funcionamiento',
            'quantity' => 500000,
            'period' => 'ANUAL',
            'alert_time' => 30,
            'created_by' => $users->first()->id,
            'expiration_date' => now()->addMonths(6),
            'observations' => 'Renovación anual de la licencia municipal',
            'status' => 10,
        ]);

        Obligation::create([
            'obligation_description' => 'Impuesto predial',
            'quantity' => 800000,
            'period' => 'ANUAL',
            'alert_time' => 45,
            'created_by' => $users->first()->id,
            'expiration_date' => now()->addDays(10),
            'observations' => 'Pago del impuesto predial anual',
            'status' => 12,
        ]);
    }
}
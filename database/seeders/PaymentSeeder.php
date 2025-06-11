<?php

namespace Database\Seeders;

use App\Models\Payment;
use App\Models\Obligation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class PaymentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener algunas obligaciones existentes
        $obligations = Obligation::take(5)->get();
        
        if ($obligations->isEmpty()) {
            // Si no hay obligaciones, crear algunas primero
            $this->command->info('No hay obligaciones existentes. Creando algunas obligaciones primero...');
            return;
        }

        // Obtener algunos usuarios existentes
        $users = User::take(3)->get();
        
        if ($users->isEmpty()) {
            $this->command->info('No hay usuarios existentes. Usando usuario por defecto con ID 1.');
            $defaultUserId = 1;
        } else {
            $defaultUserId = $users->first()->id;
        }

        foreach ($obligations as $obligation) {
            // Crear entre 1 y 4 pagos por obligación
            $paymentCount = rand(1, 4);
            
            for ($i = 0; $i < $paymentCount; $i++) {
                $dateIni = Carbon::now()->subMonths(rand(1, 12));
                $dateEnd = $dateIni->copy()->addDays(rand(30, 90));
                
                Payment::create([
                    'obligation_id' => $obligation->obligation_id,
                    'date_ini' => $dateIni,
                    'date_end' => rand(0, 1) ? $dateEnd : null, // 50% probabilidad de tener fecha fin
                    'paid' => rand(100, 2000),
                    'observations' => $this->getRandomObservation(),
                    'created_by' => $users->isNotEmpty() ? $users->random()->id : $defaultUserId,
                    'creation_date' => Carbon::now()->subDays(rand(1, 365)),
                    'status' => rand(1, 10) > 8 ? 1 : 2, // 20% eliminados, 80% activos
                ]);
            }
        }

        $this->command->info('Pagos de obligaciones creados exitosamente.');
    }

    /**
     * Get random observation text
     */
    private function getRandomObservation(): ?string
    {
        $observations = [
            'Pago realizado en tiempo y forma',
            'Pago con retraso justificado',
            'Pago parcial - pendiente saldo',
            'Pago completo de la obligación',
            'Pago mediante transferencia bancaria',
            'Pago en efectivo',
            null, // Sin observaciones
        ];

        return $observations[array_rand($observations)];
    }
}
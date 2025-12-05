<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Customer;
use Illuminate\Support\Facades\DB;

class DefaultCustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Obtener todos los usuarios
        $users = User::all();

        foreach ($users as $user) {
            // Verificar si ya tiene un cliente "Público General"
            $existingCustomer = Customer::where('user_id', $user->id)
                ->where('name', 'Público General')
                ->first();

            if (!$existingCustomer) {
                // Crear cliente por defecto
                Customer::create([
                    'name' => 'Público General',
                    'email' => 'publico.general.' . $user->id . '@default.com',
                    'phone_number' => 'N/A',
                    'user_id' => $user->id,
                ]);

                $this->command->info("Cliente 'Público General' creado para usuario: {$user->name} (ID: {$user->id})");
            } else {
                $this->command->info("Usuario {$user->name} (ID: {$user->id}) ya tiene cliente 'Público General'");
            }
        }

        $this->command->info('Proceso completado!');
    }
}

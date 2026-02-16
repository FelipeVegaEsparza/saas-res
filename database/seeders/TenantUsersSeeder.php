<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenantUsersSeeder extends Seeder
{
    public function run(): void
    {
        // Configurar conexión temporal al tenant demo
        $dbName = 'tenant_demo';

        config([
            'database.connections.tenant_temp' => [
                'driver' => 'mysql',
                'host' => env('DB_HOST', '127.0.0.1'),
                'port' => env('DB_PORT', '3306'),
                'database' => $dbName,
                'username' => env('DB_USERNAME', 'sail'),
                'password' => env('DB_PASSWORD', 'password'),
                'charset' => 'utf8mb4',
                'collation' => 'utf8mb4_unicode_ci',
                'prefix' => '',
                'strict' => true,
                'engine' => null,
            ]
        ]);

        // Verificar si ya existe el usuario admin
        $existingUser = DB::connection('tenant_temp')
            ->table('users')
            ->where('email', 'admin@demo.cl')
            ->first();

        if (!$existingUser) {
            DB::connection('tenant_temp')->table('users')->insert([
                'name' => 'Admin Demo',
                'email' => 'admin@demo.cl',
                'password' => Hash::make('password'),
                'role' => 'owner',
                'active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $this->command->info('✓ Usuario admin creado para tenant demo');
        } else {
            $this->command->info('Usuario admin ya existe en tenant demo');
        }

        // Agregar usuarios adicionales de ejemplo
        $users = [
            [
                'name' => 'Carlos Gerente',
                'email' => 'gerente@demo.cl',
                'password' => Hash::make('password'),
                'role' => 'manager',
                'active' => true,
            ],
            [
                'name' => 'María Mesera',
                'email' => 'mesera@demo.cl',
                'password' => Hash::make('password'),
                'role' => 'waiter',
                'active' => true,
            ],
            [
                'name' => 'Juan Personal',
                'email' => 'personal@demo.cl',
                'password' => Hash::make('password'),
                'role' => 'staff',
                'active' => true,
            ],
        ];

        foreach ($users as $userData) {
            $existing = DB::connection('tenant_temp')
                ->table('users')
                ->where('email', $userData['email'])
                ->first();

            if (!$existing) {
                DB::connection('tenant_temp')->table('users')->insert(array_merge($userData, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ]));
                $this->command->info("✓ Usuario {$userData['name']} creado");
            }
        }
    }
}

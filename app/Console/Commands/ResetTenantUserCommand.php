<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Models\Tenant;

class ResetTenantUserCommand extends Command
{
    protected $signature = 'tenant:reset-user {tenant} {email} {password}';
    protected $description = 'Resetear o crear usuario en tenant';

    public function handle()
    {
        $tenantId = $this->argument('tenant');
        $email = $this->argument('email');
        $password = $this->argument('password');

        $tenant = Tenant::find($tenantId);

        if (!$tenant) {
            $this->error("Tenant '{$tenantId}' no encontrado");
            return 1;
        }

        $dbName = 'tenant_' . $tenantId;

        // Configurar conexión
        config(['database.connections.tenant.database' => $dbName]);
        DB::purge('tenant');

        try {
            // Verificar si el usuario existe
            $user = DB::connection('tenant')->table('users')->where('email', $email)->first();

            if ($user) {
                // Actualizar password
                DB::connection('tenant')->table('users')
                    ->where('email', $email)
                    ->update([
                        'password' => Hash::make($password),
                        'role' => 'owner',
                        'active' => true,
                        'updated_at' => now(),
                    ]);
                $this->info("✓ Usuario actualizado: {$email}");
            } else {
                // Crear nuevo usuario
                DB::connection('tenant')->table('users')->insert([
                    'name' => 'Administrador',
                    'email' => $email,
                    'password' => Hash::make($password),
                    'role' => 'owner',
                    'active' => true,
                    'email_verified_at' => now(),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                $this->info("✓ Usuario creado: {$email}");
            }

            $this->info("Password: {$password}");
            return 0;

        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
            return 1;
        }
    }
}

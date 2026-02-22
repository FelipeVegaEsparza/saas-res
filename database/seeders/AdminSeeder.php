<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    public function run(): void
    {
        // Verificar si ya existe un admin
        if (Admin::where('email', 'admin@admin.com')->exists()) {
            $this->command->info('✓ Usuario admin ya existe');
            return;
        }

        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@admin.com',
            'password' => Hash::make('admin123'),
            'active' => true,
        ]);

        $this->command->info('✓ Usuario admin creado exitosamente (admin@admin.com / admin123)');
    }
}

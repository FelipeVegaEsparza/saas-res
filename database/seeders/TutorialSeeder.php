<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TutorialCategory;
use App\Models\Tutorial;

class TutorialSeeder extends Seeder
{
    public function run(): void
    {
        // Crear categorías de ejemplo
        $categoriaInicio = TutorialCategory::create([
            'name' => 'Primeros Pasos',
            'slug' => 'primeros-pasos',
            'description' => 'Aprende lo básico para comenzar a usar el sistema',
            'order' => 1,
            'is_active' => true,
        ]);

        $categoriaGestion = TutorialCategory::create([
            'name' => 'Gestión de Restaurante',
            'slug' => 'gestion-restaurante',
            'description' => 'Administra tu restaurante de forma eficiente',
            'order' => 2,
            'is_active' => true,
        ]);

        $categoriaAvanzado = TutorialCategory::create([
            'name' => 'Funciones Avanzadas',
            'slug' => 'funciones-avanzadas',
            'description' => 'Saca el máximo provecho del sistema',
            'order' => 3,
            'is_active' => true,
        ]);

        // Crear tutoriales de ejemplo (puedes reemplazar estos URLs con tus propios videos)
        Tutorial::create([
            'tutorial_category_id' => $categoriaInicio->id,
            'title' => 'Introducción al Sistema',
            'description' => 'Conoce la interfaz y las funcionalidades principales del sistema',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order' => 1,
            'is_active' => true,
        ]);

        Tutorial::create([
            'tutorial_category_id' => $categoriaInicio->id,
            'title' => 'Configuración Inicial',
            'description' => 'Aprende a configurar tu restaurante por primera vez',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order' => 2,
            'is_active' => true,
        ]);

        Tutorial::create([
            'tutorial_category_id' => $categoriaGestion->id,
            'title' => 'Gestión de Mesas',
            'description' => 'Cómo crear y administrar las mesas de tu restaurante',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order' => 1,
            'is_active' => true,
        ]);

        Tutorial::create([
            'tutorial_category_id' => $categoriaGestion->id,
            'title' => 'Gestión de Productos',
            'description' => 'Aprende a agregar y organizar tu menú',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order' => 2,
            'is_active' => true,
        ]);

        Tutorial::create([
            'tutorial_category_id' => $categoriaAvanzado->id,
            'title' => 'Reportes y Estadísticas',
            'description' => 'Analiza el rendimiento de tu restaurante',
            'youtube_url' => 'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
            'order' => 1,
            'is_active' => true,
        ]);

        $this->command->info('✓ Tutoriales de ejemplo creados exitosamente');
    }
}

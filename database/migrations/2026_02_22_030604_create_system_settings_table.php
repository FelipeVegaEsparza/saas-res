<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, textarea, boolean, json
            $table->string('group')->default('general'); // general, email, appearance
            $table->timestamps();
        });

        // Insertar configuraciones por defecto
        DB::table('system_settings')->insert([
            [
                'key' => 'email_welcome_subject',
                'value' => '¡Bienvenido! Tu cuenta ha sido activada',
                'type' => 'text',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_welcome_message',
                'value' => 'Tu restaurante ha sido configurado exitosamente en nuestro sistema. Tu cuenta está lista para usar.',
                'type' => 'textarea',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'email_footer_text',
                'value' => 'Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos. Estamos aquí para ayudarte a tener éxito.',
                'type' => 'textarea',
                'group' => 'email',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'company_name',
                'value' => 'Sistema de Gestión de Restaurantes',
                'type' => 'text',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'support_email',
                'value' => 'soporte@tusistema.com',
                'type' => 'text',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};

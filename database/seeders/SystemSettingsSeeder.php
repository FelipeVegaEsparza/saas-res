<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SystemSettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Configuración de Emails
            [
                'key' => 'email_welcome_subject',
                'value' => '¡Bienvenido! Tu cuenta ha sido activada',
                'type' => 'text',
                'group' => 'email',
            ],
            [
                'key' => 'email_welcome_message',
                'value' => 'Tu restaurante ha sido configurado exitosamente en nuestro sistema. Tu cuenta está lista para usar.',
                'type' => 'textarea',
                'group' => 'email',
            ],
            [
                'key' => 'email_footer_text',
                'value' => 'Si tienes alguna pregunta o necesitas ayuda, no dudes en contactarnos. Estamos aquí para ayudarte a tener éxito.',
                'type' => 'textarea',
                'group' => 'email',
            ],

            // Configuración Básica de la Empresa
            [
                'key' => 'company_name',
                'value' => 'RestaurantSaaS',
                'type' => 'text',
                'group' => 'company',
            ],
            [
                'key' => 'company_logo',
                'value' => '',
                'type' => 'file',
                'group' => 'company',
            ],
            [
                'key' => 'company_favicon',
                'value' => '',
                'type' => 'file',
                'group' => 'company',
            ],
            [
                'key' => 'company_phone',
                'value' => '+56 9 1234 5678',
                'type' => 'text',
                'group' => 'company',
            ],
            [
                'key' => 'company_email',
                'value' => 'info@restaurantsaas.com',
                'type' => 'text',
                'group' => 'company',
            ],
            [
                'key' => 'company_address',
                'value' => '',
                'type' => 'textarea',
                'group' => 'company',
            ],
            [
                'key' => 'company_description',
                'value' => 'Sistema completo de gestión para restaurantes. Simplifica tu operación y aumenta tus ventas.',
                'type' => 'textarea',
                'group' => 'company',
            ],

            // Redes Sociales
            [
                'key' => 'social_facebook',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_instagram',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_twitter',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_linkedin',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
            ],
            [
                'key' => 'social_youtube',
                'value' => '',
                'type' => 'text',
                'group' => 'social',
            ],

            // Configuración General (legacy)
            [
                'key' => 'support_email',
                'value' => 'soporte@restaurantsaas.com',
                'type' => 'text',
                'group' => 'general',
            ],
        ];

        foreach ($settings as $setting) {
            DB::table('system_settings')->updateOrInsert(
                ['key' => $setting['key']],
                array_merge($setting, [
                    'created_at' => now(),
                    'updated_at' => now(),
                ])
            );
        }

        $this->command->info('✓ Configuraciones del sistema creadas exitosamente');
    }
}

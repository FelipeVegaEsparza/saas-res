<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('rut')->nullable()->after('name');
            $table->string('logo_horizontal')->nullable()->after('rut');
            $table->string('logo_square')->nullable()->after('logo_horizontal');
            $table->string('phone')->nullable()->after('logo_square');
            $table->string('address')->nullable()->after('phone');
            $table->text('description')->nullable()->after('address');

            // Redes sociales
            $table->string('facebook')->nullable()->after('description');
            $table->string('instagram')->nullable()->after('facebook');
            $table->string('tiktok')->nullable()->after('instagram');
            $table->string('twitter')->nullable()->after('tiktok');

            // Configuración de carta QR
            $table->string('menu_background_image')->nullable()->after('twitter');
            $table->string('menu_color_scheme')->default('classic')->after('menu_background_image');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'rut',
                'logo_horizontal',
                'logo_square',
                'phone',
                'address',
                'description',
                'facebook',
                'instagram',
                'tiktok',
                'twitter',
                'menu_background_image',
                'menu_color_scheme',
            ]);
        });
    }
};

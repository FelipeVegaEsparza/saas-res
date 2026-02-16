<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
            $table->string('tenant_id')->unique(); // UUID del tenant
            $table->string('name'); // Nombre del restaurante
            $table->string('slug')->unique(); // restaurante1, restaurante2
            $table->string('domain')->unique(); // restaurante1.tusistema.com
            $table->string('db_name')->unique(); // tenant_uuid
            $table->string('db_username')->nullable(); // Usuario DB específico (opcional)
            $table->string('db_password')->nullable(); // Password DB específico (opcional)
            $table->boolean('active')->default(true);
            $table->string('plan')->default('free'); // free, basic, premium
            $table->timestamp('trial_ends_at')->nullable();
            $table->timestamp('subscribed_at')->nullable();
            $table->json('settings')->nullable(); // Configuraciones adicionales
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};

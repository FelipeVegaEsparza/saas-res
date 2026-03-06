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
        Schema::create('preparation_areas', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 7)->default('#667eea'); // Color hex
            $table->string('icon')->default('ri-restaurant-line'); // Clase de icono Remixicon
            $table->integer('order')->default(0);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('preparation_areas');
    }
};

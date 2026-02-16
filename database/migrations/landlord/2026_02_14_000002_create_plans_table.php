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
        Schema::create('plans', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Free, Basic, Premium
            $table->string('slug')->unique(); // free, basic, premium
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2)->default(0);
            $table->string('billing_cycle')->default('monthly'); // monthly, yearly
            $table->integer('max_products')->default(50);
            $table->integer('max_tables')->default(10);
            $table->integer('max_users')->default(3);
            $table->boolean('custom_domain')->default(false);
            $table->boolean('analytics')->default(false);
            $table->boolean('active')->default(true);
            $table->json('features')->nullable(); // Características adicionales
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plans');
    }
};

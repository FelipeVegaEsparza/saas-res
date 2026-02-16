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
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('number')->unique(); // Mesa 1, Mesa 2, Barra, etc.
            $table->integer('capacity')->default(4);
            $table->string('qr_code')->unique(); // Código QR único
            $table->string('status')->default('available'); // available, occupied, reserved
            $table->string('location')->nullable(); // Terraza, Interior, VIP
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables');
    }
};

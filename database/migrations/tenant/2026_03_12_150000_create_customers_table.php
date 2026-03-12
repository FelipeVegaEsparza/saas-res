<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('document_type')->nullable(); // RUT, DNI, etc.
            $table->string('document_number')->nullable();
            $table->decimal('credit_limit', 10, 2)->default(0); // Cupo de crédito
            $table->decimal('credit_used', 10, 2)->default(0); // Crédito usado
            $table->boolean('active')->default(true);
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index(['name', 'phone']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
};

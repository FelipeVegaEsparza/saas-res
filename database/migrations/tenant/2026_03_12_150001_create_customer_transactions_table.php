<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->string('type'); // 'credit_use', 'payment', 'adjustment'
            $table->decimal('amount', 10, 2);
            $table->string('description');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('delivery_order_id')->nullable()->constrained()->onDelete('set null');
            $table->json('metadata')->nullable(); // Para datos adicionales
            $table->timestamps();

            $table->index(['customer_id', 'type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_transactions');
    }
};

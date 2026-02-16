<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('delivery_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number')->unique();
            $table->enum('type', ['delivery', 'takeaway'])->default('delivery'); // delivery o para llevar
            $table->enum('status', ['pending', 'confirmed', 'preparing', 'ready', 'on_delivery', 'delivered', 'cancelled'])->default('pending');

            // Información del cliente
            $table->string('customer_name');
            $table->string('customer_phone');
            $table->string('customer_email')->nullable();

            // Información de entrega (solo para delivery)
            $table->text('delivery_address')->nullable();
            $table->string('delivery_zone')->nullable();
            $table->decimal('delivery_fee', 10, 2)->default(0);

            // Información del pedido
            $table->decimal('subtotal', 10, 2);
            $table->decimal('total', 10, 2);
            $table->text('notes')->nullable();

            // Tiempos
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('ready_at')->nullable();
            $table->timestamp('delivered_at')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('delivery_orders');
    }
};

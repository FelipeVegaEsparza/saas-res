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
        Schema::table('orders', function (Blueprint $table) {
            // Cambiar el status para incluir más estados
            $table->string('status')->default('pending')->change();
            // pending: pedido tomado, esperando preparación
            // preparing: en preparación en cocina
            // ready: listo para servir
            // served: servido al cliente
            // closed: cuenta cerrada, esperando pago
            // paid: pagado y completado
            // cancelled: cancelado

            // Timestamps de estados
            $table->timestamp('preparing_at')->nullable()->after('status');
            $table->timestamp('ready_at')->nullable()->after('preparing_at');
            $table->timestamp('served_at')->nullable()->after('ready_at');
            $table->timestamp('closed_at')->nullable()->after('served_at');
            $table->timestamp('paid_at')->nullable()->after('closed_at');

            // Información adicional
            $table->foreignId('waiter_id')->nullable()->after('table_id')->constrained('users')->onDelete('set null');
            $table->text('kitchen_notes')->nullable()->after('notes');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['waiter_id']);
            $table->dropColumn([
                'preparing_at',
                'ready_at',
                'served_at',
                'closed_at',
                'paid_at',
                'waiter_id',
                'kitchen_notes'
            ]);
        });
    }
};

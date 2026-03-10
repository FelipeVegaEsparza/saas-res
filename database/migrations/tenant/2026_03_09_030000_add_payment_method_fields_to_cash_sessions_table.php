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
        Schema::table('cash_sessions', function (Blueprint $table) {
            // Montos esperados por método de pago
            $table->decimal('expected_cash', 10, 2)->default(0)->after('expected_balance');
            $table->decimal('expected_card', 10, 2)->default(0)->after('expected_cash');
            $table->decimal('expected_transfer', 10, 2)->default(0)->after('expected_card');

            // Montos contados por método de pago
            $table->decimal('counted_cash', 10, 2)->nullable()->after('expected_transfer');
            $table->decimal('counted_card', 10, 2)->nullable()->after('counted_cash');
            $table->decimal('counted_transfer', 10, 2)->nullable()->after('counted_card');

            // Diferencias por método de pago
            $table->decimal('difference_cash', 10, 2)->nullable()->after('counted_transfer');
            $table->decimal('difference_card', 10, 2)->nullable()->after('difference_cash');
            $table->decimal('difference_transfer', 10, 2)->nullable()->after('difference_card');

            // Propinas por método de pago
            $table->decimal('tips_cash', 10, 2)->default(0)->after('difference_transfer');
            $table->decimal('tips_card', 10, 2)->default(0)->after('tips_cash');
            $table->decimal('tips_transfer', 10, 2)->default(0)->after('tips_card');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cash_sessions', function (Blueprint $table) {
            $table->dropColumn([
                'expected_cash', 'expected_card', 'expected_transfer',
                'counted_cash', 'counted_card', 'counted_transfer',
                'difference_cash', 'difference_card', 'difference_transfer',
                'tips_cash', 'tips_card', 'tips_transfer'
            ]);
        });
    }
};

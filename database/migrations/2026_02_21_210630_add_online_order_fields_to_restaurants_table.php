<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->boolean('accepts_online_orders')->default(false)->after('menu_color_scheme');
            $table->decimal('delivery_fee', 10, 2)->default(0)->after('accepts_online_orders');
            $table->decimal('min_order_amount', 10, 2)->default(0)->after('delivery_fee');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn([
                'accepts_online_orders',
                'delivery_fee',
                'min_order_amount',
            ]);
        });
    }
};

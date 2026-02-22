<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->string('country')->default('CL')->after('min_order_amount');
            $table->string('currency')->default('CLP')->after('country');
            $table->string('currency_symbol')->default('$')->after('currency');
        });
    }

    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn(['country', 'currency', 'currency_symbol']);
        });
    }
};

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
        // Solo agregar la columna si no existe
        if (!Schema::hasColumn('payments', 'tip')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->decimal('tip', 10, 2)->default(0)->after('amount_paid');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('payments', 'tip')) {
            Schema::table('payments', function (Blueprint $table) {
                $table->dropColumn('tip');
            });
        }
    }
};

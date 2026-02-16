<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Increase precision to 15,2 (max ~99 Trillion, comfortably handling 10,000 Crore)

        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('amount', 15, 2)->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->decimal('budget', 15, 2)->nullable()->change();
        });

        Schema::table('change_requests', function (Blueprint $table) {
            $table->decimal('cost_impact', 15, 2)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Note: Reverting might lose data if current values exceed the old limits
        Schema::table('payments', function (Blueprint $table) {
            $table->decimal('amount', 10, 2)->change();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->decimal('amount', 12, 2)->change();
        });

        Schema::table('leads', function (Blueprint $table) {
            $table->decimal('budget', 12, 2)->nullable()->change();
        });

        Schema::table('change_requests', function (Blueprint $table) {
            $table->decimal('cost_impact', 12, 2)->change();
        });
    }
};
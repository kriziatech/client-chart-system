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
        Schema::table('material_payments', function (Blueprint $table) {
            $table->string('paid_to')->nullable()->after('supplier_name');
            $table->string('reference_number')->nullable()->after('payment_mode');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('material_payments', function (Blueprint $table) {
            $table->dropColumn(['paid_to', 'reference_number']);
        });
    }
};
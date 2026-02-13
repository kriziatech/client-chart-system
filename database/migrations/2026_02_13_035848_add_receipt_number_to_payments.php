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
        Schema::table('payments', function (Blueprint $table) {
            $table->string('receipt_number')->nullable()->unique()->after('id');
            $table->string('payment_method')->nullable()->after('amount'); // Cash, UPI, Bank Transfer, Cheque
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['receipt_number', 'payment_method']);
        });
    }
};
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
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('vendor_id')->nullable()->constrained();
            $table->string('payment_mode')->nullable(); // Cash, Bank, UPI, Credit
            $table->foreignId('paid_through')->nullable()->constrained('users'); // Employee who paid
            $table->string('paid_to')->nullable(); // Specific person at vendor
            $table->string('attachment')->nullable(); // For receipts
            $table->text('comments')->nullable();
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_mode')->nullable();
            $table->string('recipient')->nullable(); // Who received the payment
            $table->string('reference_number')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['vendor_id']);
            $table->dropForeign(['paid_through']);
            $table->dropColumn(['vendor_id', 'payment_mode', 'paid_through', 'paid_to', 'attachment', 'comments']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_mode', 'recipient', 'reference_number']);
        });
    }
};
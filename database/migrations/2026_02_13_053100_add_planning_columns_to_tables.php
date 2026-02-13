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
        Schema::table('quotations', function (Blueprint $table) {
            $table->dateTime('signed_at')->nullable();
            $table->longText('signature_data')->nullable(); // Store SVG or Base64 of signature
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->date('start_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropColumn(['signed_at', 'signature_data']);
        });

        Schema::table('tasks', function (Blueprint $table) {
            $table->dropColumn('start_date');
        });
    }
};
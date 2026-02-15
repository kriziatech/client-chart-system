<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            // Drop the single unique constraint
            $table->dropUnique(['quotation_number']);

            // Add a composite unique index for number + version
            $table->unique(['quotation_number', 'version']);
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            $table->dropUnique(['quotation_number', 'version']);
            $table->unique('quotation_number');
        });
    }
};
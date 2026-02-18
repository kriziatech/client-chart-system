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
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->softDeletes();
            $table->text('deletion_remark')->nullable();
        });

        Schema::table('material_inwards', function (Blueprint $table) {
            $table->softDeletes();
            $table->text('deletion_remark')->nullable();
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->softDeletes();
            $table->text('deletion_remark')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vendor_payments', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deletion_remark');
        });

        Schema::table('material_inwards', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deletion_remark');
        });

        Schema::table('expenses', function (Blueprint $table) {
            $table->dropSoftDeletes();
            $table->dropColumn('deletion_remark');
        });
    }
};
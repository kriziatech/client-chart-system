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
        $tables = [
            'users',
            'clients',
            'leads',
            'quotations',
            'expenses',
            'vendors',
            'inventory_items',
            'roles',
            'daily_reports',
            'tasks',
            'chat_messages',
            // Financial Tables
            'vendor_payments',
            'material_inwards',
            'material_payments',
            'project_financials',
            // Lead Pitch Tables (Optional but good to have)
            'pitch_leads'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    // Check if column exists first to avoid errors if re-running partially
                    if (!Schema::hasColumn($tableName, 'company_id')) {
                        $table->foreignId('company_id')->nullable()->after('id')->constrained('companies')->cascadeOnDelete();
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'users', 'clients', 'leads', 'quotations', 'expenses',
            'vendors', 'inventory_items', 'roles', 'daily_reports',
            'tasks', 'chat_messages', 'vendor_payments', 'material_inwards',
            'material_payments', 'project_financials', 'pitch_leads'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    if (Schema::hasColumn($tableName, 'company_id')) {
                        $table->dropForeign(['company_id']);
                        $table->dropColumn('company_id');
                    }
                });
            }
        }
    }
};
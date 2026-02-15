<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (!Schema::hasColumn('quotations', 'version')) {
                $table->unsignedInteger('version')->default(1)->after('status');
            }
            if (!Schema::hasColumn('quotations', 'parent_id')) {
                $table->foreignId('parent_id')->nullable()->after('version')->constrained('quotations')->onDelete('cascade');
            }
            if (!Schema::hasColumn('quotations', 'discount_amount')) {
                $table->decimal('discount_amount', 15, 2)->default(0)->after('tax_amount');
            }
            if (!Schema::hasColumn('quotations', 'gst_percentage')) {
                $table->decimal('gst_percentage', 5, 2)->default(18)->after('discount_amount');
            }
            if (!Schema::hasColumn('quotations', 'signature_path')) {
                $table->string('signature_path')->nullable()->after('notes');
            }
            if (!Schema::hasColumn('quotations', 'signed_at')) {
                $table->timestamp('signed_at')->nullable()->after('signature_path');
            }
            if (!Schema::hasColumn('quotations', 'signature_data')) {
                $table->longText('signature_data')->nullable()->after('signed_at');
            }
        });

        Schema::table('quotation_items', function (Blueprint $table) {
            if (!Schema::hasColumn('quotation_items', 'category')) {
                $table->string('category')->nullable()->after('type');
            }
        });
    }

    public function down(): void
    {
        Schema::table('quotations', function (Blueprint $table) {
            if (Schema::hasColumn('quotations', 'parent_id')) {
                $table->dropForeign(['parent_id']);
                $table->dropColumn('parent_id');
            }
            $columns = ['version', 'discount_amount', 'gst_percentage', 'signature_path', 'signed_at', 'signature_data'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('quotations', $column)) {
                    $table->dropColumn($column);
                }
            }
        });

        Schema::table('quotation_items', function (Blueprint $table) {
            if (Schema::hasColumn('quotation_items', 'category')) {
                $table->dropColumn('category');
            }
        });
    }
};
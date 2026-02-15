<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            if (!Schema::hasColumn('leads', 'location')) {
                $table->string('location')->nullable()->after('address');
            }
            if (!Schema::hasColumn('leads', 'budget')) {
                $table->decimal('budget', 12, 2)->nullable()->after('location');
            }
            if (!Schema::hasColumn('leads', 'whatsapp')) {
                $table->string('whatsapp')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('leads', 'notes')) {
                $table->text('notes')->nullable()->after('metadata');
            }
        });
    }

    public function down(): void
    {
        Schema::table('leads', function (Blueprint $table) {
            $table->dropColumn(['location', 'budget', 'whatsapp', 'notes']);
        });
    }
};
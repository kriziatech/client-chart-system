<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('scope_of_works')) {
            Schema::create('scope_of_works', function (Blueprint $table) {
                $table->id();
                $table->foreignId('client_id')->constrained()->onDelete('cascade');
                $table->string('version_name')->default('Final Proposal'); // e.g., 'Draft 1', 'Final'
                $table->text('exclusions')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('scope_items')) {
            Schema::create('scope_items', function (Blueprint $table) {
                $table->id();
                $table->foreignId('scope_of_work_id')->constrained('scope_of_works')->onDelete('cascade');
                $table->string('area_name'); // e.g., 'Master Bedroom', 'Kitchen'
                $table->text('description'); // e.g., 'False ceiling, electrical wiring'
                $table->text('specifications')->nullable(); // e.g., 'Saint Gobain Gypsum, Havells Wires'
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('scope_items');
        Schema::dropIfExists('scope_of_works');
    }
};
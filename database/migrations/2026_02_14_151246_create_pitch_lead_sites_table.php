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
        if (!Schema::hasTable('pitch_lead_sites')) {
            Schema::create('pitch_lead_sites', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('pitch_leads')->onDelete('cascade');
                $table->text('address');
                $table->string('plot_size')->nullable();
                $table->string('location_coordinates')->nullable();
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pitch_lead_sites');
    }
};
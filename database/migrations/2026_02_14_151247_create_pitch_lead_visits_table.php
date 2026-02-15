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
        if (!Schema::hasTable('pitch_lead_visits')) {
            Schema::create('pitch_lead_visits', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('pitch_leads')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
                $table->timestamp('visited_at');
                $table->string('purpose');
                $table->text('outcome')->nullable();
                $table->text('observations')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pitch_lead_visits');
    }
};
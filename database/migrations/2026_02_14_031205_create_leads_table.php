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
        Schema::create('leads', function (Blueprint $table) {
            $table->id();
            $table->uuid('offline_uuid')->unique()->nullable(); // For offline syncing
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('New'); // New, Contacted, Qualified, Lost, Converted
            $table->string('source')->nullable(); // Website, Referral, Cold Call
            $table->string('address')->nullable();
            $table->text('work_description')->nullable();
            $table->foreignId('assigned_to_id')->nullable()->constrained('users');
            $table->json('metadata')->nullable(); // For additional notes, tags, etc.
            $table->timestamp('last_follow_up_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('leads');
    }
};
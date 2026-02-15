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
        Schema::create('pitch_leads', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('status')->default('New');
            $table->string('source')->nullable();
            $table->text('work_description')->nullable();
            $table->foreignId('assigned_to_id')->nullable()->constrained('users');
            $table->boolean('is_converted')->default(false);
            $table->timestamp('converted_at')->nullable();
            $table->unsignedBigInteger('converted_client_id')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pitch_leads');
    }
};
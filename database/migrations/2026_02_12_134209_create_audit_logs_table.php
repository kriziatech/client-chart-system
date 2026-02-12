<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('user_name')->nullable(); // Snapshot of name
            $table->string('user_role')->nullable(); // Snapshot of role
            $table->string('action'); // Login, Create, Update, etc
            $table->string('module')->nullable(); // Project, Task, Invoice, etc
            $table->string('model_type')->nullable(); // App\Models\Client
            $table->unsignedBigInteger('model_id')->nullable();
            $table->text('description')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();

            // Status & Error
            $table->string('status')->default('success'); // success / failed
            $table->text('failure_reason')->nullable();

            // Context
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('os')->nullable();
            $table->string('device_type')->nullable(); // Desktop / Mobile

            // Meta
            $table->string('source')->default('web'); // web / api
            $table->boolean('is_system_action')->default(false);
            $table->boolean('is_immutable')->default(true);

            $table->timestamp('created_at')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
    }
};
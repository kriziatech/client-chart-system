<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('project_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->integer('rating')->default(5);
            $table->text('comment')->nullable();
            $table->boolean('is_testimonial')->default(false);
            $table->string('status')->default('pending'); // pending, approved
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('project_feedback');
    }
};
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        if (!Schema::hasTable('pitch_design_concepts')) {
            Schema::create('pitch_design_concepts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('lead_id')->constrained('pitch_leads')->onDelete('cascade');
                $table->integer('version')->default(1);
                $table->enum('status', ['Pending', 'Approved', 'Changes Required'])->default('Pending');
                $table->text('notes')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pitch_design_assets')) {
            Schema::create('pitch_design_assets', function (Blueprint $table) {
                $table->id();
                $table->foreignId('concept_id')->constrained('pitch_design_concepts')->onDelete('cascade');
                $table->enum('type', ['Moodboard', '2D Drawing', '3D Render', 'Material Selection']);
                $table->string('file_path');
                $table->string('title');
                $table->text('description')->nullable();
                $table->timestamps();
            });
        }

        if (!Schema::hasTable('pitch_design_feedback')) {
            Schema::create('pitch_design_feedback', function (Blueprint $table) {
                $table->id();
                $table->foreignId('asset_id')->constrained('pitch_design_assets')->onDelete('cascade');
                $table->foreignId('user_id')->constrained('users');
                $table->text('comment');
                $table->timestamps();
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('pitch_design_feedback');
        Schema::dropIfExists('pitch_design_assets');
        Schema::dropIfExists('pitch_design_concepts');
    }
};
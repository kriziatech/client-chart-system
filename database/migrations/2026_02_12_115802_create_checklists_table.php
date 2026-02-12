<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->boolean('civil_work')->default(false);
            $table->boolean('tiles')->default(false);
            $table->boolean('bathroom')->default(false);
            $table->boolean('doors')->default(false);
            $table->boolean('wardrobe')->default(false);
            $table->boolean('kitchen')->default(false);
            $table->boolean('paint')->default(false);
            $table->boolean('electrical')->default(false);
            $table->boolean('windows')->default(false);
            $table->boolean('upvc')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('checklists');
    }
};

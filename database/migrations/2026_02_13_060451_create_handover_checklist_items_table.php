<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('handover_checklist_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('handover_id')->constrained()->onDelete('cascade');
            $table->string('item_name');
            $table->boolean('is_completed')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handover_checklist_items');
    }
};
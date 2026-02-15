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
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->foreignId('parent_id')->nullable()->constrained('chat_messages')->onDelete('cascade');
            $table->boolean('is_pinned')->default(false);
            $table->boolean('is_decision')->default(false);
            $table->foreignId('linked_task_id')->nullable()->constrained('tasks')->onDelete('set null');
            $table->json('reactions')->nullable();
            $table->json('metadata')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('chat_messages', function (Blueprint $table) {
            $table->dropForeign(['parent_id']);
            $table->dropColumn('parent_id');
            $table->dropColumn('is_pinned');
            $table->dropColumn('is_decision');
            $table->dropForeign(['linked_task_id']);
            $table->dropColumn('linked_task_id');
            $table->dropColumn('reactions');
            $table->dropColumn('metadata');
        });
    }
};
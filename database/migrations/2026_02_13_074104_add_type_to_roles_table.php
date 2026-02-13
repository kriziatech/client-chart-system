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
        Schema::table('roles', function (Blueprint $table) {
            $table->enum('type', ['admin', 'editor', 'viewer'])->default('viewer')->after('description');
        });

        // Set Default Roles
        \App\Models\Role::where('name', 'admin')->update(['type' => 'admin']);
        \App\Models\Role::where('name', 'editor')->update(['type' => 'editor']);
        \App\Models\Role::where('name', 'viewer')->update(['type' => 'viewer']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('roles', function (Blueprint $table) {
        //
        });
    }
};
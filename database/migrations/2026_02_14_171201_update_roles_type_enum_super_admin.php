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
        DB::statement("ALTER TABLE roles MODIFY COLUMN type ENUM('super_admin', 'admin', 'editor', 'viewer', 'sales') NOT NULL");

        // Correct the existing super_admin role
        DB::table('roles')->where('id', 10)->update(['type' => 'super_admin']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("ALTER TABLE roles MODIFY COLUMN type ENUM('admin', 'editor', 'viewer', 'sales') NOT NULL");
    }
};
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
        // No schema changes needed, just inserting data
        });

        DB::table('roles')->insertOrIgnore([
            [
                'id' => 10, // Selecting a unique ID or letting it auto-increment
                'name' => 'Super Admin',
                'type' => 'super_admin',
                'description' => 'God mode - Universal access across the entire system',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 1,
                'name' => 'Admin',
                'type' => 'admin',
                'description' => 'Director level - Access to all modules',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);

        // Update existing admin role if needed
        DB::table('roles')->where('id', 1)->update(['name' => 'Admin', 'type' => 'admin', 'description' => 'Director level - Access to all modules']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('roles')->where('type', 'super_admin')->delete();
    }
};
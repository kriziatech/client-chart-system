<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->foreignId('role_id')->nullable()->after('email')->constrained('roles')->onDelete('set null');
        });

        // Seed Default Roles
        $roles = [
            ['name' => 'admin', 'description' => 'Administrator', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'editor', 'description' => 'Editor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'viewer', 'description' => 'Viewer', 'created_at' => now(), 'updated_at' => now()],
        ];

        DB::table('roles')->insert($roles);

        // Migrate existing users
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            if (isset($user->role)) { 
                $role = DB::table('roles')->where('name', $user->role)->first();
                if ($role) {
                    DB::table('users')->where('id', $user->id)->update(['role_id' => $role->id]);
                }
            }
        }

        // Drop old column
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'editor', 'viewer'])->default('viewer');
        });

        // Migrate back
        $users = DB::table('users')->get();
        foreach ($users as $user) {
            if ($user->role_id) {
                $role = DB::table('roles')->where('id', $user->role_id)->first();
                if ($role && in_array($role->name, ['admin', 'editor', 'viewer'])) {
                    DB::table('users')->where('id', $user->id)->update(['role' => $role->name]);
                }
            }
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['role_id']);
            $table->dropColumn('role_id');
        });
    }
};
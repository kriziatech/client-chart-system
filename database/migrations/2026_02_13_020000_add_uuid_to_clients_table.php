<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->after('id');
        });

        // Populate existing clients
        $clients = \App\Models\Client::all();
        foreach ($clients as $client) {
            $client->uuid = (string)Str::uuid();
            $client->save();
        }

        // Make unique after population
        Schema::table('clients', function (Blueprint $table) {
            $table->uuid('uuid')->unique()->change();
        });
    }

    public function down(): void
    {
        Schema::table('clients', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
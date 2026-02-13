<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('handovers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->date('handover_date')->nullable();
            $table->integer('warranty_years')->default(1);
            $table->date('warranty_expiry')->nullable();
            $table->longText('client_signature')->nullable();
            $table->string('status')->default('pending'); // pending, completed
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('handovers');
    }
};
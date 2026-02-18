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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->onDelete('cascade');
            $table->foreignId('vendor_id')->nullable()->constrained()->onDelete('set null');
            $table->string('work_order_number')->unique();
            $table->string('title');
            $table->text('description')->nullable();
            $table->decimal('total_amount', 12, 2)->default(0);
            $table->text('payment_terms')->nullable();
            $table->enum('status', ['draft', 'sent', 'accepted', 'completed', 'cancelled'])->default('draft');
            $table->date('issue_date');
            $table->date('expiry_date')->nullable();
            $table->json('items')->nullable(); // For detailed line items
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
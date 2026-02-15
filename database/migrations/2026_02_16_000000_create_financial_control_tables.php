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
        // 1. Vendor Payments
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete(); // The Project
            $table->foreignId('vendor_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 15, 2);
            $table->string('work_type'); // e.g., Carpentry, Electrical
            $table->date('payment_date');
            $table->string('payment_mode')->nullable(); // Cash, Cheque, Online
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 2. Material Inwards (Procurement)
        Schema::create('material_inwards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->string('supplier_name');
            $table->string('item_name');
            $table->string('unit')->default('pcs'); // kg, mtr, etc.
            $table->decimal('quantity', 15, 2);
            $table->decimal('rate', 15, 2);
            $table->decimal('total_amount', 15, 2); // Auto-calc or manual override
            $table->string('bill_number')->nullable();
            $table->string('bill_image_path')->nullable();
            $table->date('inward_date');
            $table->timestamps();
        });

        // 3. Material Payments (Linked to Inwards)
        Schema::create('material_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete(); // Direct project link for queries
            $table->foreignId('material_inward_id')->nullable()->constrained()->onDelete('set null'); // Optional link to specific bill
            $table->string('supplier_name')->nullable(); // If not linked to specific inward
            $table->decimal('amount_paid', 15, 2);
            $table->date('payment_date');
            $table->string('payment_mode')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        // 4. Project Financials (Summary & Lock)
        Schema::create('project_financials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->decimal('budget_locked_amount', 15, 2)->default(0); // If budget is frozen
            $table->boolean('is_locked')->default(false); // Profit Lock
            $table->decimal('expected_profit_margin', 5, 2)->default(15.00); // Percentage

            // Cached Totals for performance (optional, can be calculated on fly too)
            // But let's keep them calculated on fly for accuracy first.

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('project_financials');
        Schema::dropIfExists('material_payments');
        Schema::dropIfExists('material_inwards');
        Schema::dropIfExists('vendor_payments');
    }
};
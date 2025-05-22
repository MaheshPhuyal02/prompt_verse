<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('prompt_id')->constrained()->onDelete('cascade');
            $table->decimal('purchase_price', 10, 2); // Price at time of purchase
            $table->timestamp('purchase_date');
            $table->json('prompt_snapshot')->nullable(); // Store prompt data at time of purchase
            $table->enum('status', ['active', 'inactive', 'refunded'])->default('active');
            $table->timestamps();

            $table->unique(['user_id', 'prompt_id']); // Prevent duplicate purchases
            $table->index(['user_id', 'status']);
            $table->index(['prompt_id']);
            $table->index(['purchase_date']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases');
    }
};

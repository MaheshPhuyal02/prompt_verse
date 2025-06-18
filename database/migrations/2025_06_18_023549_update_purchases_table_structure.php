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
        // First, drop the existing enum constraint
        DB::statement('ALTER TABLE purchases DROP CONSTRAINT IF EXISTS purchases_status_check');
        
        Schema::table('purchases', function (Blueprint $table) {
            // Drop old columns
            $table->dropColumn(['purchase_price', 'purchase_date', 'prompt_snapshot']);
            
            // Add new columns
            $table->decimal('price_at_time', 10, 2)->after('prompt_id');
            $table->string('payment_id')->nullable()->after('price_at_time');
            $table->string('payment_method')->nullable()->after('payment_id');
            $table->timestamp('purchased_at')->nullable()->after('payment_method');
            $table->string('transaction_id')->nullable()->after('purchased_at');
            
            // Update status column
            $table->string('status')->default('pending')->change();
            
            // Update indexes
            // Only drop the index if it exists
        });
        // Drop the index outside the Blueprint to avoid errors if it doesn't exist
        \DB::statement('DROP INDEX IF EXISTS purchases_purchase_date_index');
        Schema::table('purchases', function (Blueprint $table) {
            $table->index('purchased_at');
            $table->index('payment_id');
            $table->index('transaction_id');
        });

        // Add the new enum constraint
        DB::statement("ALTER TABLE purchases ADD CONSTRAINT purchases_status_check CHECK (status IN ('pending', 'completed', 'failed', 'refunded'))");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // First, drop the existing enum constraint
        DB::statement('ALTER TABLE purchases DROP CONSTRAINT IF EXISTS purchases_status_check');
        
        Schema::table('purchases', function (Blueprint $table) {
            // Drop new columns
            $table->dropColumn(['price_at_time', 'payment_id', 'payment_method', 'purchased_at', 'transaction_id']);
            
            // Add back old columns
            $table->decimal('purchase_price', 10, 2)->after('prompt_id');
            $table->timestamp('purchase_date')->after('purchase_price');
            $table->json('prompt_snapshot')->nullable()->after('purchase_date');
            
            // Revert status column
            $table->string('status')->default('active')->change();
            
            // Revert indexes
        });
        // Drop the new indexes outside the Blueprint
        \DB::statement('DROP INDEX IF EXISTS purchases_purchased_at_index');
        \DB::statement('DROP INDEX IF EXISTS purchases_payment_id_index');
        \DB::statement('DROP INDEX IF EXISTS purchases_transaction_id_index');
        Schema::table('purchases', function (Blueprint $table) {
            $table->index('purchase_date');
        });

        // Add back the old enum constraint
        DB::statement("ALTER TABLE purchases ADD CONSTRAINT purchases_status_check CHECK (status IN ('active', 'inactive', 'refunded'))");
    }
};

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
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('wallet_id'); // Foreign key to wallets table
            $table->unsignedBigInteger('order_id')->nullable(); // Foreign key to wallets table
            $table->enum('transaction_type', ['credit', 'debit', 'refund']); // Type of transaction
            $table->decimal('amount', 10, 2); // Transaction amount
            $table->string('description')->nullable(); // Optional description
            $table->timestamp('transaction_date')->default(DB::raw('CURRENT_TIMESTAMP')); // Transaction timestamp

            $table->foreign('wallet_id')->references('id')->on('wallets')->onDelete('cascade'); // Foreign key constraint
            $table->foreign('order_id')->references('id')->on('orders')->onDelete('cascade'); // Foreign key constraint
            $table->timestamps(); // Laravel timestamps (created_at, updated_at)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};

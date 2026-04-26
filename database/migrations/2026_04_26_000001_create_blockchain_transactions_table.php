<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('blockchain_transactions', function (Blueprint $table) {
            $table->id();
            $table->string('tx_hash')->unique()->index(); // Blockchain transaction hash
            $table->string('contract_name'); // Origin, Escrow, Reputation
            $table->string('contract_address');
            $table->string('function_name');
            $table->json('parameters'); // Function parameters
            $table->string('status')->default('pending'); // pending, confirmed, failed
            $table->integer('block_number')->nullable();
            $table->string('gas_used')->nullable();
            $table->decimal('gas_price', 20, 10)->nullable();
            $table->string('from_address');
            $table->string('to_address');
            $table->decimal('value', 30, 18)->default(0); // ETH value sent
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('set null');
            $table->string('related_type')->nullable(); // Product, Order, User
            $table->unsignedBigInteger('related_id')->nullable();
            $table->text('error_message')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->integer('confirmations')->default(0);
            $table->timestamps();
            
            $table->index(['related_type', 'related_id']);
            $table->index(['contract_name', 'status']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('blockchain_transactions');
    }
};

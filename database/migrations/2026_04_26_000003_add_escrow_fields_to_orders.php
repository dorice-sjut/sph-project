<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            // Escrow blockchain fields
            $table->boolean('is_escrow_payment')->default(false)->after('payment_status');
            $table->string('escrow_contract_address')->nullable()->after('is_escrow_payment');
            $table->string('escrow_tx_hash')->nullable()->after('escrow_contract_address');
            $table->string('escrow_status')->default('pending')->after('escrow_tx_hash'); // pending, delivered, released, refunded, cancelled
            $table->timestamp('escrow_created_at')->nullable()->after('escrow_status');
            $table->timestamp('escrow_delivered_at')->nullable()->after('escrow_created_at');
            $table->timestamp('escrow_released_at')->nullable()->after('escrow_delivered_at');
            $table->decimal('escrow_amount_eth', 30, 18)->nullable()->after('escrow_released_at');
            $table->decimal('platform_fee_eth', 30, 18)->nullable()->after('escrow_amount_eth');
            $table->string('buyer_wallet_address')->nullable()->after('platform_fee_eth');
            $table->string('farmer_wallet_address')->nullable()->after('buyer_wallet_address');
            
            // Index for quick lookups
            $table->index('escrow_tx_hash');
            $table->index('escrow_status');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['escrow_tx_hash']);
            $table->dropIndex(['escrow_status']);
            $table->dropColumn([
                'is_escrow_payment',
                'escrow_contract_address',
                'escrow_tx_hash',
                'escrow_status',
                'escrow_created_at',
                'escrow_delivered_at',
                'escrow_released_at',
                'escrow_amount_eth',
                'platform_fee_eth',
                'buyer_wallet_address',
                'farmer_wallet_address'
            ]);
        });
    }
};

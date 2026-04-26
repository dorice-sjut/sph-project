<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            // Blockchain verification fields
            $table->boolean('is_blockchain_verified')->default(false)->after('status');
            $table->string('batch_id')->nullable()->unique()->after('is_blockchain_verified');
            $table->string('blockchain_tx_hash')->nullable()->after('batch_id');
            $table->string('verification_contract_address')->nullable()->after('blockchain_tx_hash');
            $table->timestamp('blockchain_verified_at')->nullable()->after('verification_contract_address');
            $table->string('ipfs_hash')->nullable()->after('blockchain_verified_at');
            
            // Index for quick lookups
            $table->index('batch_id');
            $table->index('blockchain_tx_hash');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['batch_id']);
            $table->dropIndex(['blockchain_tx_hash']);
            $table->dropColumn([
                'is_blockchain_verified',
                'batch_id',
                'blockchain_tx_hash',
                'verification_contract_address',
                'blockchain_verified_at',
                'ipfs_hash'
            ]);
        });
    }
};

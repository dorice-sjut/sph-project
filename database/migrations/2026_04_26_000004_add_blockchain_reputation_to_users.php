<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Blockchain reputation fields
            $table->boolean('is_blockchain_registered')->default(false)->after('preferred_language');
            $table->string('wallet_address')->nullable()->unique()->after('is_blockchain_registered');
            $table->string('reputation_contract_address')->nullable()->after('wallet_address');
            $table->integer('blockchain_trust_score')->default(500)->after('reputation_contract_address'); // 0-1000
            $table->string('trust_tier')->default('Average')->after('blockchain_trust_score'); // Poor, Average, Good, Excellent
            $table->integer('blockchain_total_transactions')->default(0)->after('trust_tier');
            $table->integer('blockchain_successful_deliveries')->default(0)->after('blockchain_total_transactions');
            $table->integer('blockchain_failed_deliveries')->default(0)->after('blockchain_successful_deliveries');
            $table->timestamp('blockchain_registered_at')->nullable()->after('blockchain_failed_deliveries');
            
            // Index for wallet lookups
            $table->index('wallet_address');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['wallet_address']);
            $table->dropColumn([
                'is_blockchain_registered',
                'wallet_address',
                'reputation_contract_address',
                'blockchain_trust_score',
                'trust_tier',
                'blockchain_total_transactions',
                'blockchain_successful_deliveries',
                'blockchain_failed_deliveries',
                'blockchain_registered_at'
            ]);
        });
    }
};

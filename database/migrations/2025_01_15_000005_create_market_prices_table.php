<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('market_prices', function (Blueprint $table) {
            $table->id();
            $table->string('product_name');
            $table->string('category');
            $table->decimal('price_local', 12, 2);
            $table->string('currency_local')->default('TZS');
            $table->decimal('price_usd', 12, 2);
            $table->string('region');
            $table->string('country');
            $table->string('market_name');
            $table->decimal('price_change_24h', 5, 2)->nullable();
            $table->enum('trend', ['up', 'down', 'stable'])->default('stable');
            $table->date('price_date');
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('market_prices');
    }
};

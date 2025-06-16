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
        Schema::create('bank_cards', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('card_holder_name', 100)->nullable();
            $table->string('card_number', 50)->nullable();
            $table->string('card_expiry_year', 5)->nullable();
            $table->string('card_expiry_month', 5)->nullable();
            $table->string('card_cvv', 5)->nullable();
            $table->boolean('default_card')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bank_cards');
    }
};

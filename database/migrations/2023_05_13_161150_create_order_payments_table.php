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
        Schema::create('order_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->string('transaction_id')->nullable();
            $table->string('status')->nullable(); // status received from payment gateway response
            $table->foreignId('payment_method_id')->nullable()->constrained('payment_methods')->nullOnDelete();
            $table->string('payment_method_name')->nullable(); // card, mobile, wallet, cash on delivery ...
            $table->foreignId('bank_card_id')->nullable()->constrained('bank_cards')->nullOnDelete();
            $table->string('card_type')->nullable(); // credit , debit
            $table->string('card_agent')->nullable(); // Visa , masterCard
            $table->string('payment_currency_code')->nullable();
            $table->decimal('amount')->nullable();
            $table->text('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_payments');
    }
};

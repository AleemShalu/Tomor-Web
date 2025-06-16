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
        Schema::create('carts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();

            $table->integer('items_count')->default(0);
            $table->integer('items_quantity')->default(0);

            $table->decimal('exchange_rate', 12, 4)->nullable();
            $table->dateTime('conversion_time')->nullable();

            $table->string('cart_currency_code')->nullable();
            $table->string('base_currency_code')->nullable();

            $table->decimal('grand_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_grand_total', 12, 4)->default(0)->nullable();

            $table->decimal('sub_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_sub_total', 12, 4)->default(0)->nullable();

            $table->decimal('service_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_service_total', 12, 4)->default(0)->nullable();

            $table->decimal('discount_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_discount_total', 12, 4)->default(0)->nullable();

            $table->decimal('tax_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_tax_total', 12, 4)->default(0)->nullable();

            $table->decimal('taxable_total', 12, 4)->default(0)->nullable();
            $table->decimal('base_taxable_total', 12, 4)->default(0)->nullable();

            $table->string('coupon_code')->nullable();

            $table->boolean('is_gift')->default(0);
            $table->boolean('is_guest')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('carts');
    }
};

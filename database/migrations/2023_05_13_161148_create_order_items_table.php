<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();

            $table->foreignId('product_id')->nullable()->constrained('products')->nullOnDelete();
            $table->string('item_code', 50)->nullable();
            $table->string('item_name')->nullable();
            $table->text('item_description')->nullable();;
            $table->decimal('item_unit_price', 12, 4)->default(0)->nullable();;

            $table->decimal('item_quantity', 12, 4, true)->default(0);

            $table->string('item_status')->nullable(); // received, accepted, processing, preparing, finished, canceled, ...

            $table->decimal('item_total', 12, 4)->default(0)->nullable();
//            $table->decimal('item_base_total', 12,4)->default(0)->nullable();
//
//            $table->decimal('item_amount', 12,4)->default(0)->nullable();
//            $table->decimal('item_base_amount', 12,4)->default(0)->nullable();
//
//            $table->decimal('item_discount_rate', 12,4)->default(0)->nullable();
//            $table->decimal('item_discount_amount', 12,4)->default(0)->nullable();
//            $table->string('item_coupon_code')->nullable();
//
//            $table->foreignId('tax_code_id')->nullable()->constrained('tax_codes')->nullOnDelete();
//            $table->decimal('item_tax_rate', 12, 4)->default(0)->nullable();
//            $table->decimal('item_tax_amount', 12, 4)->default(0)->nullable();
//            $table->decimal('item_base_tax_amount', 12, 4)->default(0)->nullable();
//
//            $table->decimal('item_taxable_amount', 12,4)->default(0)->nullable();
//            $table->decimal('item_base_taxable_amount', 12,4)->default(0)->nullable();

            $table->string('note')->nullable();
            $table->string('voice_url')->nullable();
            $table->string('voice_path')->nullable();


            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};

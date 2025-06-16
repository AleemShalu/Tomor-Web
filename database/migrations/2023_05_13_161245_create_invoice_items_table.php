<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoice_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('invoice_id')->constrained('invoices')->cascadeOnUpdate()->cascadeOnDelete();

            // $table->foreignId('item_id')->nullable()->constrained('items')->cascadeOnUpdate()->nullOnDelete();
            $table->timestamp('item_supply_date')->nullable();
            $table->string('item_type')->nullable();
            $table->string('item_sku')->nullable();
            $table->string('item_model_number')->nullable();
            $table->string('item_barcode')->nullable();
            $table->string('item_code')->nullable();
            $table->string('item_name')->nullable();
            $table->text('item_description');

            $table->string('item_unit')->nullable();
            $table->decimal('unit_price', 12, 4)->default(0);
            $table->decimal('quantity', 12, 4, true)->default(0);

            $table->decimal('subtotal', 12, 4)->default(0);
            $table->decimal('item_unit_discount', 12, 4)->nullable();
            $table->decimal('item_discount_percentage', 12, 4)->nullable();
            $table->decimal('invoice_level_discount_percentage', 12, 4)->nullable();
            $table->decimal('item_discount_amount', 12, 4)->default(0)->nullable();
            $table->decimal('taxable_subtotal', 12, 4)->default(0);

            // $table->foreignId('vat_code_id')->nullable()->constrained('vat_codes')->nullOnDelete();
            $table->string('vat_code')->nullable();
            $table->string('vat_code_description')->nullable();
            $table->decimal('vat_rate', 12, 4)->default(0)->nullable();
            $table->decimal('vat_amount', 12, 4)->default(0)->nullable();
            $table->decimal('subtotal_including_vat', 12, 4)->default(0);
            $table->string('remarks')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoice_items');
    }
};

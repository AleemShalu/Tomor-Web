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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_brand_id')->nullable()->constrained('product_brands')->nullOnDelete();
            $table->string('product_code', 50);
            $table->string('model_number', 50)->nullable();
            $table->string('barcode', 100)->nullable();
            $table->decimal('quantity', 12, 4, true)->default(0);
            $table->string('availability')->nullable()->comment('in stock, out of stock, ...'); // for customer: in stock, out of stock, on backorder, coming soon, pre order...
            $table->decimal('unit_price', 12, 4)->default(0);
            $table->string('calories')->nullable();
            $table->boolean('status')->default(0); // for owner: available, unavailable
            $table->foreignId('product_category_id')->nullable()->constrained('product_categories')->nullOnDelete();
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
           $table->unique(['product_code', 'store_id']); // fixed.
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};

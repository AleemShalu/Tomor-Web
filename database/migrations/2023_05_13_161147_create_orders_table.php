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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->integer('order_number');
            $table->string('branch_order_number');
            $table->string('store_order_number');

            $table->string('branch_queue_number');

            $table->string('status')->nullable()->comment('received, processing, finished, on-way, delivered, canceled...');

            $table->timestamp('order_date')->nullable();
            $table->string('order_color');

            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('customer_name')->nullable();
            // $table->string('customer_first_name')->nullable();
            // $table->string('customer_last_name')->nullable();
            $table->string('customer_dial_code')->nullable();
            $table->string('customer_contact_no')->nullable();
            $table->string('customer_email')->nullable();
            $table->foreignId('customer_vehicle_id')->nullable()->constrained('customer_vehicles')->nullOnDelete();
            $table->string('customer_vehicle_manufacturer')->nullable();
            $table->string('customer_vehicle_name')->nullable();
            $table->string('customer_vehicle_model_year')->nullable();
            $table->string('customer_vehicle_description')->nullable();
            $table->string('customer_vehicle_color')->nullable();
            $table->string('customer_vehicle_plate_letters')->nullable();
            $table->string('customer_vehicle_plate_number')->nullable();
            $table->boolean('customer_special_needs_qualified')->nullable()->default(0);

            $table->integer('items_count')->default(0)->comment('number of order items');
            $table->integer('items_quantity')->default(0)->comment('total quantity of all order items');

            $table->decimal('exchange_rate', 12, 4)->nullable();
            $table->dateTime('conversion_time')->nullable();

            $table->string('order_currency_code')->nullable();
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

            $table->foreignId('bank_card_id')->nullable()->constrained('bank_cards')->cascadeOnUpdate();
            $table->string('checkout_method')->nullable(); // card, mobile, wallet, cash on delivery ...
            $table->string('coupon_code')->nullable();

            $table->boolean('is_gift')->default(0);
            $table->boolean('is_guest')->nullable();

            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnUpdate();
            $table->foreignId('store_branch_id')->nullable()->constrained('store_branches')->cascadeOnUpdate();
            $table->foreignId('employee_id')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};

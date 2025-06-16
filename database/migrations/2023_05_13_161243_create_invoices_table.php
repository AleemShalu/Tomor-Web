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
        Schema::create('invoices', function (Blueprint $table) {
            // Unique primary key for each invoice
            $table->id();

            // Unique UUID for each invoice
            $table->uuid('uuid')->unique();

            // Invoice number
            $table->string('invoice_number')->nullable();

            // Invoice status (e.g., unpaid, paid, canceled, etc.)
            $table->string('status')->nullable()->comment('unpaid, paid in installments, paid in full, canceled, overdue, ....');

            // Foreign key reference to the associated order
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnUpdate()->nullOnDelete();

            // Foreign key reference to the invoice type
            $table->foreignId('invoice_type_id')->nullable()->constrained('invoice_types')->cascadeOnUpdate()->nullOnDelete();

            // Invoice language (e.g., en, ar)
            $table->string('invoice_locale', 5)->nullable();

            // Invoice issuance date
            $table->timestamp('invoice_date')->nullable();

            // Goods supply date
            $table->timestamp('supply_date')->nullable();

            // Foreign key reference to the associated business
            $table->foreignId('business_id')->nullable()->constrained('businesses')->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('business_name_ar');
            $table->string('business_name_en');
            $table->string('business_address');
            $table->string('business_vat_number')->nullable();
            $table->string('business_group_vat_number')->nullable();
            $table->string('business_cr_number')->nullable();

            // Foreign key reference to the associated store
            $table->foreignId('store_id')->nullable()->constrained('stores')->cascadeOnUpdate()->nullOnDelete();
            $table->string('store_invoice_number')->nullable();
            $table->string('store_name_ar')->nullable();
            $table->string('store_name_en')->nullable();

            // Foreign key reference to the store branch
            $table->foreignId('store_branch_id')->nullable()->constrained('store_branches')->cascadeOnUpdate()->nullOnDelete();
            $table->string('store_branch_invoice_number')->nullable();
            $table->string('store_branch_name_ar')->nullable();
            $table->string('store_branch_name_en')->nullable();
            $table->string('store_branch_address')->nullable();

            // Foreign key reference to the customer
            $table->foreignId('customer_id')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();
            $table->string('customer_name')->nullable();
            $table->string('customer_email')->nullable();
            $table->string('customer_dial_code', 10)->nullable();
            $table->string('customer_contact_no', 30)->nullable();

            // Invoice discount percentage
            $table->decimal('invoice_discount_percentage', 12, 4)->nullable();

            // Invoice discount amount (value), starts with a default value of 0
            $table->decimal('invoice_discount_amount', 12, 4)->default(0)->nullable();

            // Total discount applied to the invoice
            $table->decimal('total_discount', 12, 4)->default(0)->nullable();

            // Total taxable amount before taxes
            $table->decimal('total_taxtable_amount', 12, 4)->default(0)->nullable()->comment('Excluding VAT, only two decimal places as required by ZATCA');

            // Total value-added tax (VAT) amount (the tax)
            $table->decimal('total_vat', 12, 4)->default(0)->nullable();

            // Exchange rate (currency)
            $table->decimal('exchange_rate', 12, 4)->nullable();

            // Conversion time
            $table->dateTime('conversion_time')->nullable();

            // Total VAT amount in Saudi Riyals (as required by ZATCA, with two decimal places only)
            $table->decimal('total_vat_in_sar', 12, 4)->default(0)->nullable()->comment('as required by ZATCA, two decimal places only and show it in SAR');

            // Gross total amount, including taxes
            $table->decimal('gross_total_including_vat', 12, 4)->default(0)->nullable();

            // Issued by (optional)
            $table->foreignId('issued_by')->nullable()->constrained('users')->cascadeOnUpdate()->nullOnDelete();

            // File path for the invoice (PDF or another document)
            $table->text('path')->nullable();

            // QR Code path associated with the invoice
            $table->text('qrcode_path')->nullable();

            // Hash value for the invoice (optional)
            $table->text('invoice_hash')->nullable();

            // Additional information (optional)
            $table->text('additional')->nullable();

            // Created at and updated at timestamps
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

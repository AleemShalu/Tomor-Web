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
        Schema::create('coupons', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->foreignId('coupon_type_id')->nullable()->constrained('coupon_types')->nullOnDelete();
            $table->foreignId('discount_type_id')->nullable()->constrained('discount_types')->nullOnDelete();
            // $table->enum('discount_type', ['percentage', 'fixed', 'free_shipping']);
            $table->decimal('discount_percentage', 10, 4)->nullable();
            $table->decimal('discount_amount', 10, 4)->nullable();
            $table->string('coupon_currency_code');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->boolean('enabled')->default(true);
            $table->integer('max_uses')->nullable(); // number of max uses globally, e.g. first 100 uses
            $table->integer('max_uses_per_user')->nullable(); // number of max uses per user
            $table->integer('usage_count')->default(0);
            $table->decimal('min_amount', 10, 4)->nullable();
            $table->text('restrictions')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('coupons');
    }
};

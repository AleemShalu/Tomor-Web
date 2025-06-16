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
        Schema::create('order_ratings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->nullOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();

            $table->foreignId('order_rating_type_id')->nullable()->constrained('order_rating_types')->nullOnDelete();
            $table->text('body_massage')->nullable(); // Use text to store the message

            $table->integer('rating'); // Assuming you want to store the rating as an integer of 5 stars
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_ratings');
    }
};

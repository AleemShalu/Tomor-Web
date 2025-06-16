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
        Schema::create('customer_with_special_needs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('special_needs_type_id')->nullable()->constrained('special_needs_types')->nullOnDelete();
            $table->boolean('special_needs_qualified')->nullable()->default(0);
            $table->string('special_needs_sa_card_number', 20)->nullable();
            $table->string('special_needs_description')->nullable();
            $table->string('special_needs_attachment')->nullable();
            $table->string('special_needs_status')->nullable()->comment('approved, pending, reviewing, rejected, ...');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_with_special_needs');
    }
};

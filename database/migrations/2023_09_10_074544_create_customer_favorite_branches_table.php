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
        Schema::create('customer_favorite_branches', function (Blueprint $table) {
            $table->primary(['customer_id', 'store_branch_id']);
            $table->foreignId('customer_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('store_branch_id')->constrained('store_branches')->cascadeOnDelete();
            // $table->unique(['customer_id', 'store_branch_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customer_favorite_branches');
    }
};

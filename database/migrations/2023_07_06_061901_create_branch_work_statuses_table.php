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
        Schema::create('branch_work_statuses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('store_branch_id')->nullable()->constrained('store_branches')->cascadeOnDelete();
            $table->string('status')->nullable()->comment('active, busy, closed, inactive...');
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->foreignId('store_id')->nullable()->constrained('stores')->nullOnDelete(); // useless column, unless for easy query purposes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_work_statuses');
    }
};

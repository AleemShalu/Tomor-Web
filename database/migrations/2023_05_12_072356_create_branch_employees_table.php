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
        Schema::create('branch_employees', function (Blueprint $table) {
            // $table->id();
            $table->primary(['employee_id', 'store_branch_id']);
            $table->foreignId('employee_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('store_branch_id')->constrained('store_branches')->cascadeOnDelete();
            $table->foreignId('role_id')->constrained('roles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('branch_employees');
    }
};

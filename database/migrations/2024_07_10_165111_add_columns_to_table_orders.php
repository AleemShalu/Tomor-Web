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
        Schema::table('orders', function (Blueprint $table) {
            $table->decimal('service_total_tax_amount', 12, 4)->after('base_service_total')->default(0)->nullable();
            $table->decimal('service_total_including_tax', 12, 4)->after('service_total_tax_amount')->default(0)->nullable();
            $table->string('payment_reference_id', 50)->unique()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('service_total_tax_amount');
            $table->dropColumn('service_total_including_tax');
            $table->dropColumn('payment_reference_id');
        });
    }
};

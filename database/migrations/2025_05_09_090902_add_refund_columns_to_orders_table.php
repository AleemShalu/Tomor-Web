<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddRefundColumnsToOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->tinyInteger('refund_request')->default(0)->comment('0: No refund requested, 1: Refund requested');
            $table->enum('refund_status', ['none', 'pending', 'accepted'])->default('none')->comment('Refund status: none, pending, accepted');
            $table->string('refund_reason')->nullable()->comment('Reason for refund request, if any');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['refund_request', 'refund_status', 'refund_reason']);
        });
    }
}
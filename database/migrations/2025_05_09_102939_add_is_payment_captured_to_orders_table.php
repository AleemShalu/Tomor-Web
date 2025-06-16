<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddIsPaymentCapturedToOrdersTable extends Migration
{
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->boolean('is_payment_captured')->default(false)->comment('Indicates if payment has been captured');
        });

        // Set default for existing rows
        \DB::table('orders')
            ->whereNull('is_payment_captured')
            ->update(['is_payment_captured' => false]);
    }

    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('is_payment_captured');
        });
    }
}
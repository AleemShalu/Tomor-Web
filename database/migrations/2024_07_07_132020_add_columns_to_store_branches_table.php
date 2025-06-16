<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('store_branches', function (Blueprint $table) {
            $table->string('identity_number', 10)->nullable();
            $table->string('supplier_id')->nullable();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('store_branches', function (Blueprint $table) {
            $table->dropColumn('identity_number');
            $table->dropColumn('supplier_id');

        });
    }
};

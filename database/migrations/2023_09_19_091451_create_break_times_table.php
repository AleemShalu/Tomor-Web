<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('break_times', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('work_day_id');
            $table->time('break_start_time');
            $table->time('break_end_time')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('work_day_id')->references('id')->on('work_days');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::dropIfExists('break_times');
    }
};

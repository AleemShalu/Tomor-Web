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
        Schema::create('loggers', function (Blueprint $table) {
            $table->id();
            $table->string('path');
            $table->string('method');
            $table->string('ip');
            $table->string('http_version');
            $table->timestamp('timestamp');
            $table->string('model')->nullable();
            $table->json('headers');
            $table->json('request')->nullable();
            $table->json('response')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loggers');
    }
};

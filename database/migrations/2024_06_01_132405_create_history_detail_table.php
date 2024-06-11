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
        Schema::create('history_detail', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('history_id');
            $table->unsignedBigInteger('service_id');
            $table->foreign('history_id')->references('id')->on('histories')->onDelete('cascade');
            $table->foreign('service_id')->references('id')->on('services')->onDelete('cascade');
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
        Schema::dropIfExists('history_detail');
    }
};

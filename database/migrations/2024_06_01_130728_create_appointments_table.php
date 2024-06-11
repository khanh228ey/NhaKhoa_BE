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
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->string('name',50);
            $table->string('phone',20);
            $table->string('email')->nullable();
            $table->string('date',20);
            $table->string('time',20);
            $table->uuid('doctor_id')->nullable();
            $table->integer('status')->comment('0 là chưa xác nhận , 1 là đã xác nhận , 2 là đã hủy');
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
        Schema::dropIfExists('appointments');
    }
};

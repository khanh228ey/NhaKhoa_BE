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
        Schema::create('users', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('name',50);
            $table->string('avatar',100)->nullable();
            $table->string('email',100)->unique();
            $table->string('phone_number',20)->unique();
            $table->boolean('gender')->comment('0 là nam và 1 là nữ');
            $table->date('birthday');
            $table->string('address',100)->nullable();
            $table->text('education')->nullable();
            $table->text('certificate')->nullable();
            $table->boolean('status')->default(1);
            $table->unsignedBigInteger('role_id');
            // $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserReflinks extends Migration
{

    public function up()
    {
        Schema::create('user_reflinks', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->index();
            $table->string('handler', 255);
            $table->string('token', 100)->unique();
            $table->json('properties');
            $table->timestamps();
        });
    }


    public function down()
    {
        Schema::dropIfExists('user_reflinks');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EmailEvents extends Migration
{
    public function up()
    {
        Schema::create('email_events', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->string('code');
            $table->string('name');
            $table->text('fields');
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_events');
    }
}

<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Notifications extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('sender_id')->nullable();
            $table->string('type');
            $table->text('message')->nullable();
            $table->unsignedInteger('object_id')->nullable();
            $table->string('object_type')->nullable();
            $table->text('parameters');
            $table->timestamps();
            $table->timestamp('sent_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}

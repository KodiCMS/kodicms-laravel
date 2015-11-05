<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificationsUnsubscribers extends Migration
{
    public function up()
    {
        Schema::create('notifications_unsubscribers', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->integer('object_type');

            $table->primary(['user_id', 'object_type']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications_unsubscribers');
    }
}

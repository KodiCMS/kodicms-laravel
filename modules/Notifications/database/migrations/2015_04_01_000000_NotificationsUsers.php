<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NotificationsUsers extends Migration
{
    public function up()
    {
        Schema::create('notifications_users', function (Blueprint $table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('notification_id');
            $table->boolean('is_read')->default(false);

            $table->primary(['user_id', 'notification_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications_users');
    }
}

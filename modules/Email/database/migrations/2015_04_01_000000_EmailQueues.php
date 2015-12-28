<?php

use KodiCMS\Email\Model\EmailQueue;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class EmailQueues extends Migration
{
    public function up()
    {
        Schema::create('email_queues', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();
            $table->enum('status', [EmailQueue::STATUS_PENDING, EmailQueue::STATUS_SENT, EmailQueue::STATUS_FAILED]);
            $table->text('parameters');
            $table->string('message_type', 5);
            $table->text('body');
            $table->unsignedInteger('attempts')->default(0);
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_queues');
    }
}

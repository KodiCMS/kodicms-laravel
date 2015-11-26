<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class EmailTemplates extends Migration
{
    public function up()
    {
        Schema::create('email_templates', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->unsignedInteger('email_event_id');
            $table->foreign('email_event_id')->references('id')->on('email_events')->onDelete('cascade');

            $table->tinyInteger('status');

            $table->boolean('use_queue')->default(false);
            $table->string('email_from');
            $table->string('email_to');
            $table->string('subject');

            $table->text('message');

            $table->string('message_type', 5);

            $table->string('cc')->nullable();
            $table->string('bcc')->nullable();
            $table->string('reply_to')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('email_templates');
    }
}

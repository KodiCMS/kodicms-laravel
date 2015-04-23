<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class MessagesUsers extends Migration
{
	public function up()
	{
		Schema::create('messages_users', function (Blueprint $table) {
			$table->unsignedInteger('message_id');
			$table->unsignedInteger('parent_id')->default(0);
			$table->unsignedInteger('user_id');

			// TODO: вынести статус
			$table->tinyInteger('status')->default(1)->index();
			$table->timestamp('updated_at');

			$table->index(['message_id', 'user_id']);
		});
	}

	public function down()
	{
		Schema::dropIfExists('messages_users');
	}
}

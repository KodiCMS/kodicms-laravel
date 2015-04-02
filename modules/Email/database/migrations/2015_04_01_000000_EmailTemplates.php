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

			$table->unsignedInteger('type_id')->index();
			$table->tinyInteger('status');

			$table->boolean('use_queue')->default(FALSE);
			$table->string('email_from');
			$table->string('email_to');
			$table->string('subject');

			$table->text('message');

			// TODO: вынести константу в класс Email
			$table->string('message_type', 5)->default('html');

			$table->string('cc');
			$table->string('bcc');
			$table->string('reply_to');
		});
	}

	public function down()
	{
		Schema::dropIfExists('email_templates');
	}
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class UserReflinks extends Migration
{
	public function up()
	{
		Schema::create('user_reflinks', function (Blueprint $table) {
			$table->integer('user_id')->index();
			$table->string('type', 50);
			$table->string('code', 50)->index();
			$table->json('properties');
			$table->timestamp('created_at');

			$table->unique(['user_id', 'code']);
		});
	}

	public function down()
	{
		Schema::dropIfExists('user_reflinks');
	}
}

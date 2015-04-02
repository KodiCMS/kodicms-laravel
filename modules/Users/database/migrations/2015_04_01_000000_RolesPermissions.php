<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RolesPermissions extends Migration
{
	public function up()
	{
		Schema::create('roles_permissions', function (Blueprint $table) {
			$table->unsignedInteger('role_id');
			$table->string('action', 100);
			$table->unique(['role_id', 'action']);
		});
	}

	public function down()
	{
		Schema::dropIfExists('roles_permissions');
	}
}

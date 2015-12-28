<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class ConfigsTable extends Migration
{
    public function up()
    {
        Schema::create('config', function (Blueprint $table) {
            $table->string('group', 128);
            $table->string('key', 128);
            $table->text('value');

            $table->primary(['group', 'key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('config');
    }
}

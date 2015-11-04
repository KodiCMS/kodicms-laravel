<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PageWidgets extends Migration
{
    public function up()
    {
        Schema::create('page_widgets', function (Blueprint $table) {
            $table->unsignedInteger('page_id');
            $table->unsignedInteger('widget_id')->index();
            $table->string('block', 32);

            $table->integer('position')->default(500);
            $table->boolean('set_crumbs')->default(false);
            $table->index(['page_id', 'block']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_widgets');
    }
}

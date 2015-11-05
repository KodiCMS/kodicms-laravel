<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class RelatedWidgets extends Migration
{
    public function up()
    {
        Schema::create('related_widgets', function (Blueprint $table) {
            $table->integer('id');
            $table->unsignedInteger('to_widget_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('related_widgets');
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class PageBehaviorSetting extends Migration
{
    public function up()
    {
        Schema::create('page_behavior_settings', function (Blueprint $table) {
            $table->unsignedInteger('page_id')->index()->unique();
            $table->text('settings');
        });
    }

    public function down()
    {
        Schema::dropIfExists('page_behavior_settings');
    }
}

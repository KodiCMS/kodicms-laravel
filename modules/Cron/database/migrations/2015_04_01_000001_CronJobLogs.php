<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CronJobLogs extends Migration
{
    public function up()
    {
        Schema::create('cron_job_logs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('job_id')->index();
            $table->tinyInteger('status');
            $table->timestamps();

            //$table->foreign('job_id')->references('id')->on('cron_jobs')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('cron_job_logs');
    }
}

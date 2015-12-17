<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttemptsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempts', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('job_id')->unsigned();
            $table->timestamp('try_at');
            $table->timestamp('tried_at')->nullable();
            $table->integer('status_code')->nullable();
            $table->longText('response')->nullable();
            $table->timestamps();

            $table->foreign('job_id')
              ->references('id')
              ->on('jobs')
              ->onUpdate('cascade')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('attempts');
    }
}

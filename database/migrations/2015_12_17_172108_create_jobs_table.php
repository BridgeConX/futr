<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateJobsTable extends Migration
{

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('jobs', function (Blueprint $table) {
            $table->increments('id');
            $table->enum('method', [
              'GET',
              'POST',
              'PUT',
              'PATCH',
              'DELETE',
            ]);
            $table->longText('payload');
            $table->string('callback_url')->nullable();;
            $table->timestamp('start_at');
            $table->time('time');
            $table->integer('retries');
            $table->integer('retry_interval');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('jobs');
    }
}

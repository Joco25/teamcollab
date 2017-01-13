<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTopicNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topic_notifications', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('team_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('topic_id')->unsigned();
			$table->string('type', 50)->default('watching');
			$table->timestamps();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('topic_notifications');
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTopicPostLikesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topic_post_likes', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('team_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->integer('topic_post_id')->unsigned();
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
		Schema::drop('topic_post_likes');
	}

}

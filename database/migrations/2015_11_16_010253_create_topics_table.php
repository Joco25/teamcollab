<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateTopicsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('topics', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('team_id')->unsigned();
			$table->integer('user_id')->unsigned();
			$table->string('name', 500);
			$table->integer('post_count')->unsigned()->default(0);
			$table->integer('like_count')->unsigned()->default(0);
			$table->integer('view_count')->unsigned()->default(0);
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
		Schema::drop('topics');
	}

}

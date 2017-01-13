<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateCardsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('cards', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('user_id')->nullable();
			$table->integer('stage_id')->nullable();
			$table->string('name', 600)->nullable();
			$table->text('description', 65535)->nullable();
			$table->timestamps();
			$table->integer('priority')->nullable();
			$table->integer('team_id')->nullable();
			$table->boolean('blocked')->nullable();
			$table->integer('impact')->nullable();
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('cards');
	}

}

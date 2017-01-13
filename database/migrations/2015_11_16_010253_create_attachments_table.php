<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateAttachmentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('attachments', function(Blueprint $table)
		{
			$table->increments('id');
			$table->integer('card_id')->nullable();
			$table->integer('user_id')->nullable();
			$table->integer('team_id')->nullable();
			$table->string('filename', 400)->nullable();
			$table->string('original_filename', 1000)->nullable();
			$table->string('file_url', 600)->nullable();
			$table->string('file_size', 200)->nullable();
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
		Schema::drop('attachments');
	}

}

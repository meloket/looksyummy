<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateFollowersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('followers', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('following_id')->index('fk_followers_following_id_users')->comment('The user id of the person who is following');
			$table->integer('follower_id')->index('fk_followers_follower_id_users')->comment('The user id of the person who is being followed');
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
		Schema::drop('followers');
	}

}

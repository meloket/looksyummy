<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToCommentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('comments', function(Blueprint $table)
		{
			$table->foreign('meal_id', 'comments_ibfk_1')->references('id')->on('meals')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('meal_id', 'comments_ibfk_2')->references('id')->on('meals')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('user_id', 'comments_ibfk_3')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('comments', function(Blueprint $table)
		{
			$table->dropForeign('comments_ibfk_1');
			$table->dropForeign('comments_ibfk_2');
			$table->dropForeign('comments_ibfk_3');
		});
	}

}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class AddForeignKeysToMealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('meals', function(Blueprint $table)
		{
			$table->foreign('restaurant_id', 'meals_ibfk_1')->references('id')->on('restaurants')->onUpdate('RESTRICT')->onDelete('RESTRICT');
			$table->foreign('posted_by', 'meals_ibfk_2')->references('id')->on('users')->onUpdate('RESTRICT')->onDelete('RESTRICT');
		});
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('meals', function(Blueprint $table)
		{
			$table->dropForeign('meals_ibfk_1');
			$table->dropForeign('meals_ibfk_2');
		});
	}

}

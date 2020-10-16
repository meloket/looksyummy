<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateMealsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('meals', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('code', 25);
			$table->integer('restaurant_id')->index('fk_meals_restaurant_id_restaurants');
			$table->string('title', 250);
			$table->text('description', 65535)->nullable();
			$table->string('photo', 50);
			$table->integer('posted_by')->index('fk_meals_posted_by_users')->comment('User Id of the contributor');
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
		Schema::drop('meals');
	}

}

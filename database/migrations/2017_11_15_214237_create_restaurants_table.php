<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateRestaurantsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('restaurants', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('fk_restaurants_user_id_users');
			$table->string('place_id', 100)->nullable();
			$table->string('place_name')->nullable();
			$table->string('place_code', 100)->nullable();
			$table->string('place_vicinity', 250)->nullable();
			$table->text('place_photo_reference')->nullable();
			$table->enum('place_open_now', array('0','1'))->default('0');
			$table->float('place_rating', 10, 0)->nullable();
			$table->string('place_types', 250)->nullable();
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
		Schema::drop('restaurants');
	}

}

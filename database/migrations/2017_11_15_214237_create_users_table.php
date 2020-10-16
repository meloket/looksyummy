<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateUsersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->string('first_name', 100)->nullable();
			$table->string('last_name', 100)->nullable();
			$table->string('username', 50);
			$table->string('password', 150);
			// Email is set as null in order to insert a record from FourSquare or Google
			$table->string('email', 150)->nullable(); 
			$table->string('phone', 75)->nullable();
			$table->string('profile_pic', 75)->nullable();
			$table->string('address', 350)->nullable();
			$table->string('city', 150)->nullable();
			$table->string('state', 150)->nullable();
			$table->string('country', 150)->nullable();
			$table->decimal('lat', 16, 8)->nullable();
			$table->decimal('lng', 16, 8)->nullable();
			$table->string('zip', 25)->nullable();
			$table->text('bio', 65535)->nullable();
			$table->string('fb_id', 50)->nullable();
			$table->enum('user_type', array('1','2'))->default('1');
			$table->enum('user_role', array('user','admin'))->nullable()->default('user');
			$table->string('website', 350)->nullable();
			$table->enum('device_type', array('iOS','Android'))->nullable();
			$table->mediumText('device_token')->nullable();
			$table->enum('active', array('0','1'))->default('1');
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
		Schema::drop('users');
	}

}

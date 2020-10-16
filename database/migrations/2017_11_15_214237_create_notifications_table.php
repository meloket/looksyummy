<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateNotificationsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('notifications', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('sender_id')->index('fk_notifications_sender_id_users');
			$table->integer('recipient_id')->index('fk_notifications_recipient_id_users');
			$table->enum('notify_type', array('1','2','3'))->default('1')->comment('1=Like, 2=Comment, 3=Follow');
			$table->integer('meal_id')->index('fk_notifications_meal_id_meals')->nullable();
			
			$table->integer('comment_id')->nullable()->index('fk_notifications_comment_id_comments');
			$table->enum('viewed', array('0','1'))->default('0');
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
		Schema::drop('notifications');
	}

}

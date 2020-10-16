<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFlaggedMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('flagged_meals', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('meal_id')->index('fk_flagged_meals_id_meals')->comment('The id of the meeal who is being flagged');
			$table->integer('user_id')->index('fk_flagged_user_id_users')->comment('The id of the person who is flagging the meal');
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
        Schema::drop('flagged_meals');
    }
}

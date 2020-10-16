<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBlockedUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blocked_users', function(Blueprint $table)
		{
			$table->bigInteger('id', true);
			$table->integer('user_id')->index('fk_blocked_users_id_users')->comment('The user id of the person who is blocking');
			$table->integer('blocked_user_id')->index('fk_blocked_blocked_user_id_users')->comment('The user id of the person who is being blocked');
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
        Schema::drop('blocked_users');
    }
}

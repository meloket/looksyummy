<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreatePaymentsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('payments', function(Blueprint $table)
		{
			$table->integer('id', true);
			$table->integer('user_id')->index('fk_payments_user_id_users')
				->comment('The id of the business user who is paying');						 
			$table->double('amount', 14, 2);		 
			$table->string('currency', '10');
			$table->string('card_type', 25);
			$table->string('card_last_4_digits', 4);  		 
			$table->string('transaction_id', 100);
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
		Schema::drop('payments');
	}

}

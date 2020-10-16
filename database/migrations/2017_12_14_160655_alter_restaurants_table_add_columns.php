<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterRestaurantsTableAddColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('restaurants', function (Blueprint $table) {            
			$table->string('place_street', 150)->nullable()->after('place_code');
			$table->string('place_locality', 150)->nullable()->after('place_street');
			$table->string('place_city', 100)->nullable()->after('place_locality');
			$table->string('place_state', 100)->nullable()->after('place_city');
			$table->string('place_country', 100)->nullable()->after('place_state');
			$table->string('place_zipcode', 25)->nullable()->after('place_country');
			$table->decimal('place_lat', 16, 8)->nullable()->after('place_zipcode');
			$table->decimal('place_lng', 16, 8)->nullable()->after('place_lat');
			$table->string('place_website', 150)->nullable()->after('place_lng');
			
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('restaurants', function (Blueprint $table) {
            $table->dropColumn('place_street');
			$table->dropColumn('place_locality');
			$table->dropColumn('place_city');
			$table->dropColumn('place_state');
			$table->dropColumn('place_country');
			$table->dropColumn('place_zipcode');
			$table->dropColumn('place_lat');
			$table->dropColumn('place_lng');
			$table->dropColumn('place_website');
        });
    }
}

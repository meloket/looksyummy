<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnsToMealsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('meals', function (Blueprint $table) {
            
            $table->string('restaurant_name', 300)->nullable()->after('posted_by');
            $table->double('lat', 12, 4)->nullable()->after('restaurant_name');
            $table->double('lon', 12, 4)->nullable()->after('lat');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('meals', function (Blueprint $table) {
            $table->dropColumn('restaurant_name');
            $table->dropColumn('lat');
            $table->dropColumn('lon');
        });
    }
}

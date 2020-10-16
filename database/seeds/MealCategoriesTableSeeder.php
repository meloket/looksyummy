<?php

use Illuminate\Database\Seeder;

class MealCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('meal_categories')->delete();
        
        \DB::table('meal_categories')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Drinks'
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Apps'
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Entrees'
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Desserts'
            )
		));
    }
}

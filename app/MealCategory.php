<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class MealCategory extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'restaurant_id', 'title', 'description', 'photo', 'posted_by'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'password','active'
    ];
	
	
	
	public function meal()
    {
		return $this->hasMany('App\Meal', 'meal_category_id', 'id');
		//return $this->hasManyThrough('App\Meal', 'meal_category_id', 'id');
		
    }
}

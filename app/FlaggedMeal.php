<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class FlaggedMeal extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'meal_id', 'user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
	
	
	public function user()
    {
		return $this->belongsTo('App\User', 'user_id', 'id');
    }

	public function meal()
    {
		return $this->belongsTo('App\Meal', 'meal_id', 'id');
    }
}

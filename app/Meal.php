<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Meal extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'code', 'restaurant_id', 'meal_category_id', 'title', 'description', 'photo', 'posted_by'
    ];

    protected $dates = ['created_at'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'password','active'
    ];
	
	
	
	public function comments(){
		return $this->hasMany('App\Comment', 'meal_id', 'id');
	}
		
	public function commentsCount()
	{
		return $this->comments()
		->selectRaw('meal_id, count(*) as aggregate')
		->groupBy('meal_id');
	}

	public function likes(){
        return $this->hasMany('App\Like', 'meal_id', 'id');
    }
	
	public function notifications(){
        return $this->hasMany('App\Notification', 'meal_id', 'id');
    }
		
	public function likesCount()
	{
		return $this->likes()
		->selectRaw('meal_id, count(*) as aggregate')
		->groupBy('meal_id');
	}

	
	public function restaurant()
    {
		return $this->belongsTo('App\Restaurant');
    }
	
	
	public function user()
    {
		return $this->belongsTo('App\User', 'posted_by', 'id');
    }

	public function mealCategory()
    {
		return $this->belongsTo('App\MealCategory', 'meal_category_id', 'id');
    }

    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }

    
	public function scopeMealCategoryId($query, $categoryId)
	{
		if($categoryId != 0 && $categoryId != "")
			return $query->where('meal_category_id', '=', $categoryId);
		else
			return $query;
	}
    
	public function scopeKeyword($query, $keyword)
	{
		if($keyword != "")		
			return $query->where('title', 'like', '%' . $keyword . '%')->orWhere('description', 'like', '%' . $keyword . '%');
		else
			return $query;
	}

}

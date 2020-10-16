<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Restaurant extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'place_id',  'place_name',  'place_code',  'place_street',  'place_locality',  'place_city',  'place_state',  'place_country',  
		'place_zipcode',  'place_lat',  'place_lng',  'place_lng',  'active',
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
    
    public function meals(){
      return $this->hasMany('App\Meal', 'restaurant_id', 'id');
    }

    
    public function restaurantUsers(){
      return $this->hasMany('App\RestaurantUser', 'restaurant_id', 'id');
    }

    
	public function scopeKeyword($query, $keyword)
	{
		if($keyword != "")		
			return $query->where('place_name', 'like', '%' . $keyword . '%')->orWhere('place_vicinity', 'like', '%' . $keyword . '%')->orWhere('place_phone', 'like', '%' . $keyword . '%')->orWhere('place_types', 'like', '%' . $keyword . '%');
		else
			return $query;
	}
    
    
	
}

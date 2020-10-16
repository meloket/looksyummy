<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class RestaurantUser extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       
    ];
    public $timestamps = false;
  
    public function user()
    {
		  return $this->belongsTo('App\User', 'user_id', 'id');
    }
    
    public function restaurant(){
      return $this->hasMany('App\Restaurant', 'restaurant_id', 'id');
    }

    
	
}

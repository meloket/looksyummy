<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Follower extends Authenticatable
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'following_id', 'follower_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       
    ];
	
	/*public function tariff(){
		return $this->hasMany('App\Tariff', 'id', 'user_id');
	}*/
	
	
	public function user()
    {
		return $this->belongsTo('App\User', 'following_id', 'id');
    }
	
	public function follower()
  {
		return $this->hasMany('App\User', 'id', 'follower_id');
  }
}

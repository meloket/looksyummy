<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Ravaelles\Filterable\Filterable as Filterable;

class User extends Authenticatable
{
    use Notifiable;
    //use Filterable; // Add trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'username', 'password', 'email', 'phone', 'profile_pic', 'user_role', 'user_type', 'address', 'city', 'state', 'country', 'zip', 'business_name', 'lat', 'lng', 'bio', 'fb_id', 'website', 'device_type', 'device_type', 'device_token', 'show'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       'password'
    ];
	
	public function posts(){
		return $this->hasMany('App\Meal', 'posted_by', 'id');
	}
	
	
	public function followers(){
		return $this->hasMany('App\Follower', 'following_id', 'id');
	}
	
	
	public function followings(){
		return $this->hasMany('App\Follower', 'follower_id', 'id');
	}
	
	public function followersCount()
	{
		return $this->followers()
		->selectRaw('following_id, count(*) as aggregate')
		->groupBy('following_id');
	}
	
	public function followingsCount()
	{
		return $this->followings()
		->selectRaw('follower_id, count(*) as aggregate')
		->groupBy('follower_id');
	}
	
	public function mealsCount()
	{
		return $this->posts()
		->selectRaw('posted_by, count(*) as aggregate')
		->groupBy('posted_by');
	}
	
	
	public function restaurant()
    {
		return $this->belongsTo('App\Restaurant', 'id', 'user_id');
    }


	
	public function meals()
    {
		return $this->hasManyThrough('App\Meal', 'App\Restaurant');

		/*return $this->hasManyThrough(
            'App\Meal',
            'App\Restaurant',
            'restaurant_id', // Foreign key on restaurants table...
            'user_id', // Foreign key on meals table...
            'id', // Local key on users table...
            'id' // Local key on restaurants table...
        );*/

    }


	public function scopeUserType($query, $type)
	{
		if($type != 0 && $type != "")
			return $query->where('user_type', '=', $type);
		else
			return $query;
	}

	public function scopeKeyword($query, $keyword)
	{
		if($keyword != "")		
			return $query->where('first_name', 'like', '%' . $keyword . '%')->orWhere('last_name', 'like', '%' . $keyword . '%')->orWhere('username', 'like', '%' . $keyword . '%')->orWhere('email', 'like', '%' . $keyword . '%');
		else
			return $query;
	}

	// this is a recommended way to declare event handlers
    /*protected static function boot() {
        parent::boot();

        static::deleting(function($user) { // before delete() method call this
		
			 $user->meals()->delete();
             $user->followers()->delete();
			 $user->followings()->delete();			 
			 $user->restaurant()->delete();
             // do the rest of the cleanup...
        });
	}*/
	

	/**
     * Route notifications for the FCM channel.
     *
     * @param  \Illuminate\Notifications\Notification  $notification
     * @return string
     */
    public function routeNotificationForFcm()
    {
        //dd($this->device_token);
        return $this->device_token;
    }
}

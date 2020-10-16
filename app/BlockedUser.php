<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use Ravaelles\Filterable\Filterable as Filterable;

class BlockedUser extends Authenticatable
{
    use Notifiable;
    //use Filterable; // Add trait

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id', 'blocked_user_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
    ];
	
	
	public function user(){
		return $this->belongsTo('App\User', 'id', 'user_id');
	}
	
	
	public function blocked(){
		return $this->belongsTo('App\User', 'id', 'blocked_user_id');
	}
	

}

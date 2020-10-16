<?php

namespace App;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Notification extends Model
{
    
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'sender_id', 'recipient_id', 'notify_type', 'meal_id', 'comment_id', 'viewed'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
       
    ];
	
	public function sender(){
		return $this->belongsTo('App\User', 'sender_id', 'id');
	}
		
	public function recipient(){
		return $this->belongsTo('App\User', 'recipient_id', 'id');
	}
	
	public function meal(){
		return $this->belongsTo('App\Meal', 'meal_id', 'id');
	}
	
	public function comment(){
		return $this->belongsTo('App\Comment', 'comment_id', 'id');
    }
    
    public function getCreatedAtAttribute($value)
    {
        return Carbon::parse($value)->diffForHumans();
    }
}

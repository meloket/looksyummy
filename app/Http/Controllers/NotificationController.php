<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Hash;
use App\User;
use App\Comment;
use App\Like;
use App\Meal;
use App\Restaurant;
use App\Follower;
use App\Notification;
use Illuminate\Support\Facades\Validator;


class NotificationController extends Controller
{
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
		$input = $request->all();
		$rules = array(
            'user_id'  	=> 'required|numeric'
        );
		
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
					'success' => false,
					'message' =>'Unable to retrieve data',
					'errorDtl' => $validator->errors()->all()
				), 200);
				
		} else {
		
			try {
				//'sender', 'recipient', 'meal', 'comment'
				$notifications = Notification::where('recipient_id', $input['user_id'])->with(
				array(
						'sender' => function($query3){
							$query3->select('id','username', 'profile_pic', 'profile_pic_rotation');
						},
					
						'recipient' => function($query3){
							$query3->select('id','username', 'profile_pic', 'profile_pic_rotation');
						},
						'meal' => function($query3){
							$query3->select('id','photo');
						},
					
					)
				)->orderBy('created_at', 'desc')->get();
				
				foreach($notifications as $notification) {
					
					
					if(isset($notification->sender->profile_pic) && $notification->sender->profile_pic != "") {
						$notification['sender']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$notification->sender->profile_pic);
						
					}
					
					
					if(isset($notification->recipient->profile_pic) && $notification->recipient->profile_pic != "") {
						$notification['recipient']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$notification->recipient->profile_pic);
						
					}

					
					if(isset($notification->meal->photo) && $notification->meal->photo != "") {
						//$notification['recipient']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max')."/".$notification->recipient->profile_pic);						
						$notification['meal']['photo_url'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$notification->meal->photo);
					}

					
					
					
					//unset($notification->sender->profile_pic);
					//unset($notification->recipient->profile_pic);
					
						
				}
				
				return response(array(
					'success' => false,
					'notifications' =>$notifications->toArray(),
					'message' => 'Record found.'
				), 200);
						
				
				/*if(count($users) > 0) {
					
					foreach($users as $user) {
						if(isset($user['profile_pic']) && $user['profile_pic'] != "") {
							$user['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max')."/".$user['profile_pic']);
						}
					}
					
				}*/
				
				if(count($meals) > 0) {
										
					foreach($meals as $meal) {
						
						$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
						
						$meal['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/meals/max/').$meal->user->profile_pic);
						
					}
					
				}
				
			
				/*if(count($meals) == 0 && count($users) == 0 && count($restaurants) == 0) {
					return response(array(
						'success' => false,
						'message' => 'No record found.'
						),200);
					
				}
				else {
					return response(array(
						'success' => false,
						'meals' =>$meals->toArray(),
						'users' =>$users->toArray(),
						'restaurants' =>$restaurants->toArray(),
						'message' => 'Record found.'
						),200);
				}*/
				   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.'
					),200);
			}
			
		}			   
    }
	
	public function viewed(Request $request)
    {		
		$input = $request->all();
		$rules = array(
            'id'  	=> 'required|numeric'
        );
		
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
					'success' => false,
					'message' =>'Unable to retrieve data',
					'errorDtl' => $validator->errors()->all()
				), 200);
				
		} else {
		
			try {
				
				$notification = Notification::find($input['id']);

				if(isset($notification)) {
					$notification->viewed = 1;
					$notification->save();
					
					return response(array(
						'success' => true,
						'message' => 'You have viewed the notification.'
					),200);
					
				}
				else {
					return response(array(
						'success' => false,
						'message' => 'Notification does not exist.'
					),200);
				}				
				
				   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.'
					),200);
			}
			
		}			   
    }
	
	
	
}

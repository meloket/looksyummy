<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use Hash;
use Log;
use App\User;
use App\Comment;
use App\Like;
use App\Meal;
use App\Restaurant;
use App\Follower;
use SKAgarwal\GoogleApi\PlacesApi;
use Illuminate\Support\Facades\Validator;


class SearchController extends Controller
{
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {	
			$keyword = "";
			$input = $request->all();
			$rules = array(
							'keyword'  	=> 'required',
							'self_id' => 'numeric|required',
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
					if(isset($input['keyword']))
						$keyword = $input['keyword'];
					
					// split on 1+ whitespace & ignore empty (eg. trailing space)
					//$searchValues = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY); 
					
					// List of words and phrases to be excluded in search
					$words = app('sharedSearchKeywords'); //Get the Search Keywords stored in AppServiceProvider
	
					$pattern = '/\b(?:' . join('|', $words) . ')\b/i';
					$keyword = preg_replace($pattern, '', $keyword);
					
					$searchValues = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY); 
					
					//$searchValues = preg_split('/((?<!(in|to|by|the|and|for))\.|\?|;) /', $keyword, -1, PREG_SPLIT_NO_EMPTY); 


					$meals = Meal::where('active', '1')->where(function ($q) use ($searchValues) {
						foreach ($searchValues as $value) {
						$q->orWhere('title', 'like', "%{$value}%");
						$q->orWhere('description', 'like', "%{$value}%");
						}
					})->orderBy('created_at', 'desc')->with(
					array(
							'restaurant' => function($query){
								$query->select('id','place_name', 'place_lat', 'place_lng');
							},
							
							'user' => function($query3){
								$query3->select('id','username', 'profile_pic');
							},
							
							'likesCount',
							'commentsCount'
						
						)
					)->orderBy('created_at', 'desc')->paginate(20);
					
					
					$users = User::where('active', '1')->where('user_type', '1')->where(function ($q) use ($searchValues) {
						foreach ($searchValues as $value) {
						$q->orWhere('username', 'like', "%{$value}%");
						$q->orWhere('first_name', 'like', "%{$value}%");
						$q->orWhere('last_name', 'like', "%{$value}%");
						$q->orWhere('business_name', 'like', "%{$value}%");
						$q->orWhere('address', 'like', "%{$value}%");
						$q->orWhere('city', 'like', "%{$value}%");
						$q->orWhere('state', 'like', "%{$value}%");
						$q->orWhere('country', 'like', "%{$value}%");
						}
					})->with('followersCount')->select('id','username', 'profile_pic')->orderBy('created_at', 'desc')->paginate(20);
					
					
					$restaurants = Restaurant::where('active', '1')->where(function ($q) use ($searchValues) {
						foreach ($searchValues as $value) {
						$q->orWhere('place_name', 'like', "%{$value}%");
						$q->orWhere('place_street', 'like', "%{$value}%");
						$q->orWhere('place_locality', 'like', "%{$value}%");
						$q->orWhere('place_city', 'like', "%{$value}%");
						$q->orWhere('place_state', 'like', "%{$value}%");
						$q->orWhere('place_country', 'like', "%{$value}%");
						$q->orWhere('place_zipcode', 'like', "%{$value}%");
						$q->orWhere('place_vicinity', 'like', "%{$value}%");
						$q->orWhere('place_types', 'like', "%{$value}%");
						}
					})->orderBy('created_at', 'desc')->paginate(20);
					
					
					if(count($users) > 0) {
						
						foreach($users as $user) {
							if(isset($user['profile_pic']) && $user['profile_pic'] != "") {
								$user['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$user['profile_pic']);
							}
						}
						
					}
					
					if(count($meals) > 0) {
											
						foreach($meals as $meal) {
							
							$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
							
							
							$meal['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/meals/max/').$meal->user->profile_pic);
							
							
							$liked_by_me = Like::where('meal_id', $meal['id'])->where('user_id', $input['self_id'])->count();	
							$meal['liked_by_me'] = $liked_by_me;
							
							
						}
						
					}
					
				
					if(count($meals) == 0 && count($users) == 0 && count($restaurants) == 0) {
						return response(array(
							'success' => false,
							'message' => 'No record found.'
							),200);
						
					}
					else {
						return response(array(
							'success' => true,
							'meals' =>$meals->toArray(),
							'users' =>$users->toArray(),
							'restaurants' =>$restaurants->toArray(),
							'message' => 'Record found.'
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
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function restaurants(Request $request)
    {	
			$type = "";
			$keyword = "";
			$lat = "";
			$lng = "";
			$input = $request->all();
			
			if(isset($input['type']) && $input['type'] != "")
				$type = $input['type'];
			else
				$type = 1;
			
			// Search for keyword
			if($type == 1) {
				$rules = array(
					'keyword'  	=> 'required'
				);
			}
			else {
				$rules = array(
					'lat'  	=> 'required',
					'lng'  	=> 'required'
				);
			}
			
					$validator = Validator::make($input, $rules);
			if ($validator->fails()) {
			
				return response(array(
						'success' => false,
						'message' =>'Unable to retrieve data',
						'errorDtl' => $validator->errors()->all()
					), 200);
					
			} 
			else 
			{
			
				try 
				{
					if($type == 1) 
					{
						$keyword = $input['keyword'];
					
						// Split on 1+ whitespace & ignore empty (eg. trailing space)
						//$searchValues = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY); 
						
						// List of words and phrases to be excluded in search
						$words = app('sharedSearchKeywords'); //Get the Search Keywords stored in AppServiceProvider
		
						$pattern = '/\b(?:' . join('|', $words) . ')\b/i';
						$keyword = preg_replace($pattern, '', $keyword);
						
						$searchValues = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY); 
						
						//$searchValues = preg_split('/((?<!(in|to|by|the|and|for))\.|\?|;) /', $keyword, -1, PREG_SPLIT_NO_EMPTY); 

						
						$restaurants = Restaurant::where('active', '1')->where(function ($q) use ($searchValues) {
							foreach ($searchValues as $value) {
							$q->orWhere('place_name', 'like', "%{$value}%");
							$q->orWhere('place_street', 'like', "%{$value}%");
							$q->orWhere('place_locality', 'like', "%{$value}%");
							$q->orWhere('place_city', 'like', "%{$value}%");
							$q->orWhere('place_state', 'like', "%{$value}%");
							$q->orWhere('place_country', 'like', "%{$value}%");
							$q->orWhere('place_zipcode', 'like', "%{$value}%");
							$q->orWhere('place_vicinity', 'like', "%{$value}%");
							$q->orWhere('place_types', 'like', "%{$value}%");
							}
						})->orderBy('created_at', 'desc')->paginate(20);
						
						
					
						if(count($restaurants) == 0) {
							return response(array(
								'success' => false,
								'message' => 'No record found.'
								),200);
							
						}
						else {
							return response(array(
								'success' => true,
								'restaurants' =>$restaurants->toArray(),
								'message' => 'Record found.'
								),200);
						}
					} 
					else {
						$radius = 10;
						$lat = $input['lat'];
						$lng = $input['lng'];
						
						$sql = "SELECT * FROM (SELECT restaurants.*, (".$radius." * acos(cos(radians($lat)) * cos(radians(place_lat)) * cos(radians(place_lng) - radians($lng)) + sin(radians($lat)) * sin(radians(place_lat)))) AS distance FROM restaurants) AS distances WHERE distance < ".$radius." ORDER BY distance";
						$restaurants['current_page'] = 1;
						$restaurants['data'] = DB::select($sql);
						
						
						
						if(count($restaurants) == 0) {
							return response(array(
								'success' => false,
								'message' => 'No record found.'
								),200);
							
						}
						else {
							return response(array(
								'success' => true,
								'restaurants' =>$restaurants,
								'message' => 'Record found.'
								),200);
						}
						
						
					}
				} catch (\Illuminate\Database\QueryException $e) {
					return response(array(
						'success' => false,
						'message' => 'Unable to process request, database error occurred.'
						),200);
				}
				
			}			   
	}
	
	public function foursquare(Request $request)
    {
		
		Log::debug("Testing from foursquare: ".$request->endpoint);
	}
	
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Hash;
use DB;
use Log;
use Session;
use Mail;
use App\User;
use App\Comment;
use App\Like;
use App\BlockedUser;
use App\FlaggedMeal;
use App\Meal;
use App\Follower;
use App\Notification;
use App\Restaurant;
use App\Mail\ResetPasswordMail;
use Excel;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class UserController extends Controller
{
	public function index(Request $request)
	{
		$keyword = "";
		$user_type = "0";
		
		if($request->clear == 1) {
			$request->session()->forget('sess_filter_keyword');
			$request->session()->forget('sess_filter_user_type');
		}
		
		if(isset($request->keyword)) {
			$request->session()->put('sess_filter_keyword', $request->keyword);
		}

		if(isset($request->user_type)) {
			$request->session()->put('sess_filter_user_type', $request->user_type);
		}

		if ($request->session()->has('sess_filter_keyword')) {
		    $keyword = $request->session()->get('sess_filter_keyword');
		}

		if ($request->session()->has('sess_filter_user_type')) {
		    $user_type = $request->session()->get('sess_filter_user_type');
		}
		
		$users = User::keyword($keyword)->userType($user_type)->orderBy('id', 'DESC')->paginate(20);
		
		return view('admin/users/index', ['users' => $users]);	
	}
	
    public function register(Request $request)
    {
		$googleAPIKey = env('GOOGLE_PLACES_API_KEY');
		$input = $request->all();
		$rules = array(
            'first_name'       		=> 'required',
            'last_name'      		=> 'required',			
            'username'      		=> 'required|min:6|alpha_dash|unique:users',
            'email' 				=> 'required|email|unique:users',
            'password' 				=> 'required|between:6,12',
			'confirm_password' 		=> 'required|same:password',
            //'city'      			=> 'required',
            'country'      			=> 'required',
			//'zip' 					=> 'regex:/\b\d{5}\b/',
            //'phone' 				=> 'regex:/[0-9]{9}/',
			'lat'					=> 'required',
			'lng'					=> 'required',
            'user_type'      		=> 'required|in:1,2',
            'device_type' 			=> 'required',
            'device_token' 			=> 'required',
			
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
					
			return response(array(
					'success' => false,
					'error' => false,
					'message' =>'Unable to add user record',
					'errorDtl' => $validator->errors()->all()
				),200);
				
		} else {
			try {

				$user = new User;
				$user->first_name       	= $input['first_name'];
				$user->last_name      		= $input['last_name'];
				$user->username 			= $input['username'];
				$user->password 			= bcrypt($input['password']);
				$user->email 				= $input['email'];
				
				// Applicable for Business Users only
				if($input['user_type'] == 2) {

					if(!isset($input['business_name']) || !isset($input['address']) || !isset($input['country']) || !isset($input['state']) || !isset($input['zip']))	{
						
						return response(array(
							'success' => false,
							'message' =>'Please enter business name, address, state, country and zip code',
						),200);	
						
					}
					
				}
				
				// Applicable for Business Users
				if(isset($input['address']))
					$user->address 				= $input['address'];			// Applicable for Business Users
				
				if(isset($input['phone']))
					$user->phone 				= $input['phone'];
				
				if(isset($input['country']))
					$user->country 				= $input['country'];					// Applicable for Business Users
				
				if(isset($input['state']))
					$user->state 			= $input['state'];
				
				if(isset($input['city']))
					$user->city 			= $input['city'];
				
				if(isset($input['zip']))				
					$user->zip 				= $input['zip'];
				
				if(isset($input['website']))				
					$user->website 			= $input['website'];				
				
				$user->lat 					= $input['lat'];				
				$user->lng 					= $input['lng'];				
				$user->user_type 			= $input['user_type'];				// 1=Users | 2=Businesses
				
				$user->user_role 			= "user";
				$user->device_type 			= $input['device_type'];
				$user->device_token 		= $input['device_token'];
				$user->ownership 			= '1';

				if($input['user_type'] == 2)
					$user->active 				= '0'; // By default for business users the active is set to 0
				else
					$user->active 				= '1'; // Else active is set to 1
				
				
				
				// Create Restaurant Object
				$restaurant = new Restaurant;
				if($input['user_type'] == "2") {

					if(isset($input['business_name']) && $input['business_name'] != "") {
						
						$restaurant->place_name = $input['business_name'];
					}
					$restaurant->place_id = NULL;
					$restaurant->place_code = NULL;
					
					$restaurant->place_street = "";
					$restaurant->place_locality = "";
				}
				$address = "";
				
				if(isset($input['city']) && $input['city'] != "") {
					$address = $input['city'];
					if($input['user_type'] == 2)
						$restaurant->place_city = $input['city'];
				}
				
				if(isset($input['state']) && $input['state'] != "") {
					$address .= " ".$input['state'];
					if($input['user_type'] == 2)
						$restaurant->place_state = $input['state'];
				}
				
				if(isset($input['country']) && $input['country'] != "") {
					$address .= " ".$input['country'];
					if($input['user_type'] == 2)
						$restaurant->place_country = $input['country'];
				}
				
				if(isset($input['zip']) && $input['zip'] != "") {
					$address .= " ".$input['zip'];
					if($input['user_type'] == 2)
						$restaurant->place_zipcode = $input['zip'];
				}
				if($input['user_type'] == 2) {
					
					//Log::info($address);
					$prepAddr = str_replace(' ','+',$address);
					
					//dd($prepAddr);
					$url = 'https://maps.google.com/maps/api/geocode/json?key='.$googleAPIKey.'&address='.$prepAddr.'&sensor=false';
					$geocode=file_get_contents($url);
					//dd($geocode);
			
			 
					$output= json_decode($geocode);
					
					if(isset($output->results[0]->geometry->location->lat) && isset($output->results[0]->geometry->location->lng)) {
						$restaurant->place_lat = $output->results[0]->geometry->location->lat;
						$restaurant->place_lng = $output->results[0]->geometry->location->lng;
						
					}
			 
			
				}

				
				if(isset($input['website']) && $input['website'] != "") {
					if($input['user_type'] == 2)
						$restaurant->place_website = $input['website'];
				}
				
				if(isset($input['phone']) && $input['phone'] != "") {
					if($input['user_type'] == 2)
						$restaurant->place_phone = $input['phone'];
				}
				if($input['user_type'] == 2) {
					$restaurant->place_photo_reference	 = "";
					$restaurant->place_rating = NULL;

					$restaurant->place_vicinity = $address;
				}
				
				
				DB::transaction(function() use ($user, $restaurant, $input) {
					
					//Log::error(print_r($user, true));
				
					if($user->save()) {

						
						$this->followBulkUpdate();
						
						// Add a place record
						if($input['user_type'] == 2) {
							
							$restaurant->user_id = $user->id;
							if(!$restaurant->save()) {
								
								return response(array(
									'success' => false,
									'message' =>'Unable to save restaurant record'
								),200);
							}

							$admins = User::where('username', 'Looksyummy')->get();
							foreach($admins as $admin) {
								
								$follower = new Follower;
								$follower->follower_id       	= $user->id;
								$follower->following_id       	= $admin->id;	
								$follower->save();

							}
							
						}
						else {
							

							// Auto Follow all Administrators
							/*$admins = User::where('user_role', 'admin')->get();
							foreach($admins as $admin) {
								
								$follower = new Follower;
								$follower->follower_id       	= $user->id;
								$follower->following_id       	= $admin->id;	
								$follower->save();

							}*/
						}
						
						
						return response(array(
							'success' => true,
							'message' =>'User successfully registered',
							'user'	=> $user
						),200);
						
					}
					else {
						
						Log::error(print_r($user, true));
						
						return response(array(
							'success' => false,
							'message' =>'Unable to save user record'
						),200);
					}
				});
				
				return response(array(
							'success' => true,
							'message' =>'User successfully registered',
							'user'	=> $user
						),200);
			
			} catch (\Illuminate\Database\QueryException $e) {
				
				Log::error(print_r($user, true));
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.: '
					),200);
			}
		}
	}
	
	
	public function checkUserExists(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'auth_token' 			=> 'required',
            'email' 				=> 'required|email',
			'login_type'			=> 'required|in:facebook,google',	
            'device_type' 			=> 'required',
            'device_token' 			=> 'required',
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			
			return response(array(
					'error' => false,
					'message' =>'Unable to find user record',
					'errorDtl' => $validator->errors()->all()
				),200);
				
		} else {
		
		
			try {
				
				$user = User::where('email', $input['email'])->with('restaurant')->first();
				
									
				$input2 = array();	
				
				if(isset($user->id))
				{

					$user->device_type 			= $input['device_type'];
					$user->device_token 		= $input['device_token'];
					
					if($input['login_type'] == "facebook") {
						
						$resp = $user->fb_auth_token = $input['auth_token'];
						$user->save();
	
					}
				
					else if($input['login_type'] == "google"){
						
						$resp = $user->google_auth_token = $input['auth_token'];
						$user->save();
						
					}
				
					return response(array(
						'success' => true,
						'message' =>'User record found',
						'user'	=> $user
					),200);
					
				
				}
				else {
				
					
				
					return response(array(
							'success' => false,
							'message' =>'User record not found',
							'user'	=> $user
						),200);
						
				
				}
				
			
			} catch (\Illuminate\Database\QueryException $e) {
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.: '.$e
					),200);
			}
		}
	}
	
	public function socialSignup(Request $request)
    {
		$input = $request->all();
		
		$rules = array(
            'first_name'       		=> 'required',
            'last_name'      		=> 'required',			
            'username'      		=> 'required|min:6|alpha_dash|unique:users',
            'email' 				=> 'required|email|unique:users',
            'city'      			=> 'required',
            'country'      			=> 'required',
			//'zip' 					=> 'regex:/\b\d{5}\b/',
            //'phone' 				=> 'regex:/[0-9]{9}/',
			'lat'					=> 'required',
			'lng'					=> 'required',
            'user_type'      		=> 'required|in:1,2',
            'device_type' 			=> 'required',
            'device_token' 			=> 'required',
            'auth_token'      		=> 'required',
			'login_type'			=> 'required|in:facebook,google',	
			
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			
			return response(array(
					'success' => false,
					'error' => false,
					'message' =>'Unable to add user record',
					'errorDtl' => $validator->errors()->all()
				), 200);
				
		} else {
		
			try {
				
				$pass = str_random(6);

				$user = new User;
				$user->first_name       	= $input['first_name'];
				$user->last_name      		= $input['last_name'];
				$user->username 			= $input['username'];
				$user->password 			= bcrypt($pass);
				$user->email 				= $input['email'];
				
				if(isset($input['address']))
					$user->address 				= $input['address'];			// Applicable for Business Users
				
				if(isset($input['phone']))
					$user->phone 				= $input['phone'];
				
				if(isset($input['country']))
					$user->country 				= $input['country'];					// Applicable for Business Users
				
				if(isset($input['state']))
					$user->state 			= $input['state'];
				
				if(isset($input['city']))
					$user->city 			= $input['city'];
				
				if(isset($input['zip']))				
					$user->zip 				= $input['zip'];
				
				if(isset($input['website']))				
					$user->website 			= $input['website'];				// Applicable for Business Users
				
				$user->lat 					= $input['lat'];				
				$user->lng 					= $input['lng'];				
				$user->user_type 			= $input['user_type'];				// 1=Users | 2=Businesses
				
				if($input['user_type'] == 2)				
					$user->business_name = $input['business_name'];
					
				$user->user_role 			= "user";
				$user->device_type 			= $input['device_type'];
				$user->device_token 		= $input['device_token'];
				$user->active 				= '1';
				
				if($input['login_type'] == "facebook") {
					$user->fb_auth_token = $input['auth_token'];
				}
			
				else if($input['login_type'] == "google"){
					$user->google_auth_token = $input['auth_token'];
				}
				
				if($user->save()) {

					$this->followBulkUpdate();
					
					// Auto Follow all Administrators

					/*$admins = User::where('user_role', 'admin')->get();
					foreach($admins as $admin) {
						
						$follower = new Follower;
						$follower->follower_id       	= $user->id;
						$follower->following_id       	= $admin->id;	
						$follower->save();

					}*/
					
					return response(array(
						'success' => true,
						'message' =>'User successfully registered',
						'user'	=> $user
					), 200);
					
				}
				else {
					
					Log::error(print_r($user, true));
					
					return response(array(
						'success' => false,
						'message' =>'Unable to save user record'
					),200);
				}
			
			} catch (\Illuminate\Database\QueryException $e) {
				
				Log::error(print_r($user, true));
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.: '.$e
					),200);
			}
		}
	}
	public function followBulkUpdate()
    {
		
		// Auto Follow all Administrators
		$admins = User::where('user_role', 'admin')->select('id')->get();
		$users = User::select('id')->where('user_type', '1')->get();

		foreach($users as $user) {

			foreach($admins as $admin) {
				/*if($ids != "") {
					$ids = ",";

				}
				$ids .= $admin->id;*/
				$cnt = Follower::where('follower_id', $user->id)->where('following_id', $admin->id)->select('id')->count();

				if($cnt == 0) {
					$follower = new Follower;
					$follower->follower_id       	= $user->id;
					$follower->following_id       	= $admin->id;	
					$follower->save();
				}
			}

		}
		
	}
	
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'first_name'       		=> 'required',
            'last_name'      		=> 'required',
            'email' 				=> 'unique:users,email,'.$request->id,
            //'phone' 				=> 'required|regex:/[0-9]{9}/',
            'user_type'      		=> 'required|in:1,2',
            //'device_type' 			=> 'required',
            //'device_token' 			=> 'required',
            'country' 				=> 'required',
			
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
					'success' => false,
					'message' =>'Unable to update user record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
			
				$user = User::find($input['id']);
				
				
				if($user != null) {
					
					$user->update($request->all());
					 
					return response(array(
						'success' => true,
						'message' =>'User has been updated',
					   ),200);
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'Unable to find user record',
						'errorDtl' => $validator->errors()->all()
					),200);
				}
				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
    }
	
	public function updateStatus(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'id'  		=> 'required',
            'active'  	=> 'required',
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
					'success' => false,
					'message' =>'Unable to update user record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
			
				$user = User::find($input['id']);
				
				
				if($user != null) {
					
					$user->update($request->all());
					 
					return response(array(
						'success' => true,
						'message' =>'User has been updated',
					   ),200);
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'Unable to find user record',
						'errorDtl' => $validator->errors()->all()
					),200);
				}
				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
	}
	

	public function resetPass(Request $request)
	{
		//mail("dibs439@gmail.com", "Hello", "Test from Looksyummy");
		$input = $request->all();

		$rules = array(
            'value'  => 'required',
		);
		
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
				'success' => false,
				'message' =>'Search key not defined',
				'errorDtl' => $validator->errors()->all()
			),200);
			
		}

		$plain_password = str_random(8);
		$user = User::where('username', $input['value'])->orWhere('email', $input['value'])->first();
		//dd($user->email);

		if(isset($user->id)) {

			if(isset($user->email)) {
				$input = [
			
					'password' => Hash::make($plain_password),			
					//'force_password_reset' => 1,
				];
		
				User::where('id', $user->id)->update($input);

				$user['plain_password'] = $plain_password;
				//dd($user->email);
				Mail::to($user->email)->send(new ResetPasswordMail($user));
	
				return response(array(
					'success' => true,
					'message' =>'Password has been emailed to you.',
				   ),200);
				   
			}
			else {
				return response(array(
					'success' => false,
					'message' =>'No email address is associated with this account',
					'errorDtl' => $validator->errors()->all()
				),200);
			}

			

			//return redirect('/backend/users')->with('success', __('New password successfully sent to th user. Password is '.$plain_password));
				
		}
		else {
			return response(array(
				'success' => false,
				'message' =>'Unable to find user record',
				'errorDtl' => ''
			),200);

		}
		
		
	}
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateType(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'id'       			=> 'required',
            'user_type'      	=> 'required|in:1,2', // 1 = Customer, 2=Business			
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
					'success' => false,
					'message' =>'Unable to update user record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
		try {
			
				$user = User::find($input['id']);
				$input['active'] = "1";
				
				if($user != null) {
					$user->update($input);
					return response(array(
						'success' => true,
						'message' =>'User successfully updated',
					   ),200);
				}
				else {
					return response(array(
					'success' => false,
					'message' =>'Unable to find user record',
					'errorDtl' => $validator->errors()->all()
				),200);
				}
				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
    }
	
	
	
	/**
     * Returns user details for a specific id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'id' => 'numeric|nullable',
            'self_id' => 'numeric',
            'email' => 'email|nullable',
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			return response(array(
					'success' => false,
					'message' =>'Unable to fetch user record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
				$user = array();
				if(isset($input['id']))
					$user = User::where('id', $input['id'])->with(array('followersCount', 'followingsCount', 'mealsCount', 'restaurant'))->first();
				else if(isset($input['email']))
					$user = User::where('email', $input['email'])->with(array('followersCount', 'followingsCount', 'mealsCount', 'restaurant'))->first();
				
				
				if(isset($user->id)) {
					
					$path = env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max');
					
					if(isset($user['profile_pic']) && $user['profile_pic'] != "")
						$user['profile_pic'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$user['profile_pic']);
					else
						$user['profile_pic'] = NULL;

					
					
					$is_following = Follower::where('follower_id', $input['self_id'])->where('following_id', $input['id'])->count();
					
					
					$user['is_following'] = $is_following;

					if(isset($user->followersCount[0]->aggregate)) {
						$user['count_followers'] = $user->followersCount[0]->aggregate;	
					}
					else {
						$user['count_followers'] = 0;
					}

					
					if(isset($user->followingsCount[0]->aggregate)) {
						$user['count_followings'] = $user->followingsCount[0]->aggregate;	
					}
					else {
						$user['count_followings'] = 0;
					}
					
					/*
					// If it is a business user then bring the restaurant details
					if(isset($user['user_type']) && $user['user_type'] = 2) {
						$restaurant = Restaurant::where('id', $input['id'])->first();
					}*/					
					
					if($user != null) {
						return response(array(
							'success' => true,
							'message' =>'User details found',
							'user'	  => $user
						   ),200);
					}
					else {
						return response(array(
							'success' => false,
							'message' =>'Unable to find user record',
							'errorDtl' => $validator->errors()->all()
						),200);
					}
					
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'User does not exist'
					),200);
					
				}
				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
    }
	

	public function edit($id)
    {		
        $user = User::find($id);
		//dd($user);
        // Redirect to users list if updating user wasn't existed
        if (!isset($user->id)) {
            return redirect()->intended('/admin/users');
        }

        return view('admin/users/edit', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, $id)
    {
		//dd($request);
		$err = 0;
        $rules = [
            //'first_name' => 'required',			
			//'last_name' => 'required',
			//'email' => 'required',
			//'username' => 'required|unique:users',
			'username' => 'required',
        ];

        $this->validate($request, $rules);
		
	
		
		$input = [
			'first_name' => $request['first_name'],
			'last_name' => $request['last_name'],
			'email' => $request['email'],
			'username' => $request['username'],
			'business_name' => $request['business_name'],
			'show' => $request['show'],
			'active' => $request['active'],
		];
		
		
		// Check duplicate username

		$cntUser1 = User::where('username', $input['username'])->where('id', '!=', $id)->count();
		$cntUser2 = 0;
		if($input['email'] != "")
			$cntUser2 = User::where('email', $input['email'])->where('id', '!=', $id)->count();
		//dd($cntUser);

		if($cntUser1 > 0) {
			//Session::flash('message', 'Duplicate username exists');
			
			return redirect()->back()->with('error', __('Duplicate username exists'));
			

		}
		else if($cntUser2 > 0) {
			return redirect()->back()->with('error', __('Duplicate email exists'));
			
		}
		else {
			User::where('id', $id)->update($input);
		}
		

		
	
		
		if($err == 0)
			return redirect()->intended('/admin/users')->with('success', __('User successfully updated'));
		else
			return redirect()->intended('/admin/users')->with('error', __('Unable to update user'));
    }
	
	
	/**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editPass(Request $request, $id)
    {	
	
		if($request->password != "") {
		
			$rules = [
				'password' => 'required|between:5,12|confirmed',
			];

			$this->validate($request, $rules);
			
			$user = User::where('id', $id)->first();
		
			
			$user->password = Hash::make($request->password);
			$user->save();
			return redirect()->intended('/backend/users')->with('success', __('User password successfully updated'));
			
		}
		
        $user = User::find($id);
		
		// Redirect to users list if updating user wasn't existed
        if (isset($user->id)) {
            return redirect()->intended('/backend/users');
        }

        return view('users/password', ['user' => $user]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storePass()
    {
		
		$err = 0;
        $rules = [
            'password' => 'hash:' . $request->password,
			'new_password' => 'required|different:password|confirmed'
        ];

        $this->validate($request, $rules);
		
		if ($validation->fails()) {
			return redirect()->back()->withErrors($validation->errors());
		}

		$user = User::where('id', $id)->first();
		
		
		$user->password = Hash::make(Request::input('new_password'));
		$user->save();

		return redirect()->back()
		->with('success-message', 'Your new password is now set!');
		
		return redirect()->intended('/backend/users')->with('success', __('User successfully updated'));
    }

	public function logout(Request $request)
    {
		$input = $request->all();
        $rules = [
            'id' => 'required|numeric',
        ];

		
		$validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			return response(array(
				'error' => false,
				'message' =>'Unable to logout',
				'errorDtl' => $validator->errors()->all()
			), 200);
				
		} else {
		
			try {

				$cntUser1 = User::where('id', $input['id'])->count();

				
				if($cntUser1 == 0) {
					return response(array(
						'error' => false,
						'message' =>'User does not exists',
						'errorDtl' => $validator->errors()->all()
					), 200);
				}
				$input1 = [
					'device_token' => NULL,
					'device_type' => NULL,
					'remember_token' => NULL,
				];

				User::where('id', $input['id'])->update($input1);

				return response(array(
					'success' => true,
					'message' =>'You are successfully logged out'
				   ),200);


			} catch (\Illuminate\Database\QueryException $e) {
						
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.: '.$e
					),200);
			}
		}

    }

    public function postLogin(Request $request)
    {
    	
    }
	
	public function login(Request $request)
    {

		$input = $request->all();
		$rules = array(
            'username' 				=> 'required',
            'password' 				=> 'required',			
        );

		
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
		 
			return response(array(
				'error' => false,
				'message' =>'Unable to login',
				'errorDtl' => $validator->errors()->all()
			),200);
				
		} else {
		
			try {
			
				$userdata = array(
					'username'     	=> $input['username'],
					'password'  	=> $input['password'],
					'active'  		=> '1',
				);

				
				if (Auth::attempt($userdata)) {
				
					$user = User::where('username', $input['username'])->first();

					if(isset($input['device_type']) && $input['device_type'] != "" && isset($input['device_type']) && $input['device_type'] != "" ) {
						$userdata2 = array(
							'device_type' 	=> $input['device_type'],
							'device_token' 	=> $input['device_token'],
						);

						User::where('id', $user->id)->update($userdata2);
					}
					

					// validation successful!
					// redirect them to the secure section or whatever
					// return Redirect::to('secure');
					// for now we'll just echo success (even though echoing in a controller is bad)
					//echo 'SUCCESS!';

					if(isset($user['profile_pic'])) {
						$user['profile_pic'] = env('PROFILE_PIC_THUMB').$user['profile_pic'];
					}
					
					return response(array(
							'success' => true,
							'message' =>'Login successful',
							'user' => $user
						),200);
						

				} 
				else {
					return response(array(
						'success' => false,
						'message' =>'Login and password combination does not match or your account is inactive'
					),200);
				}
					
				
				if(count($user) > 0) {
				
					if (Auth::attempt($userdata)) {

						// validation successful!
						// redirect them to the secure section or whatever
						// return Redirect::to('secure');
						// for now we'll just echo success (even though echoing in a controller is bad)
						//echo 'SUCCESS!';
						
						return response(array(
							'success' => true,
							'message' =>'Login and password combination does not match',
							'user' => $validator->errors()->all()
						),200);

					} 
					else {
						return response(array(
							'success' => false,
							'message' =>'Login and password combination does not match'
						),200);
					}
				
					
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'Email does not exist'
					),200);
				}
			
			} catch (\Illuminate\Database\QueryException $e) {
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.: '.$e
					),200);
			}
		}
	}
	
	public function upload(Request $request)
    {
		
		$rotation = 0;
		$input = $request->all();
		
    	$this->validate($request, [
    		'id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

		$user = User::find($input['id']);
		
		
		if($user != null) {

			$file = $request->file('image');
			$name = time() .".". $file->getClientOriginalExtension();
			$input['profile_pic'] = env('PROFILE_PIC_THUMB').$name;
			$filePath = env('PROFILE_PIC_UPLOAD_PATH') . $name;
			
			if(Storage::disk('s3')->put($filePath, file_get_contents($file))) {
				
				//dd($destinationPath.$photo);
				//$rotation = $this->correctImageOrientation($destinationPath.$photo);

				$user->profile_pic = $name;
				$user->profile_pic_rotation = $rotation;
				$user->save();
				
				return response(array(
						'success' => true,
						'message' =>'Profile picture successfully uploaded',
						'user'	  => $input
					   ),200);
			}
			
			/*
			$input['profile_pic'] = time().'.'.$request->image->getClientOriginalExtension();
			$destinationPath = public_path(env('PROFILE_PIC_MAX', '/storage/photos/profiles/max'));
			$destinationURL = env('APP_URL').env('PROFILE_PIC_MAX', '/storage/photos/profiles/max');
			//dd($destinationURL."/".$input['profile_pic']);


			
			

			
			if($request->image->move($destinationPath, $input['profile_pic'])) {

				//dd($destinationURL."/".$input['profile_pic']);

				//$rotation = $this->correctImageOrientation($destinationURL."/".$input['profile_pic']);
				//dd($rotation);
				
				$user->profile_pic = $input['profile_pic'];
				$user->profile_pic_rotation = $rotation;
				$user->save();
				
				return response(array(
						'success' => true,
						'message' =>'Profile picture successfully uploaded',
						'user'	  => $input
					   ),200);
			
			}*/
			
			return response(array(
				'success' => false,
				'message' => 'Unable to upload image'
				),200);
				
		}
		else {
			return response(array(
				'success' => false,
				'message' => 'Unable to process request, user does not exists'
				),200);
		}

    }
	
	
	public function follow(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'following_id'       	=> 'required|numeric',
            'user_id'      			=> 'required|numeric'			
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
					'error' => false,
					'message' =>'Unable to follow the user',
					'errorDtl' => $validator->errors()->all()
				),200);
				
		} else {
		
			try {
				
				$following_already = Follower::where('follower_id', $input['user_id'])->where('following_id', $input['following_id'])->count();
				
				if($following_already == 0) {
					
					$follower = new Follower;
					$follower->follower_id       		= $input['user_id'];
					$follower->following_id      		= $input['following_id'];	


					
					if($follower->save()) {
						$follower_count = 0;
						$follower_count = Follower::where('following_id', $input['following_id'])->count();
				
						
						// Add to notifications table
						$notification = new Notification;
						$notification->sender_id      	= $input['user_id'];
						$notification->recipient_id   	= $input['following_id'];
						$notification->notify_type		= 3; // 3=Follow
						$notification->save();
						
						// Send Push Notification
						app('App\Http\Controllers\MessageController')->send(3, $input['following_id'], $input['user_id']);
						
						return response(array(
							'success' => true,
							'follower_count'	=> $follower_count,
							'message' =>'User are now following this user',
						),200);
						
					}
					else {
						return response(array(
							'success' => false,
							'message' =>'Unable to follow the user'
						),200);
					}
					
					
				}
				else {
					return response(array(
							'success' => false,
							'message' =>'You are already following this user'
						),200);
				}
				

			
			} catch (\Illuminate\Database\QueryException $e) {
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.: '.$e
					),200);
			}
		}
	}
	
	public function unfollow(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'following_id'       	=> 'required|numeric',
            'user_id'      			=> 'required|numeric'			
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
					'error' => false,
					'message' =>'Unable to unfollow the user',
					'errorDtl' => $validator->errors()->all()
				),200);
				
		} else {
		
			try {
				
				$follower = Follower::where('follower_id', $input['user_id'])->where('following_id', $input['following_id'])->first();
				
				if(isset($follower['id']) && $follower['id'] != "") {
					
					if($follower->delete()) {
						$follower_count = 0;
						$follower_count = Follower::where('following_id', $input['following_id'])->count();
				
						return response(array(
							'success' => true,
							'follower_count'	=> $follower_count,
							'message' =>'You have unfollowed this user',
						),200);
						
					}
					else {
						return response(array(
							'success' => false,
							
							'message' =>'Unable to unfollow the user'
						),200);
					}
					
					
				}
				else {		
					
					return response(array(
							'success' => false,
							'message' =>'You are not following this user'
						),200);					
				}
				
			
			} catch (\Illuminate\Database\QueryException $e) {
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred.: '.$e
					),200);
			}
		}
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function followings(Request $request)
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
			
			
			$followings = Follower::where('follower_id', $input['user_id'])->with(
				array('user'=>function($query){
					$query->select('id','username', 'profile_pic', 'profile_pic_rotation')->with('followersCount');
				})
			)->get();
			
			if(isset($followings) && $followings->count()) {
				
				foreach($followings as $following) {
					if(isset($following->user['profile_pic']) && $following->user['profile_pic'] != "") {
						$following['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$following->user['profile_pic']);
						
					}
					else
						$following['user']['profile_pic_url'] = null;
					
					unset($following->user['profile_pic']);
				}
				
	
				return response(array(
					'success' => true,
					'users' =>$followings->toArray(),
				   ),200); 
				}
			else {
				return response(array(
					'success' => false,
					'message' => 'You are not following anyone.'
				),200);
			}
		}

		
	}
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function followers(Request $request)
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
			
			
			$followers = Follower::where('following_id', $input['user_id'])->with(
				array('follower'=>function($query){
					$query->select('id','username', 'profile_pic', 'profile_pic_rotation')->with('followersCount');
				})
			)->get();
			
			/*return response(array(
					'success' => true,
					'followings' =>$followers->toArray(),
				   ),200);*/
			
			if(isset($followers) && $followers->count()) {
				
				foreach($followers as $follower) {
					
					
					if(isset($follower['follower'][0]['profile_pic']) && $follower['follower'][0]['profile_pic'] != "") {
						
						$follower['follower'][0]['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$follower['follower'][0]['profile_pic']);
						
												
					}
					else
						$follower['follower'][0]['profile_pic_url'] = null;
					
					unset($follower['follower'][0]['profile_pic']);
				}
				
				$followersAList = $followers->toArray();
				$i = 0;
				for($i = 0; $i < count($followersAList); $i++) {
					$followersAList[$i]['user'] = $followersAList[$i]['follower'];
					$followersAList[$i]['follower'] = NULL;
					unset($followersAList[$i]['follower']);
					
				}
	
				return response(array(
					'success' => true,
					'users' =>$followersAList,
				   ),200); 
			}
			else {
				return response(array(
					'success' => false,
					'message' => 'You are not following anyone.'
				),200);
			}
		}

		
	}
	
	
	public function changePassword(Request $request)
    {
		
		
		$input = $request->all();
	
		$rules = [
			'current_password' => 'required|current_password',
			'new_password' => 'required|string|min:6|confirmed',
		];
		
		$validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			
			
			return response(array(
					'error' => false,
					'message' =>'Unable to add user record',
					'errorDtl' => $validator->errors()->all()
				),200);
		}
		else {
			$user = User::where('id', $input['id'])->first();
			
			if($user != null) {
			
				if(request()->user()->fill([
					'password' => Hash::make(request()->input('new_password'))
				])->save()) {
					return response(array(
								'success' => true,
								'message' =>'User password successfully updated',
								'user'	  => $input
							   ),200);
				}
				else {
					return response(array(
						'success' => false,
						'message' => 'Unable to update password'
						),200);
				}
			
			}
			else {
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, user does not exists'
					), 200);

			}
		}
		
		
	}
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUsername(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'id'       		=> 'required|numeric',			
            'username'      => 'required|min:6|alpha_dash',
			
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
					'success' => false,
					'message' =>'Unable to update user record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
			
				$user = User::find($input['id']);
				
				
				if($user != null) {
					
					$cntUser = User::where('username', $input['username'])->count();
					//dd($user);
					//echo $user['id'];
					
					if($cntUser == 0) {
						
						$user->update($request->all());
					 
						return response(array(
							'success' => true,
							'message' =>'Username has been updated',
						   ),200);
					}
					else {
						return response(array(
							'success' => false,
							'message' =>'Username is already taken'
						),200);
					}
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'Unable to find user record',
						'errorDtl' => $validator->errors()->all()
					),200);
				}
				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
    }
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePass(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'id'       				=> 'required|numeric',
            'password' 				=> 'required|between:6,12',
            'new_password' 			=> 'required|between:6,12',
			'confirm_password' 		=> 'required|same:new_password',
			
        );
		$validator = Validator::make($input, $rules);
		
		
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
				'success' => false,
				'message' =>'Unable to update user record',
				'errorDtl' => $validator->errors()->all()
				),200);
				
		}
		else {
			
			try {
				$user = User::where('id', $input['id'])->first();

				if(Hash::check($input['password'], $user['password'])) {
					// Right password
					$input2['password'] = Hash::make($input['new_password']);		
					$user->update($input2);

					
					return response(array(
						'success' => true,
						'message' =>'Your password has been successfully updated',
					),200);
				

				} else {
					
					return response(array(
						'success' => false,
						'message' =>'Your current password does not match with our record'
					),200);

				}


				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
	}
	

	
	
	
	public function recentlyActiveUsers(Request $request)
    {		
		$keyword = "";
		$input = $request->all();
		$id = Auth::user('id');
	
		try {

			/*
			// Currently commented out
			$users = Meal::select('posted_by')->orderBy('created_at', 'desc')->distinct()->with(
				array('user'=>function($query){
					$query->select('id','username', 'profile_pic');
				}))->take(50)->get();*/

			// ->whereNotIn('id', [$id])
			$users = User::where('active', '1')->where('user_type', '1')->with('followersCount')->orderBy('created_at', 'desc')->take(50)->get();
			
			
			if(isset($users)) {
				
				foreach($users as $user) {
					if($user->id != $id) {

						//dd($user['profile_pic']);
						if(isset($user['profile_pic']) && $user['profile_pic'] != "") {
							$user['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$user['profile_pic']);
							unset($user['profile_pic']);
							
						}
					}
					
				}
				
	
				return response(array(
					'success' => true,
					'users' =>$users->toArray(),
				   ),200); 
			}
			else {
				return response(array(
					'success' => false,
					'message' => 'No active users found.'
				),200);
			}
			
			 
			   
		} catch (\Illuminate\Database\QueryException $e) {
			return response(array(
				'success' => false,
				'message' => 'Unable to process request, database error occurred. '.$e
				),200);
		}
		
				   
    }
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDeviceToken(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'id'       		=> 'required|numeric',			
            'device_token'  => 'required',
			
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
					'success' => false,
					'message' =>'Unable to update user record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
			
				$user = User::find($input['id']);
				
				
				if($user != null) {
					
					
						$user->update($request->all());
					 
						return response(array(
							'success' => true,
							'message' =>'User device token has been updated',
						   ),200);
					
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'Unable to find user record',
						'errorDtl' => $validator->errors()->all()
					),200);
				}
				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
    }
	
	
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function block(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'user_id'       		=> 'required|numeric',			
            'blocked_user_id'  		=> 'required|numeric',
			
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
					'success' => false,
					'message' =>'Unable to block user',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
			
				$user1 = User::find($input['user_id']);
				$user2 = User::find($input['blocked_user_id']);
				
				
				
				if($user1 != null && $user2 != null) {
					
				
					$cnt_blocked = BlockedUser::where('user_id', $input['user_id'])->where('blocked_user_id', $input['blocked_user_id'])->count();
					
					
					if($cnt_blocked > 0) {
						
						return response(array(
						'success' => false,
						'message' =>'You have alraedy blocked this user',
					   ), 200);
						
					}
					
					
					$blkUser = new BlockedUser;
					$blkUser->user_id      			= $input['user_id'];
					$blkUser->blocked_user_id    	= $input['blocked_user_id'];
					
					if($blkUser->save()) {
						return response(array(
						'success' => true,
						'message' =>'User has been successfully blocked',
					   ),200);
					}
					else {
						return response(array(
							'success' => false,
							'message' =>'Unable to block user',
						),200);
					}
					
				 
					
				
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'Any of the users does not exist in our record',
					),200);
				}
				
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return response(array(
					'success' => false,
					'message' => 'Unable to process request, database error occurred. '.$e
					),200);
			}
		}
    }


	public function analytics(Request $request)
	{
		if(isset($request->id) && $request->id != "") {

			$id = $request->id;
			$ret = $this->getAnalyticsData($id);

			if(isset($ret["likes"]) || isset($ret['comments']))
				return view('admin/users/analytics', ['meals1' => $ret['likes'], 'meals2' => $ret['comments'], 'id' => $id]);
			else
				return "No meal found";


		}
		
	}
	

	// Export to Excel
	
    public function exportExcel(Request $request) {

    	
        $this->prepareExportingData($request)->export('xlsx');
        redirect()->intended('users')->with('success', __('Successfully extported excel'));;
    }
	
	private function prepareExportingData($request) {

		$id = $request->id;
		//dd($id);
        $author = Auth::user()->username;
        $meals = $this->getAnalyticsData($id);
        
        $likes = $meals['likes'];
        $comments = $meals['comments'];

		foreach ($likes as $like) {
            
            $payload['likes'][] = array('code' => $like->code, 'title' => $like->title, 'photo' => $like->photo, 'likesCount' => $like['likesCount'][0]['aggregate']);
        }


		foreach ($comments as $comment) {
            
            $payload['comments'][] = array('code' => $comment->code, 'title' => $comment->title, 'photo' => $comment->photo, 'likesCount' => $comment['commentsCount'][0]['aggregate']);
        }


        
        return Excel::create('analytics', function($excel) use($payload, $request, $author) {

        // Set the title
        $excel->setTitle('Anaytics');

        // Chain the setters
        $excel->setCreator($author)
            ->setCompany('Looksyummy');

        // Call them separately
        $excel->setDescription('Anaytics information');

        $excel->sheet('Likes', function($sheet) use($payload) {
        	
        	$sheet->fromArray($payload['likes']);
        });


        $excel->sheet('Comments', function($sheet) use($payload) {
        	
        	$sheet->fromArray($payload['comments']);
        });

        



        });
    }
	
	
    private function getExportingData() {
    	//dd(User::all());
        return User::all();
    }

    private function getAnalyticsData($id) {


			$user1 = User::where('id', $id)->with(
					
				array('meals'=>function($query){
						$query->with(
							array('likesCount'=>function($query1) {
								$query1->orderBy('aggregate', 'desc');
							})
						);
						
						
						//$query->orderBy('likesCount', 'desc');
					})

				)->first();


			$user2 = User::where('id', $id)->with(
					
				array('meals'=>function($query){
						$query->with(
							array('commentsCount'=>function($query) {
								$query->orderBy('aggregate', 'desc');
							})
						);
						
					})

				)->first();

			

			$likes = $user1['meals'];
			$comments = $user2['meals'];
			//dd($comments);
			
			$ret['likes'] = $likes;
			$ret['comments'] = $comments;
			//dd($ret);
			return $ret;


			
    }
	
	
	
	public function delete(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'user_id' 				=> 'required|numeric'
        );

		
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
		 
			return response(array(
				'error' => false,
				'message' =>'Unable to find user',
				'errorDtl' => $validator->errors()->all()
			),200);
				
		} else {
		
			try {

				$cnt = User::where('id', $input['user_id'])->count();
				
				$meals = Meal::where('posted_by', $input['user_id'])->get();
				
				if(count($meals) > 0) {
					foreach($meals as $meal) {
						$meal_id = $meal->id;
						Like::where('meal_id', $meal_id)->delete();					
						Comment::where('meal_id', $meal_id)->delete();					
						Notification::where('meal_id', $meal_id)->delete();					
						FlaggedMeal::where('meal_id', $meal_id)->delete();
					}
					
					Meal::where('posted_by', $input['user_id'])->delete();
				
				}

				if($cnt > 0) {

					BlockedUser::where('user_id', $input['user_id'])->orWhere('blocked_user_id', $input['user_id'])->delete();

					Comment::where('user_id', $input['user_id'])->delete();

					FlaggedMeal::where('user_id', $input['user_id'])->delete();

					Follower::where('following_id', $input['user_id'])->orWhere('follower_id', $input['user_id'])->delete();

					Like::where('user_id', $input['user_id'])->delete();

					Notification::where('sender_id', $input['user_id'])->orWhere('recipient_id', $input['user_id'])->delete();

					Restaurant::where('user_id', $input['user_id'])->delete();
					User::find($input['user_id'])->delete();


					return response(array(
							'success' => true,
							'message' =>'User has been successfully deleted',
						   ),200);
				}
				else {

					return response(array(
						'error' => false,
						'message' =>'User does not exist'
					),200);
				}
			
				

			
			} catch (\Illuminate\Database\QueryException $e) {
				
				Log::debug('Unable to process request, database error occurred.: '.$e);
				
				return response(array(
					'success' => false,
					'message' => 'Unable to process this request. Please try again later. '.$e
					),200);
			}
		}
	}
	
	public function bulkUpdateUsername(Request $request)
    {
		$users = User::where('user_type', 2)->where('username', '!=', NULL)->get();

		$nctr = 0;
		foreach($users as $user) {
			$count_username = 0;
			if(isset($user['business_name'])) {
				$username = preg_replace("/[ ]+/", ".", $user['business_name']);

				$username = strtolower(preg_replace("/[^a-zA-Z]+/", ".", $username));
				
				if(strlen($username) > 45) {
					$username = substr($username, 0, 45);
				}
				

				$ctr = 0;
				do {
					if($ctr > 0) {
						$rand = mt_rand(0,100);
						$username = $username."".$rand;
					}
					$count_username  = User::where('username', $username)->count();
					$ctr++;
				} while($count_username == 1);

				$input['username'] = $username;
				$user->update($input);
			}
			else {
				$input['username'] = str_random(6);
				$user->update($input);
			}		
			$nctr++;
		}

		echo "Done. ".$nctr." record(s) updated.";
	}
	
	public function bulkDeleteFollowers(Request $request)
    {
		$followers = Follower::get();

		$nctr = 0;
		foreach($followers as $follower) {
			
			$user_id = $follower->follower_id;
			$user = User::where('id', $user_id)->first();
			if($user->user_type == 2) {
				$follower->delete();
			}
			
		}

		echo "Done. ".$nctr." record(s) deleted.";
	}


	
	public function bulkDeleteInactiveBizUsers()
    {
		$users = User::where('active', '0')->where('user_type', '2')->select('id')->get();
		

		$nctr = 0;
		foreach($users as $user) {
			echo $user->id."\n";
			$meals = Meal::where('posted_by', $user->id)->get();
			print_r($meals);
			if(count($meals) > 0) {
				foreach($meals as $meal) {
					$meal_id = $meal->id;
					Like::where('meal_id', $meal_id)->delete();					
					Comment::where('meal_id', $meal_id)->delete();					
					Notification::where('meal_id', $meal_id)->delete();					
					FlaggedMeal::where('meal_id', $meal_id)->delete();
				}
				

			
			}

			
			Restaurant::where('user_id', $user->id)->delete();
			Meal::where('posted_by', $user->id)->delete();

			BlockedUser::where('user_id', $user->id)->orWhere('blocked_user_id', $user->id)->delete();

			Comment::where('user_id', $user->id)->delete();

			FlaggedMeal::where('user_id', $user->id)->delete();

			Follower::where('following_id', $user->id)->orWhere('follower_id', $user->id)->delete();

			Like::where('user_id', $user->id)->delete();

			Notification::where('sender_id', $user->id)->orWhere('recipient_id', $user->id)->delete();

			User::find($user->id)->delete();
			$nctr++;

			
			
		}

		echo "Done. ".$nctr." record(s) deleted.";
	}
	
	
	public function destroy($id)
    {
		
	
		try {

			$user = User::where('id', $id)->first();

			$meals = Meal::where('posted_by', $id)->get();

			if(count($meals) > 0) {
				foreach($meals as $meal) {
					$meal_id = $meal->id;
					Like::where('meal_id', $meal_id)->delete();					
					Comment::where('meal_id', $meal_id)->delete();					
					Notification::where('meal_id', $meal_id)->delete();					
					FlaggedMeal::where('meal_id', $meal_id)->delete();
				}
				
				Meal::where('posted_by', $id)->delete();
			
			}

			if(isset($user->id)) {

				BlockedUser::where('user_id', $id)->orWhere('blocked_user_id', $id)->delete();

				Comment::where('user_id', $id)->delete();

				FlaggedMeal::where('user_id', $id)->delete();

				Follower::where('following_id', $id)->orWhere('follower_id', $id)->delete();

				Like::where('user_id', $id)->delete();

				
				Restaurant::where('user_id', $id)->delete();
				
				Notification::where('sender_id', $id)->orWhere('recipient_id', $id)->delete();
				
				if($user->delete()) {
					return redirect()->intended('/admin/users');
				}
				else {

					return redirect()->back()->with('error', __('Unable to delete user.'));
				}
				
				
			}
			else {

				return redirect()->back()->with('error', __('Unable to delete user.'));
			}
		
			

		
		} catch (\Illuminate\Database\QueryException $e) {
			
			Log::debug('Unable to process request, database error occurred.: '.$e);
			return redirect()->back()->with('error', __('Unable to delete user.'));
		}
	
	}

}

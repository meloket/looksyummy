<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Hash;
use File;
use DB;
use Log;
use Session;
use Exception;
use Carbon\Carbon;
use App\User;
use App\Comment;
use App\Like;
use App\Meal;
use App\FlaggedMeal;
use App\MealCategory;
use App\Restaurant;
use App\Follower;
use App\Notification;
use SKAgarwal\GoogleApi\PlacesApi;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class MealController extends Controller
{
	
	/*****************  Admin Functions Starts *****************/

	public function index(Request $request)
	{

		
		$keyword = "";
		$meal_category_id = "";

		if($request->clear == 1) {
			$request->session()->forget('sess_filter_meal_keyword');
			$request->session()->forget('sess_filter_meal_category_id');
		}
		
		if(isset($request->keyword)) {
			$request->session()->put('sess_filter_meal_keyword', $request->keyword);
		}

		if(isset($request->meal_category_id)) {
			$request->session()->put('sess_filter_meal_category_id', $request->meal_category_id);
		}

		if ($request->session()->has('sess_filter_meal_keyword')) {
		    $keyword = $request->session()->get('sess_filter_meal_keyword');
		}

		if ($request->session()->has('sess_filter_meal_category_id')) {
		    $meal_category_id = $request->session()->get('sess_filter_meal_category_id');
		}

		$meal_categories = MealCategory::pluck('name', 'id');	

		//$meals = Meal::with('restaurant', 'user', 'mealCategory')->orderBy('created_at', 'desc')->paginate(15);


		$meals = Meal::keyword($keyword)->mealCategoryId($meal_category_id)->with('restaurant', 'user', 'mealCategory')->orderBy('created_at', 'desc')->paginate(15);
		//dd($meals);

		return view('admin/meals/index', ['meals' => $meals, 'meal_categories' => $meal_categories, 'keyword' => $keyword, 'meal_category_id' => $meal_category_id]);	
	}


	/**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
			$restaurant_id = '';
			
			if ($request->session()->has('sess_restaurant_id')) {
				$restaurant_id = $request->session()->get('sess_restaurant_id');
			}

			$meal_categories = MealCategory::pluck('name', 'id');	
			$restaurants = Restaurant::where('active', '1')->where('place_name', '!=', '')->orderBy('place_name', 'asc')->pluck('place_name', 'id');
        return view('admin/meals/create', ['meal_categories' => $meal_categories, 'restaurants' => $restaurants, 'restaurant_id' => $restaurant_id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
			$restaurant_id = '';
			
			if ($request->session()->has('sess_restaurant_id')) {
				$restaurant_id = $request->session()->get('sess_restaurant_id');
			}

			$userId = Auth::user()->id;

			$rules = [
							'meal_category_id' => 'required|numeric',
							'restaurant_id' => 'required|numeric',
							'title' => 'required',
							'photo1' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
					];

			$this->validate($request, $rules);

			$input = [
				'code'								=> strtoupper(unique_random('meals', 'code', 6)),
				'meal_category_id' 		=> $request['meal_category_id'],
				'restaurant_id' 			=> $request['restaurant_id'],
				'title' 							=> $request['title'],
				'description'					=> $request['description'],
				'amount'							=> $request['amount'],
				'posted_by' 					=> $userId,
				'active' 							=> 1
			];

			$restaurant = Restaurant::where('id', $request['restaurant_id'])->with('user')->first();

			if(isset($restaurant->user_id)) {
				
				$input['posted_by'] = $restaurant->user_id;

				if($request->file('photo1'))
				{
					$image = $request->file('photo1');
					
					$ext = strtolower($image->getClientOriginalExtension());
					
					if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"|| $ext == "svg")
					{
						$input['photo'] = time().'.'.$image->getClientOriginalExtension();
						$destinationPath = public_path(env('MEAL_PIC_MAX', '/storage/photos/meals/max'));
						$flag = $image->move($destinationPath, $input['photo']);

						$v = Meal::create($input);
						if($restaurant_id != '')
							return redirect()->intended('/admin/restaurant/meals/'.$restaurant_id);
						else
							return redirect()->intended('/admin/meals');

					}

					//$this->Validator->errors()->add('photo', 'Photo not uploaded.');

				}

			}
      
  	}

   

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Property  $page
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

			if (!isset($id)) {
					return redirect()->intended('/admin/meals');
			}
			$meal_categories = MealCategory::pluck('name', 'id');	
			$restaurants = Restaurant::where('active', '1')->where('place_name', '!=', '')->orderBy('place_name', 'asc')->pluck('place_name', 'id');
			$meal = Meal::find($id);
			
			return view('admin/meals/edit', ['meal' => $meal, 'meal_categories' => $meal_categories, 'restaurants' => $restaurants]);

			//return view('properties/edit', ['property' => $property, 'languages' => $languages, 'property_translations' => $property_translations, 'partners' => $partners, 'property_partners' => $property_partners, 'locations' => $locations, 'property_types' => $property_types]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Property  $page
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {

			$restaurant_id = '';
			
			if ($request->session()->has('sess_restaurant_id')) {
				$restaurant_id = $request->session()->get('sess_restaurant_id');
			}
			
			$rules = [
				'meal_category_id' => 'required|numeric',
				'restaurant_id' => 'required|numeric',
				'title' => 'required',
				'photo1' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
			];

			$this->validate($request, $rules);
			

			$input = [
				//'code'					=> strtoupper(unique_random('meals', 'code', 6)),
				'meal_category_id' 			=> $request['meal_category_id'],
				'restaurant_id' 			=> $request['restaurant_id'],
				'title' 					=> $request['title'],
				'description'				=> $request['description'],
				'amount'					=> $request['amount'],
				//'posted_by' 				=> $userId,
				'active' 					=> $request['active'],
			];

			//$restaurant = Restaurant::where('id', $request['restaurant_id'])->with('user')->first();

			//if(isset($restaurant->user_id)) {
				
				//$input['posted_by'] = $restaurant->user_id;

				if($request->file('photo1'))
				{
					$image = $request->file('photo1');
					
					$ext = strtolower($image->getClientOriginalExtension());
					
					if($ext == "jpg" || $ext == "jpeg" || $ext == "png" || $ext == "gif"|| $ext == "svg")
					{
						$input['photo'] = time().'.'.$image->getClientOriginalExtension();
						$destinationPath = public_path(env('MEAL_PIC_MAX', '/storage/photos/meals/max'));
						$flag = $image->move($destinationPath, $input['photo']);

					}

					//$this->Validator->errors()->add('photo', 'Photo not uploaded.');

				}

				Meal::where('id', $id)->update($input);

			//}

			if($restaurant_id != '')
				return redirect()->intended('/admin/restaurant/meals/'.$restaurant_id)->with('success', __('Meal updated successfully'));
			else
				return redirect()->intended('/admin/meals')->with('success', __('Meal updated successfully'));
				
      //return redirect()->intended('/admin/meals')->with('success', __('Meal updated successfully'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Property  $page
     * @return \Illuminate\Http\Response
     */
    public function destroy(Property $page)
    {
        //
    }


    /*****************  Admin Functions Ends *****************/

	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function feed(Request $request)
    {
		$keyword = "";
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

				// Auto Follow all Administrators
				/*'likes'=> function($query4) use ($user_id) {
					$query4->select('id')->where('user_id', '=', $user_id);
				},*/
				
				
				$followings = Follower::where('follower_id', $input['user_id'])->select('following_id')->get();
				
				$followings->push(array("following_id" => $input['user_id']));
				
				/*return response(array(
						'success' => true,
						'meals' =>$followings->toArray(),
					   ),200);*/
				
				$user_id = $input['user_id'];
				if(isset($input['keyword']))
					$keyword = $input['keyword'];
				
				/*$meals = Meal::where('active', '1')->with(
					array(
						'restaurant' => function($query){
							$query->select('id','place_name');
						},
						
						'user' => function($query3) {
							$query3->select('id','username', 'profile_pic');
						},
						'likesCount',
						'commentsCount'					
					)
				)->whereIn('posted_by', $followings)->orderBy('created_at', 'desc')->paginate(20);*/
				
				
				
				
				
				// List of words and phrases to be excluded in search
				$words = app('sharedSearchKeywords'); //Get the Search Keywords stored in AppServiceProvider
 
				$pattern = '/\b(?:' . join('|', $words) . ')\b/i';
				$keyword = preg_replace($pattern, '', $keyword);
				
				$searchValues = preg_split('/\s+/', $keyword, -1, PREG_SPLIT_NO_EMPTY); 
				
				$meals = Meal::where('active', '1')->whereIn('posted_by', $followings)->where(function ($q) use ($searchValues) {
				  foreach ($searchValues as $value) {
					$q->orWhere('title', 'like', "%{$value}%");
					$q->orWhere('description', 'like', "%{$value}%");
				  }
				})->with(
				array(
						'restaurant' => function($query){
							$query->select('id', 'place_id', 'place_name', 'place_lat', 'place_lng');
						},
						
						'user' => function($query3){
							$query3->select('id','username', 'profile_pic', 'profile_pic_rotation');
						},
						// 'comments' => function($query2){
						// 	$query2->select('id','meal_id', 'user_id', 'comment_text', 'created_at')->limit(2)->orderBy('id', 'DESC');
						// },
						
						'likesCount',
						'commentsCount'
					
					)
				)->orderBy('created_at', 'desc')->paginate(20);
				
		
				/***************** SAMPLE CODE *****************/
				
				
				if(count($meals) > 0) {
										
					foreach($meals as $meal) {
						
						
						$liked_by_me = Like::where('meal_id', $meal['id'])->where('user_id', $user_id)->count();
						
						$meal['liked_by_me'] = $liked_by_me;

						//dd(env('MEAL_PIC_MAX'));
						
						$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
						
						$meal['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max/').$meal->user->profile_pic);

						
						if(isset($meal['likesCount'][0]['aggregate'])) {
							$meal['total_likes'] = $meal['likesCount'][0]['aggregate'];
						}
						else {
							$meal['total_likes'] = 0;
						}

						if(isset($meal['commentsCount'][0]['aggregate'])) {
							$meal['total_comments'] = $meal['commentsCount'][0]['aggregate'];
						}
						else {
							$meal['total_comments'] = 0;
						}

						$comments = Comment::where('meal_id', $meal['id'])->orderBy('id', 'DESC')->with(
							array(

								'user' => 

								function($query4){
										$query4->select('id','username','profile_pic');
								}
							)
						)->limit(3)->get();
						

						//$meal['meal']['created_at_human'] = Carbon::parse($meal->created_at)->diffForHumans();

							
											
						foreach($comments as $comment) {
							
							$comment['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max').$comment->user->profile_pic);

						}
						$meal['comments'] = $comments;
						
					}
					
					return response(array(
						'success' => true,
						'meals' =>$meals->toArray(),
					   ), 200);
				}
				else {
					return response(array(
					'success' => false,
					'message' => 'No meal found.'
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
     * Stores a new post.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
    */
	 
		public function post(Request $request)
		{

			Log::info($request);

			$googleApiKey = env('GOOGLE_PLACES_API_KEY');
			$googlePlaces = new PlacesApi($googleApiKey);
			
			
			$input = $request->all();
			
			$rules = array(            
							'title'       => 'required',
							'image' 	  => 'required|image|mimes:jpeg,png,jpg|max:2048',
							'posted_by'   => 'required|numeric',	
					);
					$validator = Validator::make($input, $rules);
			if ($validator->fails()) {			
				return response(array(
						'error' => false,
						'message' =>'Unable to add meal record',
						'errorDtl' => $validator->errors()->all()
					),200);
					
			} else {

				if(!isset($input['place_id']) && !isset($input['restaurant_id']) && (!isset($input['lat']) || !isset($input['lon']) || !isset($input['restaurant_name']))) {
					$readyToSubmit = false;
					return response(array(
						'success' => false,
						'message' =>'Restaurant name or geo-location is missing'
					), 200);
					

				}

			
				$photo = "";
				$rotation = 0;
				$readyToSubmit = true;
				$restaurant_id = "";
			
				$input['meal_pic'] = time().'.'.$request->image->getClientOriginalExtension();
				
				$destinationPath = env('MEAL_PIC_MAX', '/storage/photos/meals/max');
				
				// Image Orientation is commented out for now
				// if($request->image->move($destinationPath, $input['meal_pic'])) {							
				// 	$photo = $input['meal_pic'];
				// 	$rotation = $this->correctImageOrientation($destinationPath."/".$photo);
				// }


				// S3 Code: Added by Dib
				$file = $request->file('image');
				$name = time() .".". $file->getClientOriginalExtension();
				$filePath = env('MEAL_PIC_UPLOAD_PATH') . $name;
				if(Storage::disk('s3')->put($filePath, file_get_contents($file))) {
					$photo = $input['meal_pic'];
				}
				
				
				try {
					
					$meal = new Meal;
					
					
					if(isset($input['restaurant_id']) && $input['restaurant_id'] != "") {						
						$restaurant_id = $input['restaurant_id'];			
					}
					
					else if(isset($input['place_id']) && $input['place_id'] != "") {
						$restaurantData = Restaurant::where('place_id', $input['place_id'])->first();
						
						if($restaurantData == null) {
								
							// Get the first admin user
							$admin = User::where('user_role', 'admin')->first();
							
							if($admin != NULL && $admin->id != NULL) {
								
								
								// Get Restaurant Details from Four Square
								
								if(isset($input['place_id']) && $input['place_id'] != "") {
									
									
									$place_id = $input['place_id'];
									
									$user = Restaurant::where('place_id', $place_id);
									
									
									$secret = env('FOURSQUARE_CLIENT_SECRET', '');
									$cid = env('FOURSQUARE_CLIENT_ID', '');
									$host = env('FOURSQUARE_API_URL', '');
									$ver = env('FOURSQUARE_VER', '');;
									
									//$limit = $data->limit;
									$offset = "";
									//if ($data->offset > 0) {
									//	$offset = "&offset=" . $data->offset;
									//}
									$url = $host.$place_id."?client_id=" . $cid . "&client_secret=" . $secret . "&v=" . $ver;
									
									//Final URL Example
									//https://api.foursquare.com/v2/venues/search?ll=37.8141,144.9633&limit=1&offset=1&client_id=FOURSQUARE_CLIENTID&client_secret=FOURSQUARE_SECRET&v=20160510
									
									//Ex: https://developer.foursquare.com/docs/api/venues/details
									
									
									// initiate curl
									$ch = curl_init();
									curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
									curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
									curl_setopt($ch, CURLOPT_URL, $url);
									$result = curl_exec($ch);
									curl_close($ch);
									
									
									
									$restaurant = json_decode($result, true);
									
									if(isset($restaurant['response']['venue']['id']) && $restaurant['response']['venue']['id'] != "") {
										

										$username = strtolower(preg_replace("/[^a-zA-Z]+/", ".", $restaurant['response']['venue']['name']));

										if(strlen($username) > 45) {
											$username = substr($username, 0, 45);
										}
										
										// Find same username
										$count_username = 0;
										$ctr = 0;
										do {
											if($ctr > 0) {
												$rand = mt_rand(0,100);
												$username = $username."".$rand;
											}
											$count_username  = User::where('username', $username)->count();
											$ctr++;
										} while($count_username == 1);
										
										
										
										
										
										// First add user record
										$user = new User;
										$user->first_name       	= NULL;
										$user->last_name      		= NULL;
										$user->username 			= $username;
										$user->password 			= bcrypt(str_random(6));
										$user->email 				= NULL;
										$user->business_name = $restaurant['response']['venue']['name'];

										$resObj = new Restaurant;
										$resObj->place_name = $restaurant['response']['venue']['name'];
										$resObj->place_id = $input['place_id'];
										$resObj->place_code = NULL;
										//$resObj->user_id = 1;
										
										$resObj->place_street = "";
										$resObj->place_locality = "";
										
										
										
										if(isset($restaurant['response']['venue']['location']['city']) && $restaurant['response']['venue']['location']['city'] != "") {
											$resObj->place_city = $restaurant['response']['venue']['location']['city'];
											$user->city = $restaurant['response']['venue']['location']['city'];
										}
										
										if(isset($restaurant['response']['venue']['location']['state']) && $restaurant['response']['venue']['location']['state'] != "") {
											$resObj->place_state = $restaurant['response']['venue']['location']['state'];
											$user->state = $restaurant['response']['venue']['location']['state'];
										}
										
										if(isset($restaurant['response']['venue']['location']['country']) && $restaurant['response']['venue']['location']['country'] != "") {
											$resObj->place_country = $restaurant['response']['venue']['location']['country'];
											
											$user->country = $restaurant['response']['venue']['location']['country'];
										}
										
										if(isset($restaurant['response']['venue']['location']['postalCode']) && $restaurant['response']['venue']['location']['postalCode'] != "") {
											$resObj->place_zipcode = $restaurant['response']['venue']['location']['postalCode'];
											$user->zip = $restaurant['response']['venue']['location']['postalCode'];
										}

										if(isset($restaurant['response']['venue']['location']['lat']) && $restaurant['response']['venue']['location']['lat'] != "") {									
											$resObj->place_lat = $restaurant['response']['venue']['location']['lat'];
											
											$user->lat = $restaurant['response']['venue']['location']['lat'];
										}

										if(isset($restaurant['response']['venue']['location']['lng']) && $restaurant['response']['venue']['location']['lng'] != "") {									
											$resObj->place_lng =  $restaurant['response']['venue']['location']['lng'];	

											$user->lng = $restaurant['response']['venue']['location']['lng'];											
										}									
										$resObj->place_website = "";
										
										if(isset($restaurant['response']['venue']['contact']['phone']) && $restaurant['response']['venue']['contact']['phone'] != "") {
											$resObj->place_phone = $restaurant['response']['venue']['contact']['phone'];
											$user->phone = $restaurant['response']['venue']['contact']['phone'];
										}
										
										$resObj->place_photo_reference	 = "";
										
										if(isset($restaurant['response']['venue']['rating']) && $restaurant['response']['venue']['rating'] != "") {
											$resObj->place_rating = $restaurant['response']['venue']['rating'];	
										}
										
										if(isset($restaurant['response']['venue']['categories']) && count($restaurant['response']['venue']['categories']) > 0) {
											
											$categories = $restaurant['response']['venue']['categories'];
											
											$cats = "";
											
											for($i = 0; $i < count($categories); $i++) {
												
												
												if($cats != "")
													$cats .= ",";
												
												$cats .= $categories[$i]['name'];
											}
											
											$resObj->place_types = $cats;
										
										}
										
										if(isset($restaurant['response']['venue']['location']['formattedAddress']) && $restaurant['response']['venue']['location']['formattedAddress'] != "") {
											$formattedAddress = $restaurant['response']['venue']['location']['formattedAddress'];
											//print_r($formattedAddress);
											$address = "";
											for($i = 0; $i < count($formattedAddress); $i++) {
												
												
												if($address != "")
													$address .= ",";
												
												$address .= $formattedAddress[$i];
											}
											
											$resObj->place_vicinity = $address;
											$user->address = $address;
										}
										
										// 2=Business User		
										$user->user_type = 2;	
										
										
										
										$user->user_role 			= "user";
										$user->device_type 			= NULL;
										$user->device_token 		= NULL;
										$user->ownership 			= '0';
										$user->active 				= '0';
										
										
										DB::transaction(function() use ($user, $resObj, &$restaurant_id) {
					
											if($user->save()) {
												
												$resObj->user_id = $user->id;
												//dd($resObj->save());
												if($resObj->save()) {
												
													$restaurant_id = $resObj->id;
													
													$readyToSubmit = true;
												
												}
												else {
													$readyToSubmit = false;
													return response(array(
														'success' => false,
														'message' =>'Unable to save restaurant record'
													),200);
												}
												
											}
											else {
												$readyToSubmit = false;
												return response(array(
													'success' => false,
													'message' =>'Unable to save user record'
												),200);
											}
										});
										
									}								
									
									
								}
								else {
									$readyToSubmit = false;
									return response(array(
										'success' => false,
										'message' =>'Place id not found'
									),200);
								}
							}
							else {
															
								$readyToSubmit = false;
								
								return response(array(
									'success' => false,
									'message' =>'Unable to save meal record. No admin user found.'
								),200);
								
							}
						}
						else {
							//dd($restaurantData['id']);
							$restaurant_id = $restaurantData['id'];
						
						}
					
							
					}

					else {

							$resObj = new Restaurant;
							$resObj->place_name = $input['restaurant_name'];
							$resObj->place_lat = $input['lat'];
							$resObj->place_lng = $input['lon'];
							$resObj->user_id = $input['posted_by'];
							$resObj->source = 2;  // 1 = Four Square, 2 = Manual
							$resObj->active = "0";
							if($resObj->save()) {
							
								$restaurant_id = $resObj->id;
								
								$readyToSubmit = true;
							
							}
							else {
								$readyToSubmit = false;
								return response(array(
									'success' => false,
									'message' =>'Unable to save restaurant record'
								),200);
							}
						
					}
					


					
					
					if($readyToSubmit) {
						
						if($restaurant_id != "") {
							$meal->code =  strtoupper(unique_random('meals', 'code', 6));
							
							if($restaurant_id != NULL) {
								$meal->title      						= $input['title'];
								$meal->description 						= $input['description'];
								$meal->meal_category_id      	= $input['category_id'];
								$meal->photo 									= $input['meal_pic'];
								$meal->photo_rotation 				= $rotation;
								$meal->posted_by 							= $input['posted_by'];
								$meal->active 								= '1';
								$meal->restaurant_id 					= $restaurant_id;
							}
							else {
								$meal->title      						= $input['title'];
								$meal->description 						= $input['description'];
								$meal->meal_category_id      	= $input['category_id'];
								$meal->photo 									= $input['meal_pic'];
								$meal->photo_rotation 				= $rotation;
								$meal->posted_by 							= $input['posted_by'];
								$meal->lat 										= $input['lat'];
								$meal->lon 										= $input['lon'];
								$meal->restaurant_name 				= $input['restaurant_name'];
								$meal->active 								= '1';
								$meal->restaurant_id 					= $restaurant_id;
							}
											
							
											
							if($meal->save()) {
								$meal->photo = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
								//$meal->photo = Storage::get(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
								//$meal->photo = Storage::get($meal->photo);
								
								return response(array(
									'success' => true,
									'message' =>'Meal successfully posted',
									'meal'	=> $meal
								),200);
								
							}
							else {
								return response(array(
									'success' => false,
									'message' =>'Unable to save meal record'
								), 200);
							}
						
						}
						else {
							return response(array(
								'success' => false,
								'message' =>'Unable to save meal record, restaurant id not found.'
							), 200);
						}
						
					
						
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
            'id' => 'numeric|required',
            'self_id' => 'numeric|required',
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			return response(array(
					'success' => false,
					'message' =>'Unable to fetch meal record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
				$meal = array();
				if(isset($input['id']))
					$meal = Meal::where('id', $input['id'])->with('restaurant', 'likes', 'comments', 'user', 'likesCount', 'commentsCount')->first();
				
				$path = env('MEAL_PIC_MAX', '/storage/photos/meals/max');
				$user_path = env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max');
				
				
				
				if($meal != null) {

					
					$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
						
				
					if(isset($meal['user']['profile_pic']) && $meal['user']['profile_pic']!= "")
						$meal['user']['profile_pic'] = env('PROFILE_PIC_THUMB') .$meal['user']['profile_pic'];
					
					$liked_by_me = Like::where('meal_id', $input['id'])->where('user_id', $input['self_id'])->count();	
					$meal['liked_by_me'] = $liked_by_me;

					if(isset($meal['likesCount'][0]['aggregate'])) {
						$meal['total_likes'] = $meal['likesCount'][0]['aggregate'];
					}
					else {
						$meal['total_likes'] = 0;
					}

					if(isset($meal['commentsCount'][0]['aggregate'])) {
						$meal['total_comments'] = $meal['commentsCount'][0]['aggregate'];
					}
					else {
						$meal['total_comments'] = 0;
					}

					
					return response(array(
						'success' => true,
						'message' =>'Meal details found',
						'meal'	  => $meal
					   ),200);
				}
				else {
					return response(array(
					'success' => false,
					'message' =>'Unable to find meal record',
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
	
	
	public function upload(Request $request)
    {
		$input = $request->all();
		
    	$this->validate($request, [
    		'id' => 'required',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

		$user = User::find($input['id']);
		
		
		if($user != null) {
			
			$input['meal_pic'] = time().'.'.$request->image->getClientOriginalExtension();
			$destinationPath = public_path(env('MEAL_PIC_MAX', '/storage/photos/meals/max'));
			
			if($request->image->move($destinationPath, $input['meal_pic'])) {
				
				
				$user->photo = $input['meal_pic'];
				$user->save();
				
				return response(array(
						'success' => true,
						'message' =>'Meal picture successfully uploaded',
						'user'	  => $input
					   ),200);
			
			}
			
			
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
	
	
	public function like(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'meal_id'       		=> 'required|numeric',
            'user_id'      			=> 'required|numeric'			
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
					'error' => false,
					'message' =>'Unable to like the meal',
					'errorDtl' => $validator->errors()->all()
				),200);
				
		} else {
		
			try {
				
				// Check if user exists
				$count = User::where('id', $input['user_id'])->count();

				if($count == 0) {
					return response(array(
						'success' => false,
						'message' =>'User does not exist'
					),200);
				}
					
				// Check if meal exists
				$meal = Meal::where('id', $input['meal_id'])->select('posted_by')->first();
				
				if(isset($meal->posted_by) && $meal->posted_by != "") {
					
					$count_liked = Like::where('user_id', $input['user_id'])->where('meal_id', $input['meal_id'])->count();
					
					if($count_liked == 0) {
						
						$like = new Like;
						$like->user_id       		= $input['user_id'];
						$like->meal_id      		= $input['meal_id'];				
						
						if($like->save()) {

							$total_likes = Like::where('meal_id', $input['meal_id'])->count();
					
							
							// Add to notifications table
							$notification = new Notification;
							$notification->sender_id      	= $input['user_id'];
							$notification->recipient_id   	= $meal->posted_by;
							$notification->notify_type		= 1; // 1=Like
							$notification->meal_id			= $input['meal_id'];
							$notification->save();
							
							// Send Push Notification
							app('App\Http\Controllers\MessageController')->send(1, $meal->posted_by, $input['user_id']);
							//app('App\Http\Notifications\PushNotification')->send(2, $meal->posted_by, $input['user_id']);
							$user = User::where('id', $meal->posted_by)->first();

							/*if(isset($user->id)) {
								//dd($user->id);
								$user->notify(new PushNotification());
							}*/
							

							
							//Notification::send($users, new InvoicePaid($invoice));

							return response(array(
								'success' => true,
								'total_likes' => $total_likes,
								'message' =>'User successfully liked the meal',
							),200);
							
						}
						else {
							return response(array(
								'success' => false,
								'message' =>'Unable to save like'
							),200);
						}
						
						
					}
					else {
						return response(array(
								'success' => false,
								'message' =>'You have already liked the meal'
							),200);
					}
				}
				else {
						return response(array(
								'success' => false,
								'message' =>'Meal does not exist'
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
	
	
	public function unlike(Request $request)
  {
		$input = $request->all();
		$rules = array(
            'meal_id'       		=> 'required|numeric',
            'user_id'      			=> 'required|numeric'			
        );
		
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
					'error' => false,
					'message' =>'Unable to unlike the meal',
					'errorDtl' => $validator->errors()->all()
				),200);
				
		} else {
		
			try {
				
				// Check if meal exists
				$meal = Meal::where('id', $input['meal_id'])->select('posted_by')->first();
				
				if(isset($meal->posted_by) && $meal->posted_by != "") {
					
					$liked = Like::where('user_id', $input['user_id'])->where('meal_id', $input['meal_id'])->first();
					
					if(isset($liked['id']) && $liked['id'] != "") {
						
						
						if($liked->delete()) {					
							$total_likes = Like::where('meal_id', $input['meal_id'])->count();
												
							
							return response(array(
								'success' => true,
								'total_likes' => $total_likes,
								'message' =>'User successfully unliked the meal',
							),200);
							
						}
						else {
							return response(array(
								'success' => false,
								'message' =>'Unable to save record'
							),200);
						}
						
						
					}
					else {
						return response(array(
								'success' => false,
								'message' =>'You have not liked the meal'
							),200);
					}
				}
				else {
						return response(array(
								'success' => false,
								'message' =>'Meal does not exist'
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
	
	
	public function comment(Request $request)
  {
		$input = $request->all();
		$rules = array(
            'meal_id'       		=> 'required|numeric',
            'user_id'      			=> 'required|numeric',
            'comment_text'      	=> 'required'			
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
					'error' => false,
					'message' =>'Unable to like the meal',
					'errorDtl' => $validator->errors()->all()
				),200);
				
		} else {
		
			try {
				
				// Check if meal exists
				$meal = Meal::where('id', $input['meal_id'])->select('posted_by')->first();
				
				if(isset($meal->posted_by) && $meal->posted_by != "") {
					
					/*$count_comment = Comment::where('user_id', $input['user_id'])->where('meal_id', $input['meal_id'])->where('comment_text', $input['comment_text'])->count();
					
					if($count_comment == 0) {*/
						
						$comment = new Comment;
						$comment->user_id       	= $input['user_id'];
						$comment->meal_id      		= $input['meal_id'];	
						$comment->comment_text      = $input['comment_text'];			
						
						if($comment->save()) {
							
							// Add to notifications table
							$notification = new Notification;
							$notification->sender_id      	= $input['user_id'];
							$notification->recipient_id   	= $meal->posted_by;
							$notification->notify_type		= 2; // 2=Comment
							$notification->meal_id			= $input['meal_id'];
							$notification->comment_id		= $comment->id; // Get Auto-increment id
							$notification->save();
							
							// Send Push Notification
							app('App\Http\Controllers\MessageController')->send(2, $meal->posted_by, $input['user_id']);
							
							return response(array(
								'success' => true,
								'message' =>'User successfully commented on the meal',
							),200);
							
						}
						else {
							return response(array(
								'success' => false,
								'message' =>'Unable to save user comment'
							),200);
						}
					/*}
					else {
						return response(array(
								'success' => false,
								'message' =>'You have already posted same comment on this meal'
							),200);
					}*/
				
				}
				else {
						return response(array(
								'success' => false,
								'message' =>'Meal does not exist'
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
     * Returns list of comments for a meal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function comments(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'meal_id' => 'required|numeric',
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			return response(array(
					'success' => false,
					'message' =>'Unable to fetch comments',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
				$comments = array();
				if(isset($input['meal_id'])) {
					$comments = Comment::where('meal_id', $input['meal_id'])->with(
						array('user'=>function($query){
							$query->select('id','username', 'profile_pic', 'profile_pic_rotation');
						})
					)->orderBy('id', 'desc')->get();
				
					$pic_url = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max'));
					

					if($comments != null && count($comments) > 0) {
						
						$i = 0;
						foreach($comments as $comment) {
							if(isset($comment['user']['profile_pic']) && $comment['user']['profile_pic'] != "") {

								$comment['user']['profile_pic'] = $pic_url.$comment['user']['profile_pic'];
								//$comments[$i]['user']['profile_pic'] = $pic_url.$comment['user']['profile_pic'];
								//$comment['user']['profile_pic'] = $pic_url.$comment['user']['profile_pic'];
								//$comments.push($comment['user']['profile_pic']);
								
							}
						}
						
						
						return response(array(
							'success' => true,
							'message' =>'Comments found',
							'profile_pic_url' => $pic_url,
							'comments'	  => $comments
						   ),200);
					}
					else {
						return response(array(
						'success' => false,
						'message' =>'Unable to find comments',
						'errorDtl' => $validator->errors()->all()
					),200);
					}
				
				}
				else {
					return response(array(
					'success' => false,
					'message' =>'Unable to find comments',
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
     * Returns list of likes for a meal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function likes(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'meal_id' => 'required|numeric',
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			return response(array(
					'success' => false,
					'message' =>'Unable to fetch likes',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
				$likes = array();
				
				$profile_pic_url = env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max');
				
				if(isset($input['meal_id'])) {
					$likes = Like::where('meal_id', $input['meal_id'])->with(
						array('user'=>function($query){
							$query->select('id','username', 'profile_pic', 'profile_pic_rotation');
						})
					)->get();
					
					foreach($likes as $like) {
						if(isset($like->user['profile_pic']) && $like->user['profile_pic'] != "") {
							$like['user']['profile_pic'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max/').$like->user['profile_pic']);
						}
					}
				
				
					
					if($likes != null && count($likes) > 0) {
						return response(array(
							'success' => true,
							'message' =>'Likes found',
							'likes'	  => $likes
						   ),200);
					}
					else {
						return response(array(
						'success' => false,
						'message' =>'Unable to find likes',
						'errorDtl' => $validator->errors()->all()
					),200);
					}
				
				}
				else {
					return response(array(
					'success' => false,
					'message' =>'Unable to find likes',
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
     * Returns list of likes for a meal.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function details(Request $request)
    {
		
		if(isset($request->id) && $request->id != "" && isset($request->type) && $request->type != "") {
			
			try {
				
				if($request->type == "likes") {
					
					$likes = array();
					$meal = array();
				
					$profile_pic_url = env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max');				
					$likes = Like::where('meal_id', $request->id)->with(
						array('user'=>function($query){
							$query->select('id','first_name', 'last_name', 'email',  'username', 'profile_pic',  'created_at');
						})
					)->orderBy('created_at', 'desc')->paginate(25);
					
					foreach($likes as $like) {
						if(isset($like->user['profile_pic']) && $like->user['profile_pic'] != "") {
							$like['user']['profile_pic'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max')."/".$like->user['profile_pic']);
							
							
						}
					}
					
					if(isset($likes[0]['meal_id']))
						$meal = Meal::where('id', $likes[0]['meal_id'])->pluck('title')->first();
					;
					
					if($likes != null && count($likes) > 0) {
						return view('admin/meals/details', ['data' => $likes, 'meal' => $meal, 'type' => $request->type]);
					}
					else {
						return "No likes found";
					}
					
				}
				else if($request->type == "comments") {
					
					$comments = array();
					$meal = array();
				
					$profile_pic_url = env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max');				
					$comments = Comment::where('meal_id', $request->id)->with(
						array('user'=>function($query){
							$query->select('id','first_name', 'last_name', 'email',  'username', 'profile_pic',  'created_at');
						})
					)->orderBy('created_at', 'desc')->paginate(25);

					
					foreach($comments as $comment) {
						if(isset($comment->user['profile_pic']) && $comment->user['profile_pic'] != "") {
							$comment['user']['profile_pic'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/profiles/max')."/".$comment->user['profile_pic']);
						}
					}
					
					if(isset($comments[0]['meal_id']))
						$meal = Meal::where('id', $comments[0]['meal_id'])->pluck('title')->first();
					
					
					if($comments != null && count($comments) > 0) {
						return view('admin/meals/details', ['data' => $comments, 'meal' => $meal, 'type' => $request->type]);
					}
					else {
						return "No comment found";
					}
				}	
					   
			} catch (\Illuminate\Database\QueryException $e) {
				return "No meal record found";
			}
		}
    }
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function my(Request $request)
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

				$user = User::where('id', $input['user_id'])->first();
				$flag = 0;

				//dd($user);

				/*if(isset($user['user_type']) && $user['user_type'] == "2") {
					
					$restaurant = Restaurant::where('user_id', $input['user_id'])->first();	

					if(isset($restaurant->id) && $restaurant->id != "") {
						$meals = Meal::where('active', '1')->where('restaurant_id', $restaurant->id)->with(
							array(
								'restaurant' => function($query){
									$query->select('id','place_name');
								},
								
								'user' => function($query3){
									$query3->select('id','username', 'profile_pic', 'profile_pic_rotation');
								},
								
								'likesCount',
								'commentsCount'
							
							)
						)->orderBy('created_at', 'desc')->paginate(20);

						//dd($meals);
					}
					else {
						$flag = 1;
					}
					
				}
				else {
					$flag = 1;
				}*/
				$flag = 1;

				if($flag == 1) {
					$meals = Meal::where('active', '1')->where('posted_by', $input['user_id'])->with(
						array(
							'restaurant' => function($query){
								$query->select('id','place_name');
							},
							
							'user' => function($query3){
								$query3->select('id','username', 'profile_pic');
							},
							
							'likesCount',
							'commentsCount'
						
						)
					)->orderBy('created_at', 'desc')->paginate(150);
				}
				
				
				if(count($meals) > 0) {
					
				
					foreach($meals as $meal) {
						
						$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
						
						$meal['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/meals/max/').$meal->user->profile_pic);
						
					}
					
					return response(array(
						'success' => true,
						'meals' =>$meals->toArray(),
					   ),200); 
				}
				else {
					return response(array(
					'success' => false,
					'message' => 'You do not have any meals posted.'
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
    public function restaurant(Request $request)
    {		
		$input = $request->all();
		$rules = array(
            'user_id'  		=> 'required|numeric',			
            'category_id'  	=> 'numeric'
        );
		
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
		
			return response(array(
				'success' => false,
				'message' =>'Unable to retrieve data',
				'errorDtl' => $validator->errors()->all()
			), 200);
				
		} else {
			
			$user_id = $input['user_id'];
			$restaurant = Restaurant::where('user_id', $user_id)->first();			
			
			//dd($input['meal_category_id']);
			
			try {
				
				if(isset($input['meal_category_id']) &&  $input['meal_category_id'] != "") {
					
					$meal_categories = MealCategory::with(['meal' => function ($query)  use ($restaurant, $input, $user_id) {
						//$query->where('restaurant_id', $restaurant->id);
						$query->where('posted_by', $user_id);


						$query->orderBy('created_at', 'desc');
						//$query->groupBy('meal_category_id');

					}])->where('id', $input['meal_category_id'])->groupBy('id')->get();
				}
				else {
					
					$meal_categories = MealCategory::with(['meal' => function ($query)  use ($restaurant) {
						$query->where('restaurant_id', $restaurant->id);
						$query->orderBy('created_at', 'desc');
						//$query->groupBy('meal_category_id');

					}])->groupBy('id')->get();
				}
				
				//dd($meals);
				/*$meals = Meal::with(['restaurant' => function ($query)  use ($user_id) {
					 $query->where('user_id', $user_id);

				}])->get();*/
			
				
				
				/*$meals = Meal::where('active', '1')->where('restaurant_id', $input['user_id'])->with(
					array(
						'restaurant' => function($query){
							$query->select('id','place_name');
						},
						
						'user' => function($query3){
							$query3->select('id','username', 'profile_pic');
						},
						
						'likesCount',
						'commentsCount'
					
					)
				)->orderBy('created_at', 'desc')->paginate(20);*/
				
				/*if(count($meals) > 0) {
					
				
					foreach($meals as $meal) {
						
						
							//$meal = $meal_category['meal'];
							
							$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
							
							//$meal['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/meals/max/')."/".$meal->user->profile_pic);
							
						
						
						$meal['user'] = NULL;
					}
					
					return response(array(
						'success' => true,
						'meals' =>$meals->toArray(),
					   ),200); 
				}
				else {
					return response(array(
					'success' => false,
					'message' => 'You do not have any meals tagged with your restaurant.'
					),200);
				}*/
				
				
				if(count($meal_categories) > 0) {
					
				
					foreach($meal_categories as $meal_category) {
							
							//dd($meal_category);
						
							$meals = $meal_category['meal'];
							
							foreach($meals as $meal) {
								
								//echo $meal['title']."<br />";
							
								$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal->photo);
								//$meal['user'] = NULL;
							}
							
							if(isset($meal))
								$meal_category['meal'] = $meal;
							else
								$meal_category['meal'] = NULL;
							//$meal['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/meals/max/')."/".$meal->user->profile_pic);
							
						
						
						
					}
					//exit;
					
					return response(array(
						'success' => true,
						'meals' =>$meal_categories->toArray(),
					   ),200); 
				}
				else {
					return response(array(
					'success' => false,
					'message' => 'You do not have any meals tagged with your restaurant.'
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mealsByRestaurant(Request $request)
    {		
			$input = $request->all();
			$rules = array(
							'restaurant_id'  		=> 'numeric',		
							'category_id'  	=> 'numeric'
			);
		
      $validator = Validator::make($input, $rules);
			if ($validator->fails()) {
			
				return response(array(
					'success' => false,
					'message' =>'Unable to retrieve data',
					'errorDtl' => $validator->errors()->all()
				), 200);
					
			} else {
				
				if(isset($input['restaurant_id'])) {
					$restaurant_id = $input['restaurant_id'];
					$restaurant = Restaurant::where('id', $input['restaurant_id'])->first();			
				}
				
				else if(isset($input['place_id'])) {
					$restaurant = Restaurant::where('place_id', $input['place_id'])->first();	
					if(isset($restaurant['id'])) {
						$restaurant_id = $restaurant['id'];
					}
					else {
						return response(array(
							'success' => false,
							'message' =>'No matching restaurant found for the given place id',
							'errorDtl' => $validator->errors()->all()
						), 200);
					}
				}

				else {
					return response(array(
						'success' => false,
						'message' =>'Either restuarant id or place id is mandatory',
						'errorDtl' => $validator->errors()->all()
					), 200);
				}
				
				
				
				
				try {
					
					
					$meals = Meal::where('restaurant_id', $restaurant_id)->with('mealCategory')->orderBy('meal_category_id')->get();		


					
					if($meals->count() > 0) {
				
						$meal_pic_url = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/'));
						
						return response(array(
							'success' => true,
							'meal_pic_url' => $meal_pic_url,
							'meals' => $meals->toArray(),
							),200); 
					}
					else {
						return response(array(
						'success' => false,
						'message' => 'You do not have any meals tagged with your restaurant.'
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function mostLikes(Request $request)
    {		
		$input = $request->all();
	
		try {

			$meals = Meal::withCount('likes')
			->orderBy('likes_count', 'desc')
			->take(50)->get();
			
			if(count($meals) > 0) {
				
			
				foreach($meals as $meal) {
					
					
					$meal['photo'] = asset(env('MEAL_PIC_MAX', '/storage/photos/meals/max/').$meal['photo']);
					
					//$meal['user']['profile_pic_url'] = asset(env('PROFILE_PIC_THUMB', '/storage/photos/meals/max/')."/".$meal->user->profile_pic);
					
				}
				
				return response(array(
					'success' => true,
					'meals' =>$meals->toArray(),
				   ),200); 
			}
			else {
				return response(array(
				'success' => false,
				'message' => 'You do not have any meals posted.'
				),200);
			}
			   
		} catch (\Illuminate\Database\QueryException $e) {
			return response(array(
				'success' => false,
				'message' => 'Unable to process request, database error occurred.'
				),200);
		}
				   
    }
	
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function delete(Request $request)
    {		
		$input = $request->all();
	
		try {

			$meal = Meal::find($input['id']);
						
			if(isset($meal['id']) && $meal['id'] != "") {
				
			
				if(isset($meal->photo) && $meal->photo != "") {
					
					$meal->likes()->delete();
					$meal->comments()->delete();
					$meal->notifications()->delete();
					
					
					//dd(env('MEAL_PIC_MAX').$meal->photo);
					if($meal->delete()) {
						
						/*return response(array(
						'success' => true,
						'meal' =>$meal,
					    ),200); */
						
						
						File::delete(env('MEAL_PIC_MAX').$meal->photo);
						
						return response(array(
						'success' => true,
						'message' => 'Meal successfully deleted.'
					   ),200); 
					}
					else {
						return response(array(
						'success' => false,
						'message' => 'Unable to delete meal.'
						),200);
					}
						
				}
			}
			else {
				return response(array(
				'success' => false,
				'message' => 'Meal does not exist.'
				),200);
			}
			   
		} catch (\Illuminate\Database\QueryException $e) {
			return response(array(
				'success' => false,
				'message' => 'Unable to process request, database error occurred.'
				),200);
		}
				   
	}
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteSingle($id)
    {		
		
		try {

			$meal = Meal::find($id);
						
			if(isset($meal['id']) && $meal['id'] != "") {
				
			
				if(isset($meal->photo) && $meal->photo != "") {
					
					$meal->likes()->delete();
					$meal->comments()->delete();
					$meal->notifications()->delete();
					
					
					//dd(env('MEAL_PIC_MAX').$meal->photo);
					if($meal->delete()) {
						
						/*return response(array(
						'success' => true,
						'meal' =>$meal,
						),200); */
						
						
						File::delete(env('MEAL_PIC_MAX').$meal->photo);

						return redirect()->intended('/admin/meals');
						
						
					}
					else {
						return redirect()->back()->with('error', __('Unable to delete meal.'));
					}
						
				}
			}
			else {

				return redirect()->back()->with('error', __('Meal does not exist.'));
				
			}
			   
		} catch (\Illuminate\Database\QueryException $e) {
			return redirect()->back()->with('error', __('Meal does not exist.'));
		}
				   
    }
	
	/**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function flag(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'user_id'       => 'required|numeric',			
            'meal_id'  		=> 'required|numeric',
			
        );
        $validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
					'success' => false,
					'message' =>'Unable to flag meal',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
			
				$user = User::find($input['user_id']);
				$meal = Meal::find($input['meal_id']);
				
				
				
				if($user != null && $meal != null) {
					
				
					$cnt_flagged = FlaggedMeal::where('user_id', $input['user_id'])->where('meal_id', $input['meal_id'])->count();
					
					
					if($cnt_flagged > 0) {
						
						return response(array(
						'success' => false,
						'message' =>'You have alraedy flagged this meal',
					   ), 200);
						
					}
					
					
					$flgMeal = new FlaggedMeal;
					$flgMeal->user_id = $input['user_id'];
					$flgMeal->meal_id = $input['meal_id'];
					
					if($flgMeal->save()) {
						return response(array(
						'success' => true,
						'message' =>'Meal has been successfully flagged',
					   ),200);
					}
					else {
						return response(array(
							'success' => false,
							'message' =>'Unable to flag meal',
						),200);
					}
					
				 
					
				
				}
				else {
					return response(array(
						'success' => false,
						'message' =>'Either the user or the meal does not exist in our record',
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
		

	
	
}

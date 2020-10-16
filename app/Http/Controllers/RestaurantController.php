<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use Hash;
use Mail;
use App\Restaurant;
use App\RestaurantUser;
use App\MealCategory;
use App\Place;
use App\PlaceCategory;
use App\Meal;
use App\Mail\NotifyMissingRestaurantMail;
use SKAgarwal\GoogleApi\PlacesApi;
use Illuminate\Support\Facades\Validator;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;


class RestaurantController extends Controller
{
	
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
				'id' => 'required'
		);
		$validator = Validator::make($input, $rules);

		if ($validator->fails()) {
		
			$errors = $validator->errors();
			return response(array(
					'success' => false,
					'message' =>'Unable to fetch restaurant record',
					'errorDtl' => $validator->errors()->all()
					),200);
				
		}
		else {
			
			try {
				$restaurant = array();
				$restaurant = Restaurant::where('id', $input['id'])->with('user')->first();
				
				
				if($restaurant != null) {
					return response(array(
						'success' => true,
						'message' =>'Restaurant details found',
						'restaurant'	  => $restaurant
						),200);
				}
				else {

					// Send mail to looksyummy to notify that the restaurant has been searched

					$endpoint = "https://api.foursquare.com/v2/venues/".$input['place_id'];
					$client = new \GuzzleHttp\Client();
					$id = 5;
					$value = "ABC";

					$response = $client->request('GET', $endpoint, ['query' => [
							'client_id' => 'CCZQ3BJAUM2EOS3RQHR4ACWRUHQ1OWFEPJCNUB0TT0AWV4M2', 
							'client_secret' => 'NTUCGL4CWXU35KZ04IXZH1F5VRL2I3JFW1H0MFKBSYSHWENB',
							'v'	=> '20180228'
					]]);

					// url will be: http://my.domain.com/test.php?key1=5&key2=ABC;

					$statusCode = $response->getStatusCode();
					$content = $response->getBody();
					$restaurant_details = json_decode($content);
					//$restau($restaurant_details->response->venue->name);
					//exit;

					if(isset($restaurant_details->response->venue->name)) {
						$restaurant['name'] = $restaurant_details->response->venue->name;
					}
					else {
						$restaurant['name'] = "";
					}

					
					if(isset($restaurant_details->response->venue->contact->phone)) {
						$restaurant['phone'] = $restaurant_details->response->venue->contact->phone;
					}
					else {
						$restaurant['phone'] = "";
					}					
					
					if(isset($restaurant_details->response->venue->location->address)) {
						$restaurant['address'] = $restaurant_details->response->venue->location->address;
					}
					else {
						$restaurant['address'] = "";
					}

					if(isset($restaurant_details->response->venue->location->postalCode)) {
						$restaurant['postalCode'] = $restaurant_details->response->venue->location->postalCode;
					}
					else {
						$restaurant['postalCode'] = "";
					}

					
					if(isset($restaurant_details->response->venue->location->city)) {
						$restaurant['city'] = $restaurant_details->response->venue->location->city;
					}
					else {
						$restaurant['city'] = "";
					}
					
					if(isset($restaurant_details->response->venue->location->state)) {
						$restaurant['state'] = $restaurant_details->response->venue->location->state;
					}
					else {
						$restaurant['state'] = "";
					}
					
					if(isset($restaurant_details->response->venue->location->country)) {
						$restaurant['country'] = $restaurant_details->response->venue->location->country;
					}
					else {
						$restaurant['country'] = "";
					}

					//dd($restaurant);

					Mail::to(env('CONTACT_EMAIL'))->send(new NotifyMissingRestaurantMail($restaurant));


					return response(array(
					'success' => false,
					'message' =>'Unable to find restaurant record',
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
	
	// Called from Admin
	public function index(Request $request)
	{
		$keyword = "";
		
		if($request->clear == 1) {
			$request->session()->forget('sess_filter_restaurant_keyword');
		}
		
		if(isset($request->keyword)) {
			$request->session()->put('sess_filter_restaurant_keyword', $request->keyword);
		}

		if ($request->session()->has('sess_filter_restaurant_keyword')) {
				$keyword = $request->session()->get('sess_filter_restaurant_keyword');
		}

		
		$restaurants = Restaurant::keyword($keyword)->with('meals', 'restaurantUsers')->paginate(20);
		
		return view('admin/restaurants/index', ['restaurants' => $restaurants]);	
	}

	public function meals(Request $request, $id = NULL)
	{
		
		
		$keyword = "";
		$meal_category_id = "";

		$request->session()->put('sess_restaurant_id', $id);
		$meal_categories = MealCategory::pluck('name', 'id');	

		

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

		$meals = Meal::keyword($keyword)->mealCategoryId($meal_category_id)->with('restaurant', 'user', 'mealCategory')->where('restaurant_id', $id)->orderBy('created_at', 'desc')->paginate(15);
	

		//$meals = Meal::with('restaurant', 'user', 'mealCategory')->where('restaurant_id', $id)->orderBy('created_at', 'desc')->paginate(15);
		return view('admin/meals/index', ['meals' => $meals, 'meal_categories' => $meal_categories, 'keyword' => $keyword, 'meal_category_id' => $meal_category_id]);	
	}

		/**
     * Returns user details for a specific id.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function details($id = NULL)
    {
		$restaurant = Restaurant::with('meals')->where('id', $id)->first();
		
		return view('admin/restaurant/details', ['restaurant' => $restaurant]);	
	}
	

	public function bulk($id = NULL)
    {
		$meals = Meal::get();
		//dd($meals);
		foreach($meals as $meal) {
			$count = RestaurantUser::where('restaurant_id', $meal->restaurant_id)->where('user_id', $meal->posted_by)->count();
			//echo $meal->restaurant_id." ".$meal->posted_by." ".$count."<br>";

			if($count == 0) {
				$restaurantUser = new RestaurantUser;
				$restaurantUser->restaurant_id = $meal->restaurant_id;
				$restaurantUser->user_id = $meal->posted_by;
				$restaurantUser->save();
			}
		}

		echo "Done...";
		
	}
	
	public function searchFourSquare(Request $request) {


		if(isset($request->ll)) {


			
			$latlng = explode(",", $request->ll);
			if(isset($latlng) && count($latlng) == 2) {

					
				try {

					$client = new \GuzzleHttp\Client();
					$url = "https://api.foursquare.com/v2/venues/search?ll=".$request->ll."&client_id=CCZQ3BJAUM2EOS3RQHR4ACWRUHQ1OWFEPJCNUB0TT0AWV4M2&client_secret=NTUCGL4CWXU35KZ04IXZH1F5VRL2I3JFW1H0MFKBSYSHWENB&v=20180228&categoryId=4d4b7105d754a06374d81259";
					$response = $client->request('GET', $url);
					
			
					if($response->getStatusCode() == 200) {
						///echo $response->getHeaderLine('content-type'); // 'application/json; charset=utf8'
						//$body = $response->getBody(); // '{"id": 1420053, "name": "guzzle", ...}'
						$body = json_decode($response->getBody());

						if(isset($body->response->venues)) {
							$venues = $body->response->venues;

							foreach($venues as $venue) {
								$place_category_id = NULL;

								if(isset($venue->categories)) {
									$categories = $venue->categories;

									foreach($categories as $category) {
										$placeCaregory = PlaceCategory::find($category->id);
										if(!isset($placeCaregory['id'])) {
											PlaceCategory::create([
												'id' => $category->id,
												'name' => $category->name												
											]);
										}
										else {
											$place_category_id = $placeCaregory['id'];
										}
									}
								}


								if(isset($venue->id) && isset($venue->name) && isset($venue->location)) 
								{

									$cnt = Place::where('id', $venue->id)->count();

									if($cnt == 0) {

										Place::create([
											'id' => $venue->id,
											'name' => $venue->name,
											'address' => isset($venue->location->address) ? $venue->location->address : NULL,
											'lat' => isset($venue->location->lat) ? $venue->location->lat : NULL,
											'lng' => isset($venue->location->lng) ? $venue->location->lng : NULL,
											'distance' => isset($venue->location->distance) ? $venue->location->distance : NULL,
											'city' => isset($venue->location->city) ? $venue->location->city : NULL,
											'state' => isset($venue->location->state) ? $venue->location->state : NULL,
											'country' => isset($venue->location->country) ? $venue->location->country : NULL,
											'postalCode' => isset($venue->location->postalCode) ? $venue->location->postalCode : NULL,
											'place_category_id' => $place_category_id
										]);
										
									}
									
								}
							}

						}

						// return response(array(
						// 	'success' => true,
						// 	'message' =>'Restaurant record synced with Four Square',
						// 	'errorDtl' => null
						// 	),200);

				
						// Send an asynchronous request.
						// $request = new \GuzzleHttp\Psr7\Request('GET', 'http://httpbin.org');
						// $promise = $client->sendAsync($request)->then(function ($response) {
						// 	echo 'I completed! ' . $response->getBody();
						// });
				
						// $promise->wait();
					


						if(isset($latlng) && count($latlng) == 2) {
							$radius = 10;
							if(isset($request->keyword)) {
								$sql = "SELECT * FROM (SELECT places.*, ($radius * acos(cos(radians($latlng[0])) * cos(radians(lat)) * cos(radians(lng) - radians($latlng[1])) + sin(radians($latlng[0])) * sin(radians(lat)))) AS dist FROM places) AS distances WHERE dist < $radius AND name LIKE '%".$request->keyword."%' ORDER BY dist";

							}
							else {
								$sql = "SELECT * FROM (SELECT places.*, ($radius * acos(cos(radians($latlng[0])) * cos(radians(lat)) * cos(radians(lng) - radians($latlng[1])) + sin(radians($latlng[0])) * sin(radians(lat)))) AS dist FROM places) AS distances WHERE dist < $radius ORDER BY dist";

							}

							// echo $sql;
							// exit;
							//$restaurants['current_page'] = 1;
							$restaurants = DB::select($sql);

							
							if(isset($request->keyword)) {
								$sql = "SELECT * FROM (SELECT restaurants.*, (".$radius." * acos(cos(radians(".$latlng[0].")) * cos(radians(place_lat)) * cos(radians(place_lng) - radians(".$latlng[1].")) + sin(radians(".$latlng[0].")) * sin(radians(place_lat)))) AS distance FROM restaurants) AS distances WHERE distance < ".$radius." AND place_name LIKE '%".$request->keyword."%' ORDER BY distance";
							}
							else {
								$sql = "SELECT * FROM (SELECT restaurants.*, (".$radius." * acos(cos(radians(".$latlng[0].")) * cos(radians(place_lat)) * cos(radians(place_lng) - radians(".$latlng[1].")) + sin(radians(".$latlng[0].")) * sin(radians(place_lat)))) AS distance FROM restaurants) AS distances WHERE distance < ".$radius." ORDER BY distance";
							
							}
							//$restaurants['current_page'] = 1;

							$restaurantsData = DB::select($sql);


							//dd($restaurantsData);

							foreach($restaurantsData as $datum) {


								if(isset($datum->place_name)) {

									$row['id'] = (string)$datum->id;
									$row['name'] = $datum->place_name;
									$row['address'] = isset($datum->place_street) ? $datum->place_street : NULL;
									$row['lat'] = isset($datum->place_lat) ? $datum->place_lat : NULL;
									$row['lng'] = isset($datum->place_lng) ? $datum->place_lng : NULL;
									$row['distance'] = isset($datum->distance) ? $datum->distance : NULL;
									$row['city'] = isset($datum->place_city) ? $datum->place_city : NULL;
									$row['state'] = isset($datum->place_state) ? $datum->place_state : NULL;
									$row['country'] = isset($datum->place_country) ? $datum->place_country : NULL;
									$row['postalCode'] = isset($datum->place_zipcode) ? $datum->place_zipcode : NULL;
									$row['place_category_id'] = NULL;
									array_push($restaurants, $row);
	
								}
								

							}
							
							
							$tempRestaurants = array_unique(array_column($restaurants, 'id'));
							$newRestaurants = array_intersect_key($restaurants, $tempRestaurants);

							array_multisort( array_column($newRestaurants, "distance"), SORT_ASC, $newRestaurants );
							
			
							return response(array(
								'success' => true,
								'message' =>'List of Restaurants has been found',
								'data' => $newRestaurants,
								'errorDtl' => null
								),200);
						}
						else {

							return response(array(
								'success' => false,
								'message' =>'Unable to find restaurants list',
								'data' => NULL,
								'errorDtl' => null
								),200);
						}

					}
					else {

						return response(array(
							'success' => false,
							'message' =>'Unable to find restaurants list',
							'data' => NULL,
							'errorDtl' => null
							),200);
					}
				

				} catch (\Exception $e) {
					return response(array(
						'success' => false,
						'message' => 'Unable to process request, database error occurred. '.$e
						),200);
				}	
			}
			else {

				return response(array(
					'success' => false,
					'message' =>'Unable to find restaurants list',
					'data' => NULL,
					'errorDtl' => null
					),200);
			}
		}
	}


	function cmp($a, $b)
	{
		if ($a["distance"] == $b["distance"]) {
			return 0;
		}
		return ($a["distance"] < $b["distance"]) ? -1 : 1;
	}
	
}

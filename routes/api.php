    <?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


Route::post('/user/register','UserController@register');
Route::post('/user/login','UserController@login');
Route::post('/user/update','UserController@update');
Route::post('/user/show','UserController@show');
Route::post('/user/delete','UserController@delete');
Route::post('/user/checkUserExists','UserController@checkUserExists');
Route::post('/user/socialSignup','UserController@socialSignup');
//Route::post('/user/facebookLogin','UserController@facebookLogin');
Route::post('/user/upload','UserController@upload');
Route::post('/user/change', 'UserController@changePassword');
Route::post('/user/follow', 'UserController@follow');
Route::post('/user/unfollow', 'UserController@unfollow');
Route::post('/user/followings', 'UserController@followings');
Route::post('/user/followers', 'UserController@followers');
Route::post('/user/followBulkUpdate', 'UserController@followBulkUpdate');
Route::post('/user/updateUsername','UserController@updateUsername');
Route::post('/user/recentlyActiveUsers','UserController@recentlyActiveUsers');
Route::post('/user/updateDeviceToken', 'UserController@updateDeviceToken');
Route::post('/user/block', 'UserController@block');
Route::post('/user/logout', 'UserController@logout');
Route::post('/user/updateStatus', array('uses'=>'UserController@updateStatus'));

Route::post('/location/countries','LocationController@countries');
Route::post('/location/states','LocationController@states');
Route::post('/location/cities','LocationController@cities');
Route::post('/location/cities','LocationController@cities');

Route::post('/meal/post','MealController@post');
Route::post('/meal/feed','MealController@feed');
Route::post('/meal/show','MealController@show');
Route::post('/meal/like','MealController@like');
Route::post('/meal/unlike','MealController@unlike');
Route::post('/meal/comment','MealController@comment');
Route::post('/meal/likes','MealController@likes');
Route::post('/meal/comments','MealController@comments');
Route::post('/meal/my','MealController@my');
Route::post('/meal/mostLikes','MealController@mostLikes');
Route::post('/meal/restaurant','MealController@restaurant');
Route::post('/restaurant/meals','MealController@mealsByRestaurant');
Route::post('/meal/flag','MealController@flag');
Route::post('/meal/delete','MealController@delete');

Route::post('/search','SearchController@index');
Route::post('/search/restaurants','SearchController@restaurants');
Route::post('/search/foursquare','SearchController@foursquare');

Route::post('/notifications','NotificationController@index');
Route::post('/notification/viewed','NotificationController@viewed');
Route::post('/restaurant/show','RestaurantController@show');
Route::get('/restaurant/locate','RestaurantController@searchFourSquare');

Route::post('/message/sendTest','MessageController@sendTest');
Route::post('/message/send','MessageController@send');
Route::post('/users/resetpass','UserController@resetPass');
Route::post('/user/updatepass','UserController@updatePass');
Route::post('/square/pay','SquareController@pay');

Route::post('/restaurant/search','RestaurantController@search');

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/

<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/

Route::get('/', 'HomeController@index');
Route::get('/privacy-policy', 'HomeController@privacy');
Route::get('/terms', 'HomeController@terms');
Route::post('/contact-us', 'HomeController@storeContactUs');
//Route::get('/bulkDeleteInactiveBizUsers', array('as' => 'deleteUsers',  'uses' => 'UserController@bulkDeleteInactiveBizUsers'));
Route::get('/deleteUsers',array('uses'=>'UserController@bulkDeleteInactiveBizUsers'));

// Back End Routes
Route::get('/admin', function () {
    return view('admin/dashboard');
})->middleware('auth');

Route::get('/dashboard', function () {
    return view('admin/dashboard');
})->middleware('auth');


Auth::routes();

Route::get('/admin/users', 'UserController@index')->middleware('auth');
Route::get('/admin/users/{type}', 'UserController@index')->middleware('auth');

Route::get('/admin/users/edit/{id}', 'UserController@edit')->middleware('auth');
Route::any('/admin/users/update/{id}', 'UserController@updateUser')->middleware('auth');
Route::any('/admin/users/password/{id}', 'UserController@editPass')->middleware('auth');
Route::get('/admin/users/analytics/{id}', 'UserController@analytics')->middleware('auth');
Route::get('/admin/users/destroy/{id}', 'UserController@destroy')->middleware('auth');
Route::get('/admin/users/update/bulk','UserController@bulkUpdateUsername')->middleware('auth');
Route::get('/admin/followers/delete/bulk','UserController@bulkDeleteFollowers')->middleware('auth');


Route::group(['middleware' => 'auth'], function()
{
    Route::resource('/admin/meals', 'MealController');
    Route::resource('/admin/users', 'UserController');
});

Route::get('/admin/meals/details/{type}/{id}', 'MealController@details')->middleware('auth');
Route::get('/admin/meals/delete/{id}', 'MealController@deleteSingle')->middleware('auth');
Route::get('logs', '\Rap2hpoutre\LaravelLogViewer\LogViewerController@index')->middleware('auth');;
Route::get('/admin/analytics/export/{id}', 'UserController@exportExcel')->name('analytics.export')->middleware('auth');
Route::get('/admin/restaurants', 'RestaurantController@index')->middleware('auth');
Route::get('/admin/restaurant/meals/{id}','RestaurantController@meals')->middleware('auth');
Route::get('/admin/restaurant/details/{id}','RestaurantController@details')->middleware('auth');
Route::get('/admin/restaurants/bulk','RestaurantController@bulk')->middleware('auth');
Route::get('/admin/meals/edit/{id}','MealController@edit')->middleware('auth');
Route::any('/admin/meals/update/{id}','MealController@update')->middleware('auth');
Route::get('/admin/messages/sendTest','MessageController@sendTest')->middleware('auth');


Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
 
    return "Cleared!";
 
});

 
Route::get('/images', 'HomeController@images');
//Route::resource('images', 'HomeController', ['only' => ['store', 'destroy']]);
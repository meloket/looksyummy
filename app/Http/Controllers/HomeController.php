<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use Hash;
use DB;
use Form;
use App\User;
use App\Meal;
use App\Follower;
use App\Notification;
use App\Restaurant;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;


class HomeController extends Controller
{
	public function index()
	{
		return view('frontend.home.index');
		//return view('frontend/home/index', ['users' => $users]);	
	}
	
	public function privacy()
	{
		return view('frontend.home.privacy');
		//return view('frontend/home/index', ['users' => $users]);	
	}

	public function terms()
	{
		return view('frontend.home.terms');
		//return view('frontend/home/index', ['users' => $users]);	
	}
	public function storeContactUs(Request $request)
    {

		$rules = [				
			
			'name' => 'required',
			'email' => 'required|email',
			'comment' => 'required',
		];

		$this->validate($request, $rules);
		
		
		$header = "From:".env('ADMIN_EMAIL', 'admin@eholiday.fr')." \r\n";
				$header .= "MIME-Version: 1.0\r\n";
				$header .= "Content-type: text/html\r\n";
				$header .= "Cc: ".$request['email']."\r\n";
		
		
		
		$message = '<h1>Web Contact Form</h1>
			<p>Dear Admin, <br /><br />

			Following user has sent an enquiry:</p>

			<table width="75%" border="1">
			<tr><td>Property Name</td><td>'.$request['property_name'].'</td></tr>			
			<tr><td>Name</td><td>'.$request['name'].'</td></tr>			
			<tr><td>Phone</td><td>'.$request['phone'].'</td></tr>
			<tr><td>Email</td><td>'.$request['email'].'</td></tr>
			<tr><td>Country</td><td>'.$request['country'].'</td></tr>
			<tr><td>Comments</td><td>'.$request['comment'].'</td></tr>
			</table>';
			
		$to = "contact@eholiday.fr";
		//$to = "dibs439@gmail.com";
		
		$v = mail($to , "A visitor has contacted you", $message, $header);
		if($v == 1)
			return redirect()->intended('/thanks');
	
		
		return view('frontend.contact');
		
	}
	
	public function images()
	{
		// https://looksyummyapp.s3.us-east-2.amazonaws.com/photos/meals/max/1552482289.png
		$picUrl = 'https://'. env('AWS_BUCKET') . '.s3.' . env('AWS_REGION') . '.amazonaws.com' . env('MEAL_PIC_MAX') . '1556049853.jpg';

		dd($picUrl);
       	$images = [];
       	/*$files = Storage::disk('s3')->files('images');
           foreach ($files as $file) {
               $images[] = [
                   'name' => str_replace('images/', '', $file),
                   'src' => $url . $file
               ];
		   }*/
		   

	   	return view('frontend.home.images', ['picUrl' => $picUrl]);
	   
		//return view('frontend.home.index');
		//return view('frontend/home/index', ['users' => $users]);	
	}
	
}

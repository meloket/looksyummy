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


class SquareController extends Controller
{
	public function pay(Request $request)
	{
		//require('vendor/connect-php-sdk/autoload.php');
		$input = $request->all();

		//dd($input);


		$rules = array(
            'user_id'       			=> 'required|numeric',
            'card_nonce' 				=> 'required',
            'amount' 					=> "required|regex:/^\d+(\.\d{1,2})?$/",
            'currency' 					=> 'required',
        );
		$validator = Validator::make($input, $rules);
		
		if ($validator->fails()) {
		
			$errors = $validator->errors();
			//return $errors->toJson();
			return response(array(
				'success' => false,
				'message' =>'Unable to charge payment',
				'errorDtl' => $validator->errors()->all()
				),200);
				
		}
		else {

			if(env('SQUARE_MODE') == "sandbox") {
				$access_token = env('SQUARE_ACCESS_TOKEN_SANDBOX');
				$location_id = env('SQUARE_LOCATION_ID_SANDBOX');
			}
				
			else {
				$access_token = env('SQUARE_ACCESS_TOKEN_LIVE');
				$location_id = env('SQUARE_LOCATION_ID_LIVE');
			}
				

			# setup authorization
			\SquareConnect\Configuration::getDefaultConfiguration()->setAccessToken($access_token);
			# create an instance of the Transaction API class
			$transactions_api = new \SquareConnect\Api\TransactionsApi();
			//$location_id = env('SQUARE_ACCESS_TOKEN_LIVE');
			$nonce = $input['card_nonce'];

			$request_body = array (
						"card_nonce" => $nonce,
						# Monetary amounts are specified in the smallest unit of the applicable currency.
						# This amount is in cents. It's also hard-coded for $1.00, which isn't very useful.
						"amount_money" => array (
						"amount" => $input['amount'] * 100,
						"currency" => $input['currency'],
					),
					# Every payment you process with the SDK must have a unique idempotency key.
					# If you're unsure whether a particular payment succeeded, you can reattempt
					# it with the same idempotency key without worrying about double charging
					# the buyer.
					"idempotency_key" => uniqid()
			);

			try {
					$result = $transactions_api->charge($location_id,  $request_body);

					
					//$array = json_decode(json_encode($result), true);
					//$array = json_decode(json_encode($result), true);

					
					return response(array(
						'success' => true,
						//'result' => $result->toArray(),
						'message' => 'Record found.',
						'errorDtl' => NULL
					), 200);

			} catch (\SquareConnect\ApiException $e) {
					//echo "Exception when calling TransactionApi->charge:";
					//var_dump($e->getResponseBody());
					return response(array(
						'success' => false,
						//'result' => NULL,
						'message' => 'Exception when calling TransactionApi->charge:',
						'errorDtl' => $e->getResponseBody()
						),200);

			}
		
		
		}
	}
	
}

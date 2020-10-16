<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use DB;
use Hash;

use FCM;
use App\User;
use App\Comment;
use App\Like;
use App\Meal;
use App\Restaurant;
use App\Follower;
use Illuminate\Support\Facades\Validator;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;


class MessageController extends Controller
{
	
	
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function sendTest(Request $request)
    {	

			$input = $request->all();

			//dd(env('FCM_SERVER_KEY'));
		/*$optionBuilder = new OptionsBuilder();
		$optionBuilder->setTimeToLive(60*20);

		$notificationBuilder = new PayloadNotificationBuilder('my title');
		$notificationBuilder->setBody('Hello world')
							->setSound('default');

		$dataBuilder = new PayloadDataBuilder();
		$dataBuilder->addData(['a_data' => 'my_data']);

		$option = $optionBuilder->build();
		$notification = $notificationBuilder->build();
		$data = $dataBuilder->build();

		$token = "efhsti6v-l0:APA91bEo6oJowVhhHr6vmuGSizLdxRxJderR5MF4UPJBJmXYjd63cdIxKP9SKKGVBOg0D_NjLzaYedkfVLD94M8sC-Td3VmoJ7CTsh_QyJkfouj9gzVPVNDDbP2S6nM-i3HAzTpRR8gT";

		$downstreamResponse = FCM::sendTo($token, $option, $notification, $data);

		$downstreamResponse->numberSuccess();
		$downstreamResponse->numberFailure();
		$downstreamResponse->numberModification();
		
		 

		//return Array - you must remove all this tokens in your database
		$downstreamResponse->tokensToDelete();

		//return Array (key : oldToken, value : new token - you must change the token in your database )
		$downstreamResponse->tokensToModify();

		//return Array - you should try to resend the message to the tokens in the array
		$downstreamResponse->tokensToRetry();

		// return Array (key:token, value:errror) - in production you should remove from your database the tokens*/
		
	
		/*$message = "This is a test";
		
		$registrationIds = $notification['device_token'];
		
		//echo $registrationIds;

		#prep the bundle
		 $msg = array
			  (
					'body' 	=> $message,
					'title'	=> 'Alert from CIT2ADM Helpdesk',
					'image'	=> 'myicon',  
					'sound' => 'http://webservices.ivisionr.com/templates/media/beep-01.wav' 
			  );*/
			  
			  
		$message = "This is a test message from Looksyummy";
		
		//$registrationIds = 'dUbCcuxIDZQ:APA91bE0zYA605ufLRk69JVKvD2o0l_eaxkymzYDYr-XjSxK-YvgAzXmVjW-cCIZGQ9RRVXM_ZpzG6LXIL3leAn7Mr0essbtdFAiGxwfYuZ04dF3JWbET21QcZ297eR7oIg0jO69XzU3';


		$registrationIds = $input['device_token'];
		
		//echo $registrationIds;

		#prep the bundle
		 $msg = array
			  (
					'body' 	=> $message,
					'title'	=> 'Alert from Looksyummy Helpdesk',
					'image'	=> 'myicon',  
					'sound' => env('AUDIO_FILE').'beep-01.wav' 
			  );

		$fields = array
				(
					'to'		=> $registrationIds,
					'notification'	=> $msg
				);


		$headers = array
				(
					'Authorization: key=' . env('FCM_SERVER_KEY'),
					'Content-Type: application/json'
				);
				
		

		#Send Reponse To FireBase Server	
		
		$ch = curl_init();
		curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
		curl_setopt( $ch,CURLOPT_POST, true );
		curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
		curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );

		
		curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
		//curl_setopt( $ch,CURLOPT_POSTFIELDS, $payload);
		
		$result = curl_exec($ch);
		
		echo $result;
		
		
		curl_close( $ch );
		
    }
	public function send($type, $recipientId, $senderId)
	{
		$senderUsername = "";
		$recipientUsername = "";
		$result = "";
		
		define('LIKE', 1);
		define('COMMENT', 2);
		define('FOLLOW', 3);
		
		$recipient = User::find($recipientId);
		$sender = User::find($senderId);
		
		if(isset($recipient->username) && $recipient->username != "")
			$recipientUsername = $recipient->username;
				
		if(isset($sender->username) && $sender->username != "")
			$senderUsername = $sender->username;
		
		if($type == FOLLOW)  
			$message = "[".$recipientUsername."] ".$senderUsername." is now following you";
		
		else if($type == LIKE)  
			$message = "[".$recipientUsername."] ".$senderUsername." has liked your post";
		
		else if($type == COMMENT)  
			$message = "[".$recipientUsername."] ".$senderUsername." has commented on your post";
		
		
		//dd($recipient->device_token);

		if(isset($recipient->device_token) && $recipient->device_token != "") {
			
			$registrationIds = $recipient->device_token;
		
			#prep the bundle
			$msg = array
				(
						'body' 	=> $message,
						'title'	=> 'Looksyummy App',
						'image'	=> 'myicon',  
						'sound' => env('AUDIO_FILE').'beep-01.wav'
				);

			$fields = array
					(
						'to'		=> $registrationIds,
						'notification'	=> $msg
					);


			$headers = array
					(
						'Authorization: key=' . env('FCM_SERVER_KEY'),
						'Content-Type: application/json'
					);

					

			/*$headers1 = array
			(
				'Authorization: key=' . env('FCM_SERVER_KEY_ANDROID'),
				'Content-Type: application/json'
			);
					
			$payload = '{
				"to" : "dUbCcuxIDZQ:APA91bE0zYA605ufLRk69JVKvD2o0l_eaxkymzYDYr-XjSxK-YvgAzXmVjW-cCIZGQ9RRVXM_ZpzG6LXIL3leAn7Mr0essbtdFAiGxwfYuZ04dF3JWbET21QcZ297eR7oIg0jO69XzU3",
				"data": {"title": "title","body": "body"},"notification" : {
				"body" : "great match!",
				"title" : "Portugal vs. Denmark",
				"icon" : "myicon" , "content_available": "true"},"priority": "high", "collapse_key": "notify_user"}';*/

			#Send Reponse To FireBase Server	

			//if(isset($recipient->device_type) && $recipient->device_type == "iOS") {
			
				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
				//curl_setopt( $ch,CURLOPT_POSTFIELDS, $payload);
				$result = curl_exec($ch);
				curl_close( $ch );
				$res = '1'; // device token found 
			/*}
			else if(isset($recipient->device_type) && $recipient->device_type == "Android") {
				$ch = curl_init();
				curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
				curl_setopt( $ch,CURLOPT_POST, true );
				curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers1 );
				curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
				curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
				curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
				//curl_setopt( $ch,CURLOPT_POSTFIELDS, $payload);
				$result = curl_exec($ch);
				curl_close( $ch );
				$res = '1'; // device token found 
			}
			else
				$res = '2'; // device token found 
			*/


			
		
		}
		else
			$res = '2'; // No device token found 
		
		//echo $result;
		
		
		
		
		return  $result;
		
	}
	
	
}

<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Requests;
use Auth;
use App\Country;
use App\State;
use App\City;
use Illuminate\Support\Facades\Validator;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function countries(Request $request)
    {
		
		try {
		
			$countries = Country::all();
			return response(array(
				'success' => true,
				'countries' =>$countries->toArray(),
			   ),200); 
			   
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
    public function states(Request $request)
    {
		$input = $request->all();
		$rules = array(
            'country_id'  => 'required|numeric',
			
        );
        $validator = Validator::make($input, $rules);
		if ($validator->fails()) {
			
			return response(array(
					'error' => false,
					'message' =>'Unable to get list of states',
					'errorDtl' => $validator->errors()->all()
				),200);
		}
		else {
	
		try {
		
			$states = State::where('country_id', $input['country_id'])->get();
			
			return response(array(
				'success' => true,
				'states' =>$states->toArray(),
			   ),200); 
			   
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
    public function cities(Request $request)
    {
		$input = $request->all();
	
		try {
		
			$cities = City::where('state_id', $input['state_id'])->get();
			return response(array(
				'success' => true,
				'cities' =>$cities->toArray(),
			   ),200); 
			   
		} catch (\Illuminate\Database\QueryException $e) {
			return response(array(
				'success' => false,
				'message' => 'Unable to process request, database error occurred.'
				),200);
		}
		
		   
    }

     
}

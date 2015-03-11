<?php

class PageController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|
	*/

	public function showHome() {
		return View::make('home');
	}
	public function makeMark() {
		return View::make('makemark');
	}
	public function postMark() {
    	if (Request::ajax()) {
    		if(Input::get('gapyear') == 0 && Input::get('edit') == 0) {
    			//Log::info("NO gapyear NO edit");
	    		$validator = Validator::make(
	    			array(
	    				'firstName' => Input::get('firstName'),
	    				'lastName' => Input::get('lastName'),
	    				'email' => Input::get('email'),
	    				'lockerNumber' => Input::get('lockerNumber'),
	    				'schoolName' => Input::get('schoolName'),
	    				'major' => Input::get('major'),
	    				),
	    			array(
	    				'firstName' => 'required|alpha',
	    				'lastName' => 'required|alpha',
	    				'email' => 'required|email|unique:users',
						'lockerNumber' => 'required|integer|unique:users',
						'schoolName' => 'required|alpha_spaces',
						'major' => 'required',
	    				)
	    			);
    		}
    		elseif(Input::get('gapyear') == 0 && Input::get('edit') == 1) {
    			//Log::info("NO gapyear YES edit");
	    		$validator = Validator::make(
	    			array(
	    				'email' => Input::get('email'),
	    				'lockerNumber' => Input::get('lockerNumber'),
	    				'schoolName' => Input::get('schoolName'),
	    				'major' => Input::get('major'),
	    				),
	    			array(
	    				'email' => 'required|email',
						'lockerNumber' => 'required|integer',
						'schoolName' => 'required|alpha_spaces',
						'major' => 'required',
	    				)
	    			);
    		}
    		elseif(Input::get('gapyear') == 1 && Input::get('edit') == 0) {
    			//Log::info("YES gapyear NO edit");
	    		$validator = Validator::make(
	    			array(
	    				'firstName' => Input::get('firstName'),
	    				'lastName' => Input::get('lastName'),
	    				'email' => Input::get('email'),
	    				'lockerNumber' => Input::get('lockerNumber'),
	    				'countryName' => Input::get('countryName'),
	    				'schoolName' => Input::get('schoolName'),
	    				'major' => Input::get('major'),
	    				),
	    			array(
	    				'firstName' => 'required|alpha',
	    				'lastName' => 'required|alpha',
	    				'email' => 'required|email|unique:users',
						'lockerNumber' => 'required|integer|unique:users',
						'countryName' => 'required|alpha_spaces',
						'schoolName' => 'alpha_spaces',
						'major' => '',
	    				)
	    			);  			
    		}
    		elseif(Input::get('gapyear') == 1 && Input::get('edit') == 1) {
    			//Log::info("YES gapyear YES edit");    			
	    		$validator = Validator::make(
	    			array(
	    				'email' => Input::get('email'),
	    				'lockerNumber' => Input::get('lockerNumber'),
	    				'countryName' => Input::get('countryName'),
	    				'schoolName' => Input::get('schoolName'),
	    				'major' => Input::get('major'),
	    				),
	    			array(
	    				'email' => 'required|email',
						'lockerNumber' => 'required|integer',
						'countryName' => 'required|alpha_spaces',
						'schoolName' => 'alpha_spaces',
						'major' => '',
	    				)
	    			);  			
    		}
    		if ($validator->fails()) {
    			$response = array('status' => 'danger', 'text' => $validator->messages());
    		}
    		else {
    			$response = array('status' => 'danger', 'text' => $validator->messages());
    			// $user = new User;
    			// $user->puid = Crypt::encrypt(Input::get('puid'));
    			// $user->email = Input::get('email');
    			// $user->password = Hash::make(Input::get('password'));
    			// $user->save();

    			// $user = User::find($user->id);
    			// Auth::login($user);
    			// $response = array('status' => 'success', 'text' => 'Success');
    		}
    		return Response::json($response); 
    		exit();
    	}
	}
}
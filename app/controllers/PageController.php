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
						'lockerNumber' => 'required|integer|unique:users,locker',
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
						'lockerNumber' => 'required|integer|unique:users,locker',
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
				if(Input::get('edit') == 1) {
    				// Make sure that the combination of email and locker exists
					if(User::where('email', '=', Input::get('email'))->where('locker', '=', Input::get('lockerNumber'))->exists()) {
    					// Update user
						$user = User::where('email', '=', Input::get('email'))->where('locker', '=', Input::get('lockerNumber'))->first();
						$user->school = Input::get('schoolName');
						$user->major = Input::get('major');
						if(Input::get('gapyear') == 1) {
							$user->country = Input::get('countryName');
						}
						else {
							$user->country = NULL;
						}
						$user->touch();
						$user->save();
						$response = array('status' => 'success', 'text' => $user->id);		
					}

    				// Send authentication error
					else {
						$response = array('status' => 'badauth', 'text' => 'Your combination of email address and locker number doesn\'t exist. Please check the your information again.');
					}
				}

    			// If the user is not editing an entry
				else {
					$user = new User;
					$user->email = Input::get('email');
					$user->firstname = Input::get('firstName');
					$user->lastname = Input::get('lastName');
					$user->locker = Input::get('lockerNumber');
					$user->school = Input::get('schoolName');
					$user->major = Input::get('major');
					if(Input::get('gapyear') == 1) {
						$user->country = Input::get('countryName');
					}
					$user->save();
					$response = array('status' => 'success', 'text' => $user->id);
				}
			}
			return Response::json($response); 
			exit();
		}
	}
	public function processInfo() {
		if (Request::ajax()) {
			$id = Input::get("id");
			$user = User::find($id);
			$college = str_replace(" ", "_", $user->school);
			$college = str_replace("-", "%E2%80%93", $college);
			$user->description = $this->getWikiDescription($college);
			$user->image = $this->getWikiImage($college);
			$user->save();
		}
		return $this->getWikiImage($college);
		//return Response::json($response); 
		exit();
	}

	# Haversine Formula to find distance between two geographic points
	function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
		$earth_radius = 6371;
		$dLat = deg2rad($latitude2 - $latitude1);
		$dLon = deg2rad($longitude2 - $longitude1);
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
		$c = 2 * asin(sqrt($a));
		$d = $earth_radius * $c;
		return $d;
	}
	public function getWikiDescription($college) {
		urlencode($url = "http://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exintro=&titles=".$college."&continue");
		// cURL stuff
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "http://kedarv.org.uk");
		$c = curl_exec($ch);

		$json = json_decode($c,true);
		var_dump($json);
		var_dump($url);
		$pagearray = $json['query']['pages'];
		$pageid = key($pagearray);
		$description = substr(trim(preg_replace('/\s+/', ' ', htmlspecialchars($pagearray[$pageid]['extract'], ENT_QUOTES))), 0, 900);
		return $description;
	}
	public function getWikiImage($college) {
		urlencode ($url = "http://en.wikipedia.org/w/api.php?action=query&titles=".$college."&prop=pageimages&format=json&pithumbsize=200&redirects");
		// cURL stuff
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "http://kedarv.org.uk");
		$c = curl_exec($ch);
		$json = json_decode($c,true);
		$imgarray = $json['query']['pages'];
		$imgpageid = key($imgarray);
		$imglink = $imgarray[$imgpageid]['thumbnail']['source'];
		return $imglink;
	}
}
<?php

class PageController extends BaseController {
	public function showHome() {
		$data = User::where('lat', '!=', '')->where('lng', '!=', '')->get(array('school', 'lat', 'lng', 'firstname', 'lastname', 'description', 'image', 'country', 'prefix', 'studyabroad'))->toArray();
		return View::make('home', compact('data'));
	}
	public function makeMark() {
		return View::make('makemark');
	}
	public function showAdvice() {
		return View::make('advice');
	}
	public function postMark() {
		if (Request::ajax()) {
			/**
			*  We have six different cases for validation, all of which need different rules. There are three 'base' cases
			*  First we check if the form is being edited. If it is, we throw away the firstName and lastName rules
			*  Case 1: User is not going on a gap year
			*  Case 2: User is going on a gap year, and is NOT studying abroad
			*  Case 3: User is going on a gap year, and IS studying abroad
			*  All three cases are present for both larger cases, editing and not editing.
			*/
			if(Input::get('edit') == 0) {
				if(Input::get('gapyear') == 0) {
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
				elseif(Input::get('gapyear') == 1 && Input::get('studyAbroad') == 0) {
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
				elseif(Input::get('gapyear') == 1 && Input::get('studyAbroad') == 1) {
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
							'schoolName' => 'required|alpha_spaces',
							'major' => 'required',
						)
					);
				}
			}
			elseif(Input::get('edit') == 1) {
				if(Input::get('gapyear') == 0) {
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
				elseif(Input::get('gapyear') == 1 && Input::get('studyAbroad') == 0) {
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
				elseif(Input::get('gapyear') == 1 && Input::get('studyAbroad') == 1) {
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
							'schoolName' => 'required|alpha_spaces',
							'major' => 'required',
						)
					);
				}
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
							if(Input::get('studyAbroad') == 1) {
								$user->studyabroad = 1;
							}
							else {
								$user->studyabroad = 0;
							}
						}
						else {
							$user->country = NULL;
							$user->studyabroad = 0;
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
					$user->studyabroad = 0;
					if(Input::get('gapyear') == 1) {
						$user->country = Input::get('countryName');
						if(Input::get('studyAbroad') == 1) {
							$user->studyabroad = 1;
						}
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
			if($user->country == "") {
				$string = str_replace(" ", "+", $user->school);
			}
			elseif($user->country != "" && $user->school != "") {
				$string = str_replace(" ", "+", $user->school);
			}
			else {
				$string = str_replace(" ", "+", $user->country);
			}
			$geocodeArray = $this->lookup($string);
			$user->lat = $geocodeArray['lat'];
			$user->lng = $geocodeArray['lng'];
			if($user->country == "") {
				$wikiString = str_replace(" ", "_", $geocodeArray['propername']);
				$wikiString = str_replace("-", "%E2%80%93", $wikiString);
				$user->state = $geocodeArray['state'];
				$user->school = $geocodeArray['propername'];
			}
			elseif($user->country != "" && $user->school != "") {
				$wikiString = str_replace(" ", "_", $geocodeArray['propername']);
				$wikiString = str_replace("-", "%E2%80%93", $wikiString);
				$user->state = $geocodeArray['state'];
				$user->school = $geocodeArray['propername'];
			}
			else {
				$wikiString = str_replace(" ", "_", $user->country);
				$wikiString = str_replace("-", "%E2%80%93", $wikiString);
			}
			$user->description = $this->getWikiDescription($wikiString);
			$user->image = $this->getWikiImage($wikiString);
			$user->milesfromhome = $this->getDistance($geocodeArray['lat'], $geocodeArray['lng'], 40.101952, -88.227161) * .62137;
			if(isset($geocodeArray['propername'])) {
				$arr = explode(' ',trim($geocodeArray['propername']));
				if (strpos($arr[0], 'University') !== false || strpos($arr[0],'College') !== false) {
					$user->prefix = "the";
				}
				else {
					$user->prefix = "";
				}
			}
			if($user->firstrun == 0) {
				$data = array(
					"email" => $user->email,
					"firstname" => $user->firstname,
					"lastname" => $user->lastname,
				);
				Mail::send('email.welcome', compact('data'), function($message) use ($user) {
					$message->to($user->email, $user->firstname . ' ' . $user->lastname)->subject('Welcome to CollegeMapper');
				});
				$user->firstrun = 1;
			}
			$user->save();
			$response = array('status' => 'success');
		}
		else {
			$response = array('status' => 'error', 'text' => 'bad request');
		}
		return Response::json($response); 
		exit();
	}
	public function stats() {
		$data = "";
		return View::make('stats', compact('data'));
	}
	/**
	*  Haversine Formula to find distance between two geographic points
	*  @param double $latitude1 First latitude coordinate
	*  @param double $longitude1 First longitude coordinate
	*  @param double $latitude2 Second latitude coordinate
	*  @param double $longitude2 Second longitude coordinate
	*  @return double distance between two coordinates in kilometers
	*/
	public function getDistance($latitude1, $longitude1, $latitude2, $longitude2) {
		$earth_radius = 6371;
		$dLat = deg2rad($latitude2 - $latitude1);
		$dLon = deg2rad($longitude2 - $longitude1);
		$a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($latitude1)) * cos(deg2rad($latitude2)) * sin($dLon/2) * sin($dLon/2);
		$c = 2 * asin(sqrt($a));
		$d = $earth_radius * $c;
		return $d;
	}
	/**
	* Fetches the first 900 characters of a Wikipedia page
	* @param String $college Name of the college, must be properly formatted
	* @return First 900 characters 
	*/
	public function getWikiDescription($college) {
		$url = "http://en.wikipedia.org/w/api.php?action=query&prop=extracts&format=json&exintro=&titles=".$college."&continue";
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "http://kedarv.org.uk");
		$c = curl_exec($ch);
		curl_close($ch);
		//var_dump($url);
		$json = json_decode($c,true);
		$pagearray = $json['query']['pages'];
		$pageid = key($pagearray);
		$description = substr(trim(preg_replace('/\s+/', ' ', htmlspecialchars($pagearray[$pageid]['extract'], ENT_QUOTES))), 0, 900);
		if(strlen($description) == 900) {
			$description = $description . "...";
		}
		return $description;
	}
	/**
	* Fetches the first image of a Wikipedia page
	* @param String $college Name of the college, must be properly formatted
	* @return Link to image 
	*/
	public function getWikiImage($college) {
		$url = "http://en.wikipedia.org/w/api.php?action=query&titles=".$college."&prop=pageimages&format=json&pithumbsize=200&redirects";
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "http://kedarv.org.uk");
		$c = curl_exec($ch);

		$json = json_decode($c,true);
		curl_close($ch);
		$imgarray = $json['query']['pages'];
		$imgpageid = key($imgarray);
		$imglink = $imgarray[$imgpageid]['thumbnail']['source'];
		return $imglink;
	}
	/**
	* Fetches the first image of a Wikipedia page
	* @param String $string Name of the college, must be properly formatted
	* @return Array containing lat, lng, state, country, propername 
	*/
	public function lookup($string){
		$url = "http://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";
		$ch = curl_init($url);
		curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt ($ch, CURLOPT_USERAGENT, "http://kedarv.org.uk");
		$c = curl_exec($ch);
		$response = json_decode($c,true);
		curl_close($ch);
		$latitude = $response['results'][0]['geometry']['location']['lat'];
		$longitude = $response['results'][0]['geometry']['location']['lng'];
		$location_array = array();
		foreach ($response["results"] as $result) {
			foreach ($result["address_components"] as $address) {
				if (in_array("administrative_area_level_1", $address["types"])) {
					$state = $address["long_name"];
					$location_array["state"] = $state;
				}
				if (in_array("country", $address["types"])) {
					$country = $address["long_name"];
				}
				if (in_array("establishment", $address["types"])) {
					$propername = $address["long_name"];
					$location_array["propername"] = $propername;
				}
			}
			break;
		}
		$location_array["lat"] = $latitude;
		$location_array["lng"] = $longitude;
		$location_array["country"] = $country;

		return $location_array;
	}
}
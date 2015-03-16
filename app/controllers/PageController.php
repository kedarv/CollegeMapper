<?php

class PageController extends BaseController {
	public function showHome() {
		$data = User::where('lat', '!=', '')->where('lng', '!=', '')->get(array('school', 'lat', 'lng', 'firstname', 'lastname', 'description', 'image', 'country', 'prefix'))->toArray();
		return View::make('home', compact('data'));
	}
	public function makeMark() {
		return View::make('makemark');
	}
	public function postMark() {
		if (Request::ajax()) {
			/* 
			*  We have four different cases for validation, all of which need different rules
			*  1. User is not editing an entry, and is not on a gap year
			*  2. User is editing an entry, and is not on a gap year
			*  3. User is not editing an entry, and is on a gap year
			*  4. User is editing an entry, and is on a gap year
			*/
			if(Input::get('gapyear') == 0 && Input::get('edit') == 0) {
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
			$string = str_replace(" ", "+", $user->school);
			$geocodeArray = $this->lookup($string);
			$college = str_replace(" ", "_", $geocodeArray['propername']);
			$college = str_replace("-", "%E2%80%93", $college);
			$user->lat = $geocodeArray['lat'];
			$user->lng = $geocodeArray['lng'];
			if($user->country == "") {
				$user->state = $geocodeArray['state'];
			}
			$user->school = $geocodeArray['propername'];
			$user->description = $this->getWikiDescription($college);
			$user->image = $this->getWikiImage($college);
			$user->milesfromhome = $this->getDistance($geocodeArray['lat'], $geocodeArray['lng'], 40.101952, -88.227161) * .62137;
			$arr = explode(' ',trim($geocodeArray['propername']));
			if (strpos($arr[0], 'University') !== false || strpos($arr[0],'College') !== false) {
				$user->prefix = "the";
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

	# Haversine Formula to find distance between two geographic points
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
		foreach ($response["results"] as $result) {
			foreach ($result["address_components"] as $address) {
				if (in_array("administrative_area_level_1", $address["types"])) {
					$state = $address["long_name"];
				}
				if (in_array("country", $address["types"])) {
					$country = $address["long_name"];
				}
				if (in_array("establishment", $address["types"])) {
					$propername = $address["long_name"];
				}
			}
			break;
		}
		$location_array = array(
			"lat" => $latitude,
			"lng" => $longitude,
			"state" => $state,
			"country" => $country,
			"propername" => $propername,
		);
		return $location_array;
	}
}
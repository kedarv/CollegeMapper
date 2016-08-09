<?php
use Goutte\Client;
class PageController extends BaseController {
	public function showHome() {
		$data = User::where('lat', '!=', '')->where('lng', '!=', '')->get(array('school', 'lat', 'lng', 'firstname', 'lastname', 'description', 'image', 'country', 'prefix', 'studyabroad'))->toArray();
		return View::make('home', compact('data'));
	}
	public function makeMark() {
		$data = Category::with('majors')->get();
		return View::make('makemark', compact('data'));
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
							'second_major' => Input::get('second_major'),
						),
						array(
							'firstName' => 'required|alpha',
							'lastName' => 'required|alpha_dash',
							'email' => 'required|email|unique:users',
							'lockerNumber' => 'required|integer|unique:users,locker',
							'schoolName' => 'required|alpha_spaces',
							'major' => 'required',
							'second_major' => '',
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
							'second_major' => Input::get('second_major'),
						),
						array(
							'firstName' => 'required|alpha',
							'lastName' => 'required|alpha_dash',
							'email' => 'required|email|unique:users',
							'lockerNumber' => 'required|integer|unique:users,locker',
							'countryName' => 'required|alpha_spaces',
							'schoolName' => 'alpha_spaces',
							'major' => '',
							'second_major' => '',
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
							'second_major' => Input::get('second_major'),
						),
						array(
							'firstName' => 'required|alpha',
							'lastName' => 'required|alpha_dash',
							'email' => 'required|email|unique:users',
							'lockerNumber' => 'required|integer|unique:users,locker',
							'countryName' => 'required|alpha_spaces',
							'schoolName' => 'required|alpha_spaces',
							'major' => 'required',
							'second_major' => '',
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
							'second_major' => Input::get('second_major'),
						),
						array(
							'email' => 'required|email',
							'lockerNumber' => 'required|integer',
							'schoolName' => 'required|alpha_spaces',
							'major' => 'required',
							'second_major' => '',
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
							'second_major' => Input::get('second_major'),
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
							'second_major' => Input::get('second_major'),
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
						if(Input::get('second_major') !== NULL) {
							$user->major = Input::get('major') . "#" . Input::get('second_major');
						}
						else {
							$user->major = Input::get('major');
						}
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
					if(Input::get('second_major') !== NULL) {
						$user->major = Input::get('major') . "#" . Input::get('second_major');
					}
					else {
						$user->major = Input::get('major');
					}
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
			$add = (mt_rand(0, 10))/600;
			$geocodeArray = $this->lookupViaText($string);
			if(count($geocodeArray) == 0) { // If lookup fails
				$response = array('status' => 'error', 'text' => 'Unable to lookup via text');
				return Response::json($response); 
				exit();
			}
			$lookupAddress = $this->lookupViaAddress($geocodeArray['formatted_address']);
			if(count($lookupAddress) == 0) { // If lookup fails
				$response = array('status' => 'error', 'text' => 'Unable to look up via address');
				return Response::json($response); 
				exit();
			}
			$user->lat = $geocodeArray['lat'] + $add;
			$user->lng = $geocodeArray['lng'] + $add;
			if($user->country == "") {
				$wikiString = str_replace(" ", "_", $geocodeArray['propername']);
				$wikiString = str_replace("-", "%E2%80%93", $wikiString);
				if(array_key_exists('state', $lookupAddress)) {
					$user->state = $lookupAddress['state'];
				}
				else {
					$user->state = "";
				}
				$user->school = $geocodeArray['propername'];
			}
			elseif($user->country != "" && $user->school != "") {
				$wikiString = str_replace(" ", "_", $geocodeArray['propername']);
				$wikiString = str_replace("-", "%E2%80%93", $wikiString);
				if(array_key_exists('state', $lookupAddress)) {
					$user->state = $lookupAddress['state'];
				}
				else {
					$user->state = "";
				}
				$user->school = $geocodeArray['propername'];
			}
			else {
				$wikiString = str_replace(" ", "_", $user->country);
				$wikiString = str_replace("-", "%E2%80%93", $wikiString);
			}
			$user->description = $this->getWikiDescription($wikiString);

			$img = $this->getWikiImageByScraping($wikiString);
			if(empty($img)) {
				$img = $this->getWikiImageByAPI($wikiString);
			}
			$user->image = $img;

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
				// Mail::send('email.welcome', compact('data'), function($message) use ($user) {
				// 	$message->to($user->email, $user->firstname . ' ' . $user->lastname)->subject('Welcome to CollegeMapper');
				// });
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
		$query = User::where('lat', '!=', '')->where('lng', '!=', '')->get(array('school', 'firstname', 'lastname', 'major', 'milesfromhome', 'state', 'country', 'studyabroad'))->toArray();
		if(count($query) == 0) {
			return "No entries yet.";
		}
		$counter = 0;
		$list = array();
		$list['states'] = array();
		$list['colleges'] = array();
		$list['majors'] = array();

		# Drilldown arrays
		$list['engineering'] = array();
		$list['other'] = array();
		$list['artscience'] = array();
		$list['businesslaw'] = array();
		$list['edumed'] = array();
		
		# Iterate through all the users
		foreach($query as $row) {
			if(!empty($row['state'])) {
				$list['states'][] = $row['state'];
			}
			if(!empty($row['school'])) {
				$list['colleges'][] = $row['school'];
			}
			if(!empty($row['major'])) {
				if(strpos($row['major'], '#') !== false) {
					$double_majors = explode("#", $row['major']);
					$list['major'][] = $double_majors[0];
					$list['major'][] = $double_majors[1];
					$row['major'] = $double_majors[0];
					$query[$counter]['major'] = $double_majors[0] . " &amp; " . $double_majors[1];
				}
				else {
					$list['major'][] = $row['major'];
				}
			}
			elseif($row['major'] == "") {
				$list['major'][] = "Gap Year";
			}
			$counter++;
		}
		$engineering = Major::where('category', '=', 1)->get(array('name'));
		$artscience = Major::where('category', '=', 2)->get(array('name'));
		$business = Major::where('category', '=', 3)->get(array('name'));
		$edumed = Major::where('category', '=', 4)->get(array('name'));
		$nocategory = Major::where('category', '=', 5)->get(array('name'));

		foreach($engineering as $item) {
			$list['engineering'][] = $item->name;
		}
		foreach($artscience as $item) {
			$list['artscience'][] = $item->name;
		}
		foreach($business as $item) {
			$list['businesslaw'][] = $item->name;
		}
		foreach($edumed as $item) {
			$list['edumed'][] = $item->name;
		}
		foreach($nocategory as $item) {
			$list['other'][] = $item->name;
		}

		$counts = array();
		$counts['states'] = array_count_values($list['states']);
		$counts['colleges'] = array_count_values($list['colleges']);
		$counts['majors'] = array_count_values($list['major']);

		uasort($counts['states'], array($this, "sortByOrder"));
		uasort($counts['colleges'], array($this, "sortByOrder"));
		uasort($counts['majors'], array($this, "sortByOrder"));

		# Drilldown
		$counts['engineering'] = array_count_values($list['engineering']);
		$counts['other'] = array_count_values($list['other']);
		$counts['artscience'] = array_count_values($list['artscience']);
		$counts['businesslaw'] = array_count_values($list['businesslaw']);
		$counts['edumed'] = array_count_values($list['edumed']);

		uasort($counts['engineering'], array($this, "sortByOrder"));
		uasort($counts['other'], array($this, "sortByOrder"));
		uasort($counts['artscience'], array($this, "sortByOrder"));
		uasort($counts['businesslaw'], array($this, "sortByOrder"));
		uasort($counts['edumed'], array($this, "sortByOrder"));

		return View::make('stats', compact('query', 'list', 'counts'));
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
		$client = new \GuzzleHttp\Client();
		$response = $client->get($url);
		$location_array = array();
		$json = $response->json();

		$pagearray = $json['query']['pages'];
		$pageid = key($pagearray);
		$description = "";
		if (array_key_exists('extract', $pagearray[$pageid])) {
			$description = substr(trim(preg_replace('/\s+/', ' ', htmlspecialchars($pagearray[$pageid]['extract'], ENT_QUOTES))), 0, 900);
			if(strlen($description) == 900) {
				$description = $description . "...";
			}
		}
		return $description;
	}

	public function getWikiImageByScraping($college) {
		$url = "https://en.wikipedia.org/wiki/".$college."";
		$client = new Client();
		$client->getClient()->setDefaultOption('verify', false);
		// Request login page
		$crawler = $client->request('GET', $url);
		// $crawler = $crawler->filterXPath('//*[@id="mw-content-text"]/table[1]');
		$crawler = $crawler->filter('.infobox');

		return $crawler->filter('img')->first()->attr('src');
	}

	/**
	* Fetches the first image of a Wikipedia page
	* @param String $college Name of the college, must be properly formatted
	* @return Link to image 
	*/
	public function getWikiImageByAPI($college) {
		$url = "http://en.wikipedia.org/w/api.php?action=query&titles=".$college."&prop=pageimages&format=json&pithumbsize=200&redirects";
		$client = new \GuzzleHttp\Client();
		$response = $client->get($url);
		$location_array = array();
		$json = $response->json();

		$imgarray = $json['query']['pages'];
		$imgpageid = key($imgarray);
		$imglink = "";
		if (array_key_exists('thumbnail', $imgarray[$imgpageid])) {
			$imglink = $imgarray[$imgpageid]['thumbnail']['source'];
		}
		return $imglink;
	}

	/**
	* Fetches the latitude and longitude of a college given text
	* @param String $string Name of the college, must be properly formatted
	* @return Array containing lat, lng, state, country, propername 
	*/
	public function lookupViaText($string){
		$url = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=".$string."&key=AIzaSyBDfQ68a8TPCGeePmMJERWSRzP74UdisP4";
		$client = new \GuzzleHttp\Client();
		$response = $client->get($url);
		$location_array = array();
		$json = $response->json();
		if($json['status'] == "OK") {
			$location_array['lat'] = $json['results'][0]['geometry']['location']['lat'];
			$location_array['lng'] = $json['results'][0]['geometry']['location']['lng'];
			$location_array['propername'] = $json['results'][0]['name'];
			$location_array['formatted_address'] = $json['results'][0]['formatted_address'];
		}
		return $location_array;
	}

	/**
	* Fetches the latitude and longitude of a college given address
	* @param String $string Name of the college, must be properly formatted
	* @return Array containing lat, lng, state, country, propername 
	*/
	public function lookupViaAddress($string){
		$url = "https://maps.googleapis.com/maps/api/geocode/json?address=".$string."&sensor=false";
		$client = new \GuzzleHttp\Client();
		$response = $client->get($url);
		$location_array = array();
		$json = $response->json();

		$latitude = $json['results'][0]['geometry']['location']['lat'];
		$longitude = $json['results'][0]['geometry']['location']['lng'];
		$location_array = array();
		foreach ($json["results"] as $result) {
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
		$location_array["status"] = 1;
		$location_array["lat"] = $latitude;
		$location_array["lng"] = $longitude;
		$location_array["country"] = $country;

		return $location_array;
	}
	public function sortByOrder($a, $b) {
    	return $b - $a;
	}
}
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CollegeMapper</title>
	{{ HTML::style('css/bootstrap.min.css')}}
	<style>
		html { height: 100% }
		body { height: 100%; margin: 0px; padding: 0px }
		#map { height: 100% }
		hr {
			margin: 7px;
		}
		.btn-desktop {
			width: 80px;
			text-align: center;
		}
		.btn-mobile {
			width: 60px;
			text-align: center;
		}
   </style>
</head>
<body>
	<!-- Mobile Buttons -->
	<a data-toggle="modal" href="#myModal" class="btn-mobile btn btn-primary btn-md hidden-md hidden-lg" style="position: absolute; bottom: 20px; right: 10px; z-index: 99;">Menu</a>
	<a data-toggle="modal" href="{{action('PageController@stats')}}" class="btn-mobile btn btn-info btn-md hidden-md hidden-lg" style="position: absolute; bottom: 55px; right: 10px; z-index: 99;">Stats</a>

	<!-- Desktop and Tablet Buttons -->
	<a data-toggle="modal" href="#myModal" class="btn-desktop btn btn-primary btn-lg btn-lg hidden-xs hidden-sm" style="position: absolute; bottom: 20px; right: 10px; z-index: 99;">Menu</a>	
	<a data-toggle="modal" href="{{action('PageController@stats')}}" class="btn-desktop btn btn-info btn-lg hidden-xs hidden-sm" style="position: absolute; bottom: 70px; right: 10px; z-index: 99;">Stats</a>
	
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
	<div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title">Welcome to CollegeMapper</h4>
        </div>
	<div class="modal-body">
			<ul class="nav nav-tabs" id="navTabs">
				<li class="active"><a href="#welcome" data-toggle="tab">Introduction</a></li>
				<li><a href="#add" id="add">Add Marker</a></li>
				<li><a href="#faq" data-toggle="tab">FAQ</a></li>
			</ul>
		<div class="tab-content">
			<div class="tab-pane fade in active" id="welcome">
				<br/>
				<h1 style="margin:5px;">Uni High Class of {{Config::get('app.year')}} Map</h1>
				To view entries, press the red marker. You can view information about the college by pressing the "View University Info" link.<br/>
				<br/>
				<small>App by <a href="http://github.com/kedarv">@kedarv.</a> Use of any content without permission is forbidden.</small>
			</div>
			<div class="tab-pane fade" id="faq">
				<br/>
				<b>Q.</b> Who wrote this app?<br/>
				<b>A.</b> <a href="http://kedarv.org.uk">Kedar,</a> a Uni High Class of 2014 grad, currently attending Purdue University.
				<hr/>
				<b>Q.</b> How is this data collected?<br/>
				<b>A.</b> Students willingly provide this information, and all data collected is stored permanently.
				<hr/>
				<b>Q.</b> How do you get the location of the University?<br/>
				<b>A.</b> Google Geocoding API provides address to lat/long conversion.
				<hr/>
				<b>Q.</b> How do you get the University Information?<br/>
				<b>A.</b> The Wikimedia API is utilized to scrape information and images from Wikipedia.
				<hr/>
				<b>Q.</b> What languages were used to build this application?<br/>
				<b>A.</b> PHP, JavaScript, and MySQL. Built with Laravel 4.2. This application also uses jQuery, Google Geocoding API, Google Maps API, and Wikimedia API.
				<hr/>
				<b>App running CollegeMapper v1.0</b>
				<hr/>
				Thanks for using my application!
			</div>
		</div>
	</div>
	<div class="modal-footer">
		<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	</div>
	</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
	<div class="modal fade" id="infoModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
		<div class="modal-content">
		  <div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h4 class="modal-title info-title" id="myModalLabel"></h4>
		  </div>
		  <div class="modal-body">
			<div class="text-center">
				<img src="#" id="logo" alt="Logo">
			</div>
			<br/>
			 <div id="myInfo"></div>
			 <br/>
			 <a class="wiki" target="_blank">Continue reading on Wikipedia&raquo;</a>
		  </div>
		  <div class="modal-footer no-top-margin">
			<button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
		  </div>
		</div>
	  </div>
	</div>
	<div id="map"></div> 
	{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')}}
	{{ HTML::script('js/bootstrap.min.js')}}
	{{ HTML::script('http://maps.google.com/maps/api/js?sensor=true')}}
	{{ HTML::script('js/gmaps.js')}}
	{{ HTML::script('js/markerclusterer.js')}}
	<script type="text/javascript">
		var map;
		$(document).ready(function(){
			map = new GMaps({
			div: '#map',
			lat: 40.5,
			lng: -96,
			markerClusterer: function(map) {
				return new MarkerClusterer(map);
			}
		  	});
			@foreach ($data as $row)
				map.addMarker({
				lat: {{$row['lat']}},
				lng: {{$row['lng']}},
				title: '{{$row['firstname']}} {{substr($row['lastname'], 0, 1)}} - Click for more Info',
				animation: google.maps.Animation.DROP,
				infoWindow: {
					@if($row['country'] == "")
						content: '{{$row['firstname']}} {{substr($row['lastname'], 0, 1)}}. will attend {{$row['prefix']}} {{$row['school']}}' +
							'<hr/><a href="#infoModal" class="uniinfo" data-id="{{$row['description']}}" data-name="{{$row['school']}}" data-img="{{$row['image']}}">Show University Info &raquo;</a>'
					@elseif($row['country'] != "" && $row['school'] == "")
						content: '{{$row['firstname']}} {{substr($row['lastname'], 0, 1)}}. will take a gap year in {{$row['country']}}' +
							'<hr/><a href="#infoModal" class="uniinfo" data-id="{{$row['description']}}" data-name="{{$row['country']}}" data-img="{{$row['image']}}">Show Country Info &raquo;</a>'
					@elseif($row['country'] != "" && $row['school'] != "" && $row['studyabroad'] == 0)
						content: '{{$row['firstname']}} {{substr($row['lastname'], 0, 1)}}. will take a gap year in {{$row['country']}}, and then attend {{$row['prefix']}} {{$row['school']}}' +
							'<hr/><a href="#infoModal" class="uniinfo" data-id="{{$row['description']}}" data-name="{{$row['school']}}" data-img="{{$row['image']}}">Show University Info &raquo;</a>'
					@elseif($row['country'] != "" && $row['school'] != "" && $row['studyabroad'] == 1)
						content: '{{$row['firstname']}} {{substr($row['lastname'], 0, 1)}}. will study abroad in {{$row['country']}}, attending {{$row['prefix']}} {{$row['school']}}' +
							'<hr/><a href="#infoModal" class="uniinfo" data-id="{{$row['description']}}" data-name="{{$row['school']}}" data-img="{{$row['image']}}">Show University Info &raquo;</a>'
					@endif
				}
			});
			@endforeach
			// Zoom the map out so the entire US is visible
 			map.setZoom(5); 
		});
		
		$('#navTabs a').click(function (e) {
			e.preventDefault()
			$(this).tab('show')
		});
		$('#add').click(function(){
			window.location.href = "{{action('PageController@makeMark')}}";
		});

		$(document).on('click', '.uniinfo', function(){ 
			var myID = $(this).data('id');
			var myName = $(this).data('name');
			var myLogo = $(this).data('img');
			// Replace spaces with underscore
			var linkName = myName.replace(/ /g, '_');
			// Replace dashes with ascii code
			var linkName = linkName.replace(/-/g, '%E2%80%93');

			$('#infoModal').modal('show');
			$(".modal-body #myInfo").html(myID);
			$("#logo").attr("src", myLogo);
			$(".info-title").html(myName);
			$(".wiki").attr("href", "http://en.wikipedia.org/wiki/" + linkName)
		});
	</script>
</body>
</html>
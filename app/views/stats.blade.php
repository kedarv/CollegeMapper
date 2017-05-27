<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Uni High Class of {{Config::get('app.year')}} |  Stats</title>
	{{ HTML::style('css/bootstrap.min.css')}}
	{{HTML::style('css/stats.css')}}
	<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300' rel='stylesheet' type='text/css'>
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">
<div class="jumbotron">
<h1 class="heading-text">Uni High Class of <span style="font-weight:400;">{{Config::get('app.year')}}</span></h1>
Displaying {{count($query)}} rows.
<ol class="breadcrumb" style="margin-bottom: 10px; background: #fff">
  <li><a href="http://2014.kedarv.com">Class of 2014</a></li>
  <li><a href="http://2015.kedarv.com">Class of 2015</a></li>
  <li><a href="http://2016.kedarv.com">Class of 2016</a></li>
  <li><a href="http://2017.kedarv.com">Class of 2017</a></li>
</ol>
<div class="table-responsive">
	<table class="table table-bordered table-condensed table-striped tablesorter" id="table">
		<thead>
			<tr>
				<th class="tablesorter filter" data-placeholder="Search Name">Name</th>
				<th class="tablesorter filter" data-placeholder="Search University/College">University/College</th>
				<th class="tablesorter filter" data-placeholder="Search Major">Major</th>
				<th class="filter-false" style="width:180px;">Distance from Home</th>
			</tr>
		</thead>
		<tbody>
		@foreach($query as $row)
			<tr>
				<td>{{$row['firstname']}} {{substr($row['lastname'], 0, 1)}}.</td>
				@if($row['country'] != "" && $row['studyabroad'] == 0 && $row['school'] == "")
					<td>Gap year ({{$row['country']}})</td>
					<td>-</td>
				@elseif($row['country'] != "" && $row['studyabroad'] == 0 && $row['school'] != "")
					<td>Gap year ({{$row['country']}}) then <a href="http://en.wikipedia.org/wiki/{{str_replace(" ", "_", $row['school'])}}">{{$row['school']}}</a></td>
					<td>{{$row['major']}}</td>
				@elseif($row['country'] != "" && $row['studyabroad'] == 1)
					<td>Studying abroad ({{$row['country']}}) at {{$row['school']}}</td>
					<td>{{$row['major']}}</td>
				@else
					<td><a href="http://en.wikipedia.org/wiki/{{str_replace(" ", "_", $row['school'])}}">{{$row['school']}}</a></td>
					<td>{{$row['major']}}</td>				
				@endif
				@if($row['school'] == "University of Illinois at Urbana-Champaign")
				<td><span class="dist hidden">0</span><img src="http://upload.wikimedia.org/wikipedia/en/thumb/3/3a/UIUC_I_mark.svg/18px-UIUC_I_mark.svg.png" class="img-responsive" alt="UofI"></td>
				@elseif($row['school'] == "Purdue University")
				<td><span class="dist">{{$row['milesfromhome']}}</span> Miles {{ HTML::image("img/purdue.png", "Purdue", array('class' => 'img-responsive', 'style' => 'float:right')) }}</td>
				@else
				<td><span class="dist">{{$row['milesfromhome']}}</span> Miles</td>
				@endif
			</tr>
		@endforeach
		</tbody>
	</table>
	<hr/>
	<div id="state" style="height: 400px; margin: 0 auto"></div>
	<hr/>
	<div id="university" style="height: 500px; margin: 0 auto"></div>
	<hr/>
	<div id="majordrilldown" style="height: 400px; margin: 0 auto"></div>
	<hr/>
	<div id="major" style="height: 400px; margin: 0 auto"></div>
	<br/>
</div>
</div>
</div>
<div class="col-md-6">
	<a href="http://kedarv.com" class="visible_link">@kedarv</a>
</div>
<div class="col-md-6">
	<span class="pull-right">
		<a href="{{action('PageController@showHome')}}" class="visible_link">Map</a>
	</span>
</div>
</div>
<br/>
<br/>
</div>
{{HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')}}
{{HTML::script('http://code.highcharts.com/highcharts.js')}}
{{HTML::script('http://code.highcharts.com/modules/drilldown.js')}}
{{HTML::script('js/tablesorter.min.js')}}
{{HTML::script('js/tablesorter.widgets.min.js')}}
<script>
$(document).ready(function(){ 
	$("#table").tablesorter({
		theme: 'bootstrap',
		headerTemplate : '{content}{icon}',
		widgets: ['uitheme', 'filter'],
		sortList: [[0,0]],		
		textExtraction: function(node) { 
			var $n = $(node), $p = $n.find('.dist');
			return $p.length ? $p.text() : $n.text(); 
		},
		emptyTo: 'bottom',
		widgetOptions : {
			filter_ignoreCase : true,
			filter_liveSearch : true,
		}
	}); 
}); 
</script>
		<script type="text/javascript">
		$(function () {
			$('#state').highcharts({
				chart: {
					plotBackgroundColor: null,
					plotBorderWidth: null,
					plotShadow: false
				},
				title: {
					text: 'Most Popular States'
				},
				tooltip: {
					pointFormat: 'Number of People: <b>{point.y}</b>'
				},
				plotOptions: {
					pie: {
						allowPointSelect: true,
						cursor: 'pointer',
						dataLabels: {
							enabled: true,
							color: '#000000',
							connectorColor: '#000000',
							format: '<b>{point.name}</b>: {point.percentage:.1f} %'
						}
					}
				},
				series: [{
					type: 'pie',
					name: 'Percent',
					data: [
						@foreach ($counts['states'] as $key => $value)
							['{{$key}}', {{$value}}],
						@endforeach
					
					]
				}],
				credits: {
					text: 'Kedar Vaidya',
					href: 'http://www.kedarv.com'
				}
			});
		});
	$(function () {
        $('#university').highcharts({
            chart: {
                type: 'column',
                margin: [ 50, 50, 100, 80]
            },
            title: {
                text: 'Most Popular Universities'
            },
            xAxis: {
                categories: [
					@foreach ($counts['colleges'] as $key =>$value)
						@if($value > 1)
							'{{$key}}',
						@endif
					@endforeach
                ],
                labels: {
                    rotation: 0,
                    align: 'center',
                    style: {
                        fontSize: '12px',
                        fontFamily: 'Verdana, sans-serif'
                    }
                }
            },
            yAxis: {
                min: 0,
				allowDecimals: false,
                title: {
                    text: 'Number of People'
                }
            },
            legend: {
                enabled: false
            },
            series: [{
                name: 'Number of People',
                data: [
					@foreach ($counts['colleges'] as $key =>$value)
						@if($value > 1)
							{{$value}}, 
						@endif
					@endforeach
				],
                dataLabels: {
                    enabled: true,
                    rotation: 0,
                    color: '#FFFFFF',
                    align: 'center',
                    x: 4,
                    y: 20,
                    style: {
                        fontSize: '13px',
                        fontFamily: 'Verdana, sans-serif',
                        textShadow: '0 0 3px black'
                    }
                }
            }],
			credits: {
				text: 'Kedar Vaidya',
				href: 'http://www.kedarv.com'
			}
        });
    });
	$(function () {
		$('#major').highcharts({
			chart: {
				plotBackgroundColor: null,
				plotBorderWidth: null,
				plotShadow: false
			},
			title: {
				text: 'Individual Majors'
			},
			tooltip: {
				pointFormat: 'Number of People: <b>{point.y}</b>'
			},
			plotOptions: {
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000000',
						connectorColor: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					}
				}
			},
			series: [{
				type: 'pie',
				name: 'Percent',
				data: [
					@foreach ($counts['majors'] as $key => $value)
						 ['{{$key}}', {{$value}}], 
					@endforeach
				]
			}],
			credits: {
				text: 'Kedar Vaidya',
				href: 'http://www.kedarv.com'
			}
			});
		});
		
	$(function () {    
		$('#majordrilldown').highcharts({
			chart: {
				type: 'pie'
			},
			title: {
				text: 'Major Groups (click section to expand)'
			},
			xAxis: {
				type: 'category'
			},

			legend: {
				enabled: false
			},

			plotOptions: {
				series: {
					borderWidth: 0,
					dataLabels: {
						enabled: true,
					}
				},
				pie: {
					allowPointSelect: true,
					cursor: 'pointer',
					dataLabels: {
						enabled: true,
						color: '#000000',
						format: '<b>{point.name}</b>: {point.percentage:.1f} %'
					}
				}
			},

			series: [{
				name: 'Majors',
				colorByPoint: true,
				data: [
				@if(count($list['engineering']) > 0)
				{
					name: 'Engineering',
					y: {{count($list['engineering'])}},
					drilldown: 'engineering'
				},
				@endif
				@if(count($list['artscience']) > 0)
				{
					name: 'Arts and Sciences',
					y: {{count($list['artscience'])}},
					drilldown: 'artscience'
				},
				@endif
				@if(count($list['businesslaw']) > 0)
				{
					name: 'Business and Law',
					y: {{count($list['businesslaw'])}},
					drilldown: 'buslaw'
				},
				@endif
				@if(count($list['edumed']) > 0)
				{
					name: 'Education and Medicine',
					y: {{count($list['edumed'])}},
					drilldown: 'edumed'
				},
				@endif
				@if(count($list['other']) > 0)
				{
					name: 'Other',
					y: {{count($list['other'])}},
					drilldown: 'other'
				}
				@endif
				],
				tooltip: {
						pointFormat: 'Number of People: <b>{point.y}</b>'
				},
			}],
			drilldown: {
				series: [
				@if(count($list['engineering']) > 0)
				{
					id: 'engineering',
					data: [
						@foreach ($counts['engineering'] as $key => $value)
							['{{ucfirst($key)}}', {{$value}}],
						@endforeach
					],
					name: 'Engineering',
					tooltip: {
						pointFormat: 'Number of People: <b>{point.y}</b>'
					},
				},
				@endif
				@if(count($list['artscience']) > 0)
				{
					id: 'artscience',
					data: [
						@foreach ($counts['artscience'] as $key => $value)
							['{{ucfirst($key)}}', {{$value}}], 
						@endforeach
					],
					name: 'Arts and Sciences',
					tooltip: {
						pointFormat: 'Number of People: <b>{point.y}</b>'
					},
				},
				@endif
				@if(count($list['businesslaw']) > 0)
				{
					id: 'buslaw',
					data: [
						@foreach ($counts['businesslaw'] as $key => $value)
							['{{ucfirst($key)}}', {{$value}}], 
						@endforeach
					],
					name: 'Business and Law',
					tooltip: {
						pointFormat: 'Number of People: <b>{point.y}</b>'
					},
				},
				@endif
				@if(count($list['edumed']) > 0)
				{
					id: 'edumed',
					data: [
						@foreach ($counts['edumed'] as $key => $value)
							['{{ucfirst($key)}}', {{$value}}], 
						@endforeach
					],
					name: 'Education and Medicine',
					tooltip: {
						pointFormat: 'Number of People: <b>{point.y}</b>'
					},
				},
				@endif
				@if(count($list['other']) > 0)
				{
					id: 'other',
					data: [
						@foreach ($counts['other'] as $key => $value)
							['{{ucfirst($key)}}', {{$value}}], 
						@endforeach
					],
					name: 'Other',
					tooltip: {
						pointFormat: 'Number of People: <b>{point.y}</b>'
					},
				}
				@endif	
				]
			},
			credits: {
				text: 'Kedar Vaidya',
				href: 'http://www.kedarv.com'
			}
		})
	});
	</script>
</body>
</html>
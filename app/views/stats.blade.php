<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CollegeMapper  |  Stats</title>
	{{ HTML::style('css/bootstrap.min.css')}}
	{{HTML::style('css/stats.css')}}
</head>
<body>
<div class="container">
<div class="row">
<div class="col-md-12">

<div class="table-responsive">
	<table class="table table-bordered table-condensed table-striped tablesorter" id="table">
		<thead>
			<tr>
				<th class="tablesorter filter" data-placeholder="Search Name">Name</th>
				<th class="tablesorter filter" data-placeholder="Search University/College">University/College</th>
				<th class="tablesorter filter" data-placeholder="Search Major">Major</th>
				<th class="filter-false" style="width:150px;">Distance from Home</th>
			</tr>
		</thead>
		<tbody>
		@foreach($query as $row)
			<tr>
				<td>{{$row['firstname']}}</td>
				@if($row['country'] != "" && $row['studyabroad'] == 0 && $row['school'] == "")
					<td>Gap year ({{$row['country']}})</td>
					<td>-</td>
				@elseif($row['country'] != "" && $row['studyabroad'] == 0 && $row['school'] != "")
					<td>Gap year ({{$row['country']}}) then {{$row['school']}}</td>
					<td>{{$row['major']}}</td>
				@elseif($row['country'] != "" && $row['studyabroad'] == 1)
					<td>Studying abroad ({{$row['country']}}) at {{$row['school']}}</td>
					<td>{{$row['major']}}</td>
				@else
					<td>{{$row['school']}}</td>
					<td>{{$row['major']}}</td>				
				@endif
				<td><span class="dist">{{$row['milesfromhome']}}</span> Miles</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
</div>
</div>
</div>
{{HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')}}
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
</body>
</html>
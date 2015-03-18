<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CollegeMapper  |  Stats</title>
	{{ HTML::style('css/bootstrap.min.css')}}
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
				<td>{{$row['milesfromhome']}}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>
</div>
</div>
</div>
</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CollegeMapper</title>
	{{ HTML::style('css/bootstrap.min.css')}}
	{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')}}
	{{ HTML::script('js/bootstrap.min.js')}}
</head>
<body>
{{link_to_action('PageController@makeMark', 'Add Marker')}}
{{var_dump($data)}}
@foreach ($data as $user)

@endforeach
</body>
</html>

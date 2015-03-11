<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>CollegeMapper</title>
	{{ HTML::style('css/bootstrap.min.css')}}
	{{ HTML::style('css/style.css')}}
</head>
<body>
	<div class="container">
		<hr/>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="well well2">
					<h1>make your <b>mark</b>er.</h1>
					<hr/>
					{{ Form::open(array('id' => 'create')) }}
					<h3>Personal Identification.</h3>
					<hr/>
					<div class="row">
						<div class="col-xs-6">
							<div class="form-group">
								{{Form::label('fname', 'First Name')}}
								{{Form::text('fname', null, array('class' => 'form-control', 'placeholder' => 'First Name'))}}
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								{{Form::label('lname', 'Last Name')}}
								{{Form::text('lname', null, array('class' => 'form-control', 'placeholder' => 'Last Name'))}}
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-8">
							<div class="form-group">
								{{Form::label('email', 'Email')}}
								{{Form::email('email', null, array('class' => 'form-control', 'placeholder' => 'Email'))}}
							</div>
						</div>
						<div class="col-xs-4">
							<div class="form-group">
								{{Form::label('lnumber', 'Locker #')}}
								{{Form::email('lnumber', null, array('class' => 'form-control', 'placeholder' => 'Locker #'))}}
							</div>
						</div>
					</div>
					<hr/>
					<h3>College Plans.</h3>
					<hr/>
						<a href="#" id="show_gapyear">I'm going on a gap year &raquo;</a>
						<a href="#" id="hide_gapyear" style="display:none;">Never mind, hide gap year &raquo;</a>
					<hr/>
					<div class="row" id="gapyear_input" style="display:none;">
						<div class="col-xs-12">
							<div class="form-group">
								{{Form::label('cname', 'What Country will you be in?')}}
								<small>Bon Voyage!</small>
								{{Form::text('cname', null, array('class' => 'form-control', 'placeholder' => 'Name of Country'))}}
							</div>
						</div>
					</div>
					<div class="row" id="college_input">
						<div class="col-xs-12" id="gap_text" style="display:none;">
							<div class="alert alert-info">If you know where you'll be attending after your gap year, put it below! If not, just leave it blank.</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								{{Form::label('cname', 'Name of School')}}
								<small>Must be the official name</small>
								{{Form::text('cname', null, array('class' => 'form-control', 'placeholder' => 'Name of School'))}}
							</div>
						</div>
						<div class="col-xs-12">
							<div class="form-group">
								{{Form::label('major', 'Major')}}
								{{Form::text('major', null, array('class' => 'form-control', 'placeholder' => 'Major'))}}
							</div>
						</div>
					</div>
				</div>
				{{ Form::close() }}
			</div>
		</div>
		<hr/>
	</div>
	{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')}}
	<script>
		$(document).ready(function() {
			$("#show_gapyear").click(function() {
				event.preventDefault();	
				$("#gap_text").slideToggle();
				$("#gapyear_input").slideToggle();		
				$("#show_gapyear").toggle();
				$("#hide_gapyear").toggle();			
			});
			$("#hide_gapyear").click(function() {
				event.preventDefault();
				$("#gap_text").slideToggle();
				$("#gapyear_input").slideToggle();				
				$("#show_gapyear").toggle();
				$("#hide_gapyear").toggle();
			});
		});
	</script>
</body>
</html>
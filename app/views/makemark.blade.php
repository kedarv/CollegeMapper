<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="apple-mobile-web-app-capable" content="yes">
	<title>CollegeMapper</title>
	{{ HTML::style('css/bootstrap.min.css')}}
	{{ HTML::style('css/style.css')}}
	{{ HTML::style('css/sweet-alert.css')}}
</head>
<body>
	<div class="barloader">
		<div class="green"></div>
		<div class="red"></div>
		<div class="blue"></div>
		<div class="yellow"></div>
	</div>
	<div class="container">
		<br/>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="well well2">
					<div id="form_content">
						<h1>Make your <b>mark</b>er.</h1>
						<hr/>
						{{Form::open(array('action' => 'PageController@postMark', 'id' => 'create'))}}
						<h3>Personal Identification.</h3>
						<a href="#" id="hide_ident">I already did this, edit my entry &raquo;</a>
						<a href="#" id="show_ident" style="display:none;">Oops, go back! &raquo;</a>
						<hr/>
						<div class="row">
							<div class="col-xs-6" id="fname-container">
								<div class="form-group">
									{{Form::label('firstName', 'First Name')}}
									{{Form::text('firstName', null, array('class' => 'form-control', 'placeholder' => 'First Name'))}}
								</div>
							</div>
							<div class="col-xs-6" id="lname-container">
								<div class="form-group">
									{{Form::label('lastName', 'Last Name')}}
									{{Form::text('lastName', null, array('class' => 'form-control', 'placeholder' => 'Last Name'))}}
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-12" id="authenticate" style="display:none;">
								<div class="alert alert-info">Please fill in the fields below to authenticate your request.</div>
							</div>
							<div class="col-xs-8">
								<div class="form-group" id="email_container">
									{{Form::label('email', 'Email')}}
									{{Form::email('email', null, array('class' => 'form-control', 'placeholder' => 'Email'))}}
								</div>
							</div>
							<div class="col-xs-4">
								<div class="form-group" id="locker_container">
									{{Form::label('lockerNumber', 'Locker #')}}
									{{Form::text('lockerNumber', null, array('class' => 'form-control', 'placeholder' => '#'))}}
								</div>
							</div>
						</div>
						<hr/>
						<h3>College Plans.</h3>
						<a href="#" id="show_gapyear">I'm going on a gap year &raquo;</a>
						<a href="#" id="hide_gapyear" style="display:none;">Never mind, hide gap year &raquo;</a>
						<hr/>
						<div class="row" id="gapyear_input" style="display:none;">
							<div class="col-xs-12">
								<div class="form-group">
									{{Form::label('countryName', 'What Country will you be in?')}}
									<small>Bon Voyage!</small>
									{{Form::text('countryName', null, array('class' => 'form-control', 'placeholder' => 'Name of Country'))}}
								</div>
							</div>
							<div class="col-xs-12">
								<div class="checkbox">
									<label>
										{{Form::checkbox('studyAbroad', 1, null, array('id' => 'studyabroad'))}} Will you be studying abroad?
									</label>
								</div>
							</div>
						</div>
						<div class="row" id="college_input">
							<div class="col-xs-12" id="gap_text" style="display:none;">
								<div class="alert alert-info">If you know where you'll be attending after your gap year, put it below! If not, just leave it blank.</div>
							</div>
							<div class="col-xs-12">
								<div class="form-group">
									{{Form::label('schoolName', 'Name of School')}}
									<small>Must be the official name</small>
									{{Form::text('schoolName', null, array('class' => 'form-control', 'placeholder' => 'Name of School'))}}
								</div>
							</div>
							<div class="col-xs-12">
								<div class="form-group">
									{{Form::label('major', 'Major')}}
						<select class="form-control" id="major" name="major">
						  <option value="Undecided">Undecided</option>
						  <optgroup label="Engineering">
							<option value="General Engineering">General Engineering</option>
							<option value="Chemical/Bio Engineering">Chemical/Bio Engineering</option>
							<option value="Biomedical Engineering">Biomedical Engineering</option>
							<option value="Civil Engineering">Civil Engineering</option>
							<option value="Environmental Engineering">Environmental Engineering</option>
							<option value="Electrical/Computer Engineering">Electrical/Computer Engineering</option>
							<option value="Computer Science">Computer Science</option>
							<option value="Mechanical Engineering">Mechanical Engineering</option>
							<option value="Aerospace Engineering">Aerospace Engineering</option>
							<option value="General/Undecided Engineering">General/Undecided Engineering</option>
						  </optgroup>
						  <optgroup label="Arts and Sciences">
							<option value="Kinesiology">Kinesiology</option>
							<option value="Biology">Biology</option>
							<option value="Chemistry">Chemistry</option>
							<option value="Biochemistry">Biochemistry</option>
							<option value="Communication">Communication</option>
							<option value="Foreign Language">Foreign Language</option>
							<option value="Anthropology">Anthropology</option>
							<option value="Psychology">Psychology</option>
							<option value="Sociology">Sociology</option>
							<option value="Political Science">Political Science</option>
							<option value="Architecture">Architecture</option>
							<option value="Agriculture and Consumer Economics">Agriculture and Consumer Economics</option>
							<option value="English">English</option>
							<option value="History">History</option>
							<option value="Music">Music</option>
							<option value="Music Cognition">Music Cognition</option>
							<option value="Theatre">Theatre</option>
						  </optgroup>
						  <optgroup label="Business">
							<option value="Business">Business</option>
							<option value="International Business">International Business</option>
							<option value="Economics">Economics</option>
						  </optgroup>
						  <optgroup label="No Category">
						  <option value="Education">Education</option>
						  <option value="Law">Law</option>
						  <option value="Media">Media</option>
						  <option value="Medicine">Medicine</option>
						  <option value="Public Health">Public Health</option>
						  <option value="General">General/Other</option>
						  <option value="Gap Year">Gap Year</option>
						  <option value="Study Abroad">Study Abroad</option>
						  </optgroup>
						</select>								</div>
							</div>
						</div>
						<hr/>
					</div>
					<div id="loader" style="display:none;">
						<h1>Working...</h1>
						<hr/>
						<div class="spinner"></div>
					</div>
					{{ Form::submit('Submit', array('class' => 'btn btn-primary btn-lg', 'id' => 'submitbtn')) }}
				</div>
				{{ Form::hidden('edit', '0', array('id' => 'edit'))}}
				{{ Form::hidden('gapyear', '0', array('id' => 'gap_year_field'))}}
				{{ Form::close() }}
			</div>
		</div>
		<hr/>
		<div class="col-md-6">
			<a href="http://github.com/kedarv">@kedarv</a>
		</div>
		<div class="col-md-6">
			<span class="pull-right">
				<a href="{{action('PageController@showHome')}}">Back to Map</a>
			</span>
		</div>
		<br/>
		<br/>
	</div>
	{{ HTML::script('https://ajax.googleapis.com/ajax/libs/jquery/2.1.3/jquery.min.js')}}
	{{ HTML::script('js/sweet-alert.min.js')}}
	{{ HTML::script('js/chosen.jquery.min.js')}}
	<script>
		$(document).ready(function() {
			$("#major").chosen()
			$('form').submit(function(e){
				e.preventDefault();
				var $form = $( this ),
				dataFrom = $form.serialize(),
				url = $form.attr( "action"),
				method = $form.attr( "method" );
				$('#error').fadeOut("fast");
				jQuery(".form-group").removeClass('has-error has-success').addClass('has-success');	
				$.ajax({
					url: "{{action('PageController@postMark')}}",
					data: dataFrom,
					type: method,
					beforeSend: function(request) {
						return request.setRequestHeader('X-CSRF-Token', $("meta[name='token']").attr('content'));
					},
					success: function (response) {
						var errors = "";
						if (response['status'] == 'success') {
							$('.green').addClass('sd0');
							$('.red').addClass('sd05');
							$('.blue').addClass('sd1');
							$('.yellow').addClass('sd15');     			
							$("#loader").slideToggle();
							$("#form_content").slideUp();
							$("#submitbtn").slideToggle();							
							$.ajax({
					            method: 'post',				            
					            data: {id : response['text']},
					            url: '{{action('PageController@processInfo')}}',
					            success: function (response) {
					            	if(response['status'] == 'success') {
					            		window.location.href = "{{action('PageController@showHome')}}";
					            	}
					            }
					        }); 
						}
						else if(response['status'] == 'badauth') {
							$("#email_container").removeClass("has-success has-error").addClass("has-error");
							$("#locker_container").removeClass("has-success has-error").addClass("has-error");
							swal({
								type: "warning",
								title: "Oops!",
								text: response['text'],
								confirmButtonText: "OK",
								allowOutsideClick: true
							});
							//console.log("info");
						}
						else {
							$.each( response['text'], function( index, value ){
								jQuery("#" + index).parent('div').addClass('has-error');
								errors += (value  + "\n");
							})
							swal({
								title: "Error!",
								text: errors,
								type: "error",
								confirmButtonText: "OK",
								allowOutsideClick: true
							});
						}
						//console.log(response['text']);
					}
				});
			});
			
			$("#studyabroad").click(function() {
				$("#gap_text").slideToggle();
			});
			$("#show_gapyear").click(function() {
				event.preventDefault();
				$("#gap_year_field").val(1);
				if($("#gap_text").is(":hidden") && !($("#studyabroad").is(':checked'))) {
					$("#gap_text").slideToggle();
				}
				$("#gapyear_input").slideToggle();		
				$("#show_gapyear").toggle();
				$("#hide_gapyear").toggle();			
			});
			$("#hide_gapyear").click(function() {
				event.preventDefault();
				if($("#gap_text").is(":visible")) {
					$("#gap_text").slideToggle();
				}
				$("#gap_year_field").val(0);
				$("#gapyear_input").slideToggle();				
				$("#show_gapyear").toggle();
				$("#hide_gapyear").toggle();
			});
			$("#hide_ident").click(function() {
				event.preventDefault();
				$("#edit").val(1);
				$("#authenticate").slideToggle();
				$("#fname-container").slideToggle();
				$("#lname-container").slideToggle();
				$("#hide_ident").toggle();
				$("#show_ident").toggle();
			});	
			$("#show_ident").click(function() {
				event.preventDefault();
				$("#edit").val(0);
				$("#authenticate").slideToggle();
				$("#fname-container").slideToggle();
				$("#lname-container").slideToggle();
				$("#hide_ident").toggle();
				$("#show_ident").toggle();
			});	
		});
	</script>
</body>
</html>
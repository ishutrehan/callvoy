@extends('layouts.app')

@section('title'){{ Lang::get('users.edit_profile') }} - @stop

@section('css_style')
<link rel="stylesheet" type="text/css" href="{{ asset('protected/public/css_theme/bootstrap-tokenfield.css') }}">
@stop

@section('content')

     <?php
 	use App\Models\AdminSettings;
	use App\Models\Notifications;
	use App\Models\Messages;

     // ** Admin Settings ** //
     $settings = AdminSettings::first();

	 // ** Data User logged ** //
     $user = Auth::user();
/*echo "<pre>";

print_r($user);
echo "</pre>";*/
     ?>
 <style>
 	input[type="checkbox"]{
 		width: auto !important;
 		height: auto !important;
 		position: static !important;
 	}
 </style>
<div class="container">
	<div class="row">
		<div class="col-xl-8">

	     	 <!--********* panel panel-default ***************-->
	     	<div class="panel panel-default">
			  <div class="panel-heading grid-panel-title"> 

			  	<h2>
			  		{{ Lang::get('users.edit_profile') }}
			  	</h2><!-- **btn-block ** -->

			  </div><!-- ** panel-heading ** -->

			 <div class="panel-body"> 

				<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('account') }}" enctype="multipart/form-data">
				<input type="hidden" name="old_video" value="{{ $user->intro_video }}" />
				@csrf

				 @if (Session::has('notification'))
				<div class="alert alert-success btn-sm" role="alert">
					<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		{{ Session::get('notification') }} <i class="fa fa-long-arrow-right"></i> <a href="{{ URL::to('@') }}{{ $user->username }}">{{ Lang::get('users.go_to_profile') }}</a>
	            		</div>
	            	@endif

				 <div class="form-group @if( $errors->first('full_name') ) has-error @endif">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.full_name_misc') }}</label>
				    <div class="col-sm-10">
				      <input type="text" value="{{ e( $user->name ) }}" name="full_name" class="form-control input-sm" id="full_name" placeholder="{{ Lang::get('misc.full_name_misc') }}">

				@if( $errors->first("full_name") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("full_name")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div><!-- **** form-group **** -->

				  <div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.username_misc') }}
				    		<i class="icon-question" title="{{ Lang::get('misc.username_no_edit') }}"></i>
				    	</label>
				    <div class="col-sm-10">
				      <input id="disabledInput" disabled="disabled" type="text" value="{{ $user->username }}" class="form-control input-sm" placeholder="{{ Lang::get('misc.username_misc') }}">
				    </div>
				  </div><!-- **** form-group **** -->

				  <div class="form-group @if( $errors->first('email') ) has-error @endif">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('auth.email') }}</label>
				    <div class="col-sm-10">
				      <input type="text" value="{{ $user->email }}" name="email" class="form-control input-sm" id="email" placeholder="{{ Lang::get('auth.email') }}">

				    @if( $errors->first("email") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("email")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div><!-- **** form-group **** -->
			  <?php /*
				  <div class="form-group @if( $errors->first('location') ) has-error @endif">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.location') }}</label>
				    <div class="col-sm-10">
				      <input type="text" value="{{ e( $user->location ) }}" class="form-control input-sm" name="location" id="location" placeholder="{{ Lang::get('misc.placeholder_location') }}"autocomplete="off" >

				     @if( $errors->first("location") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("location")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div><!-- **** form-group **** -->


				  <div class="form-group @if( $errors->first('website') ) has-error @endif">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.website_misc') }}</label>
				    <div class="col-sm-10">
				      <input type="text" value="{{ e( $user->website ) }}" class="form-control input-sm" id="website" name="website" placeholder="{{ Lang::get('misc.website_misc') }}">

				    @if( $errors->first("website") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("website")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div><!-- **** form-group **** -->

				  <div class="form-group @if( $errors->first('twitter') ) has-error @endif">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Twitter</label>
				    <div class="col-sm-10">
				      <input type="text" value="{{ $user->twitter }}" class="form-control input-sm" id="inputTwitter" name="twitter" placeholder="{{ Lang::get('misc.username_on_Twitter') }}">

				    @if( $errors->first("twitter") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("twitter")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div><!-- **** form-group **** -->

				  <div class="form-group @if( $errors->first('skills') ) has-error @endif">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.skills') }}</label>
				    <div class="col-sm-10">
				      <input type="text" value="{{ $user->skills }}" name="skills" class="form-control input-sm" id="skills" placeholder="{{ Lang::get('misc.skills') }}">

				    @if( $errors->first("skills") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("skills")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div><!-- **** form-group **** -->

				  <div class="form-group @if( $errors->first('bio') ) has-error @endif">
				    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.bio_misc') }}</label>
				    <div class="col-sm-10">
				      <textarea name="bio" rows="4" id="bio" class="form-control input-sm textarea-textx">{{ e( $user->bio ) }}</textarea>

				    @if( $errors->first("bio") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("bio")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div><!-- **** form-group **** -->
			  */?>
			   <div class="form-group @if( $errors->first('bio') ) has-error @endif">
				    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.intro_video') }}</label>
				    <div class="col-sm-10">
				    	@if($user->intro_video !='')

			    	  	<video id="video1" width="420" controls  controlsList="nodownload" oncontextmenu="return false;" height="200">
						    <source src="{{ asset('protected/public/intro_video/'.$user->intro_video)  }}" type="video/mp4">
					  	</video>
				    	@endif
			      		<input name="intro_video" id="intro_video" class="form-control" type="file" style="display: block; opacity: 1">


				    @if( $errors->first("intro_video") )
				<div class="alert alert-danger btn-sm errors-account" role="alert">
		            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		            		<strong>{{$errors->first("intro_video")}}</strong>
		     	</div><!-- Error -->
		     	@endif

				    </div>
				  </div>

				  <h4 class="titleBar title-h4">
				  	{{ Lang::get('users.settings') }} / {{ Lang::get('misc.email_notifications') }}
				  	</h4>

				  <hr />

				  @if( Auth::user()->type_account == 2 || Auth::user()->type_account == 3 )
				  <!-- **** form-group **** -->
				  <div class="form-group">
				    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.available_for_hire') }}?</label>
				    <div class="col-sm-10">
						<label class="checkbox-inline">
						 <input id="check_0" <?php if( $user->hire == 1 ) : echo 'checked="checked"'; endif ?> class="no-show"type="checkbox" value="1" name="hire" />
						   <span class="input-sm">{{ Lang::get('misc.available_true') }} </span>
						</label>
				    </div>
				  </div><!-- **** form-group **** -->
				  @endif


				  <!-- **** form-group **** -->
				  <div class="form-group row">
				    <label for="inputEmail3" class="col-xl-2 control-label input-sm">{{ Lang::get('misc.email_notifications') }}</label>
				    <div class="col-xl-10">
				    	<label class="checkbox-inline">
				    	<?php /*echo "<pre>";
				    	print_r($user);*/ ?>
						 <input id="check_0" <?php if( $user->email_notification_msg == 1 ) : echo 'checked="checked"'; endif ?> class="no-show" type="checkbox" value="1" name="email_notification_msg" />
						   <span class="input-sm">{{ Lang::get('misc.messages_private') }}</span>
						</label>
						<label class="checkbox-inline">
						  <input type="checkbox" <?php if( $user->email_notification_follow == 1 ) : echo 'checked="checked"'; endif ?> class="no-show" id="check_1" value="1" name="email_notification_follow" />
						  <span class="input-sm">{{ Lang::get('misc.being_followed') }}</span>
						</label>

				    </div>
				  </div><!-- **** form-group **** -->

				  <hr />

				  <div class="form-group">
				    <div class="col-sm-offset-2 col-sm-10">
				      <button type="submit" class="btn btn-info btn-sm btn-sort" id="saveUpdate" disabled="disabled">{{ Lang::get('misc.save_changes') }}</button>
				 @if( Auth::user()->id != 1 )
				    <a href="javascript:void(0);" class="delete-account pull-right">
				    	<small>{{ Lang::get('users.delete_account') }}</small>
				    </a>
				    @endif
				    </div>
				  </div><!-- **** form-group **** -->

				</form><!-- **** form **** -->

			 </div> <!-- Panel Body -->

	    </div><!-- Panel Default -->


	 </div>
	@stop

	@section('sidebar')
	<div class="col-xl-4">

		@include('includes.user-card')

		@include('includes.sidebar_edit_user')

		@include('includes.ads')

	</div><!-- /End col md-4 -->
</div>
</div>
@stop

@section('javascript')

  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get('config.googleapikey') ?>&amp;libraries=places"></script>
  <script type="text/javascript" src="{{ asset('protected/public/js_theme/jquery.geocomplete.js') }}"></script>
  <script type="text/javascript" src="{{ asset('protected/public/js_theme/bootstrap-tokenfield.js') }}"></script>


<script type="text/javascript">

$('#skills').on('tokenfield:createdtoken', function (e) {
	    var re = /^[a-z0-9][\w\s]+$/i
	    var valid = re.test(e.attrs.value)
	    if (!valid) {
	      $(e.relatedTarget).addClass('invalid').remove()
	    }
	  }).tokenfield({
		limit: 10,
		beautify: false
	});

//** Changes Form **//
function changesForm () {
	var button = $('#saveUpdate');
	$('form.form-account input, select, textarea, checked').each(function () {
	    $(this).data('val', $(this).val());
	    $(this).data('checked', $(this).is(':checked'));
	});

	$('form.form-account input, select, textarea, checked').bind('keyup change blur', function(){

	    var changed  = false;
	    var ifChange = false;
	    button.css({'opacity':'0.7','cursor':'default'});

	    $('form.form-account input, select, textarea, checked').each(function () {
	        if( trim( $(this).val() ) != $(this).data('val') || $(this).is(':checked') != $(this).data('checked') ){
	            changed = true;
	            ifChange = true;
	            button.css({'opacity':'1','cursor':'pointer'})
	        }

	    });
	    button.prop('disabled', !changed);
	});
}//<<<--- Function

changesForm();


	$("#bio").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

	$(function(){
        $("#location").geocomplete()
        .bind("geocode:result", function(event, result){
          });
        });


  //<<<---------- Delete Account
  $(".delete-account").click(function() {

   	bootbox.confirm("{{ Lang::get('misc.delete_account_confirm') }}", function(r) {

   		if( r == true ) {

   			var location = "{{ URL::to('delete/account') }}";
   			window.location.href = location;

	 }//END IF R TRUE

	  }); //Jconfirm

   });



</script>
@stop

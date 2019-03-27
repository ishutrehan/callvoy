<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php
    use App\Models\AdminSettings;
    use Illuminate\Support\Facades\Input;
    $settings = AdminSettings::first(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
    <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />

    <title>{{ Lang::get('auth.login') }} - {{ $settings->title }}</title>

    @include('includes.css_general')
    
    <link href="{{ URL::asset('public/css/jquery.fs.picker.min.css') }}" rel="stylesheet">

  </head>

  <body id="join-section">
  	
  	<div class="popout font-default"></div>
     
     @include('includes.navbar')
    
<!-- Start CONTAINER -->
<div class="container wrap-ui">
	<!-- ROW -->
	<div class="row">
        
     	<!-- Col MD -->
<div class="col-md-12">	
	
	<div class="row">
		
		<div class="col-md-12">
			
			<h3 class="text-center join-title">{{ Lang::get('auth.login') }} - {{ $settings->title }}</h3>
		@if( $settings->registration_active == '1' )
			<h4 class="text-center join-title">{{ Lang::get('auth.not_have_account') }} <a href="join" class="btn btn-xs btn-success no-shadow font-default btn-join margin-zero">{{ Lang::get('auth.sign_up') }}</a></h4>
		@endif
			
		<div class="login-form">
			@if (Session::has('notification'))
			<div class="alert alert-danger btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif
            	
            	@if (Session::has('password_reset_success'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('password_reset_success') }}
            		</div>
            	@endif
            	
            	@if (Session::has('login_required'))
			<div class="alert alert-danger btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('login_required') }}
            		</div>
            	@endif
            	
          	<form action="{{ URL::to('login') }}" method="post" name="form" id="signup_form">
           
            
            <div class="form-group">
              <input type="text" class="form-control login-field" value="{{ Input::old('username_email') }}" name="username_email" id="username_email" placeholder="{{ Lang::get('auth.username_or_email') }}" title="{{ Lang::get('auth.username_or_email') }}" autocomplete="off">
              <label class="login-field-icon fui-user" for="username"></label>
             </div>

            
            <div class="form-group">
              <input type="password" class="form-control login-field" name="password" id="password" placeholder="{{ Lang::get('auth.password') }}" title="{{ Lang::get('auth.password') }}" autocomplete="off">
              <label class="login-field-icon fui-lock" for="password"></label>
            </div>
         
           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-success">{{ Lang::get('auth.sign_in_nav') }}</button>

			@if( $settings->twitter_login == 'on' && $settings->twiter_appid != '' && $settings->twitter_secret != '' )
			<span class="login-link" id="twitter-btn-text">{{ Lang::get('auth.or_sign_in_with') }}</span>

					<div class="facebook-login" id="twitter-btn">
						<a href="{{URL::to('/')}}/oauth/twitter" class="btn btn-block btn-lg btn-info"><i class="fa fa-twitter"></i> Twitter</a>
					</div>
					@endif
							
    	<label class="btn-block">
		   <a href="recover/password" class="label-terms recover">{{ Lang::get('auth.forgot_password') }}</a>
		</label>
		
		<label class="radio-inline">
				<input <?php if( Session::get('rememberInput') ) : echo 'checked="checked"'; endif ?> id="keep_login" class="no-show" name="keep_login" type="checkbox" value="1">
				<span class="input-sm keep-login-title">{{ Lang::get('auth.keep_me_logged_in') }}</span>
		</label>

          </form></div>
			
		</div><!-- /COL MD -->
		
		<div class="col-md-8">
								
		</div><!-- /COL MD -->
		
	</div><!-- /Row -->
    	
 </div><!-- /COL MD -->
    	
</div><!-- /Row -->

    </div> <!-- /container -->
    
    @include('includes.javascript_general')
    
    <script type="text/javascript">
    
    @if (Session::has('success_account'))
	 $('.popout').html("{{ Session::get('success_account')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
    $('#username_email').focus();
    
    $('#buttonSubmit').click(function(){
    	$(this).css('display','none');
    });
    
    $("input[type=radio], input[type=checkbox]").picker();
    
    // URL BASE
    var URL_BASE = "{{ URL::to('/') }}";
    
    // Tooltip
    $('.shotTooltip').tooltip();
    
    </script>
    
  </body>
</html>
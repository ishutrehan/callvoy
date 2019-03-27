<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php $settings = AdminSettings::first(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
    <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />

    <title>{{ Lang::get('auth.password_reset_2') }} - {{ $settings->title }}</title>

    @include('includes.css_general')
    
    <link href="{{ URL::asset('public/css/jquery.fs.picker.min.css') }}" rel="stylesheet">

  </head>

  <body id="join-section">
     
     @include('includes.navbar')
    
<!-- Start CONTAINER -->
<div class="container wrap-ui">
	<!-- ROW -->
	<div class="row">
        
     	<!-- Col MD -->
<div class="col-md-12">	
	
	<div class="row">
		
		<div class="col-md-12">
			
	<h3 class="text-center join-title">{{ Lang::get('auth.password_reset_2') }}</h3>
			
		<div class="login-form">
			
			@if (Session::has('error'))
			<div class="alert alert-danger btn-sm" role="alert">
            		{{ Session::get('error') }}
            		</div>
        @endif
            	
          	<form action="{{ URL::to('password_reset') . '/' . $token }}" method="post" name="form" id="signup_form">
           
            <div class="form-group">
              <input type="text" class="form-control login-field" value="{{ Input::old('email') }}" name="email" id="email" placeholder="{{ Lang::get('auth.email') }}" title="{{ Lang::get('auth.email') }}" autocomplete="off">
              <label class="login-field-icon fui-mail" for="email"></label>
             </div>
             
             <div class="form-group">
              <input type="password" class="form-control login-field" name="password" id="password" placeholder="{{ Lang::get('auth.password') }}" title="{{ Lang::get('auth.password') }}" autocomplete="off">
              <label class="login-field-icon fui-lock" for="password"></label>
            </div>
            
            <div class="form-group">
              <input type="password" class="form-control login-field" name="password_confirmation" id="password_confirmation" placeholder="{{ Lang::get('auth.password_confirmation') }}" title="{{ Lang::get('auth.password_confirmation') }}" autocomplete="off">
              <label class="login-field-icon fui-lock" for="password_confirmation"></label>
            </div>
             
             <input name="token" type="hidden" value="{{ $token }}">
             
           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-success">{{ Lang::get('auth.send') }}</button>

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
        
    $("input[type=radio], input[type=checkbox]").picker();
    
    // Tooltip
    $('.shotTooltip').tooltip();
    
    </script>
    
  </body>
</html>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php $settings = AdminSettings::first(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
    <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />

    <title>{{ Lang::get('auth.password_recover') }} - {{ $settings->title }}</title>

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
			
			<h3 class="text-center join-title">{{ Lang::get('auth.password_recover') }}</h3>
			<h4 class="text-center join-title">
				<a href="{{Url::to('login') }}" class="btn btn-xs btn-success no-shadow font-default btn-join margin-zero">
					<i class="fa fa-long-arrow-left"></i> {{ Lang::get('auth.back') }}
					</a>
				</h4>
		
			
		<div class="login-form">
			
			@if (Session::has('error'))
			<div class="alert alert-danger btn-sm" role="alert">
            		{{ Session::get('error') }}
            		</div>
        @elseif (Session::has('success'))
        <div class="alert alert-success btn-sm" role="alert">
            		{{ Lang::get('auth.email_has_been_set') }}
            		</div>
        @endif
            	
          	<form action="{{ URL::to('recover/password') }}" method="post" name="form" id="signup_form">
           
            <div class="form-group">
              <input type="text" class="form-control login-field" value="" name="email" id="email" placeholder="{{ Lang::get('auth.email') }}" title="{{ Lang::get('auth.email') }}" autocomplete="off">
              <label class="login-field-icon fui-mail" for="username"></label>
             </div>
             
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
    
    $('#buttonSubmit').click(function(){
    	$(this).css('display','none');
    });
    
    $('#email').focus();
    
    $("input[type=radio], input[type=checkbox]").picker();
        
    // Tooltip
    $('.shotTooltip').tooltip();
    
    </script>
    
  </body>
</html>
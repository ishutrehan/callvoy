<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php $settings = AdminSettings::first(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
    <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />

    <title>{{ Lang::get('auth.join') }} - {{ $settings->title }}</title>

    @include('includes.css_general')

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

			<h3 class="text-center join-title">{{ Lang::get('auth.join') }} - {{ $settings->title }}</h3>
			<h4 class="text-center join-title">{{ Lang::get('auth.already_have_an_account') }} <a href="login" class="btn btn-xs btn-success no-shadow font-default btn-join margin-zero">{{ Lang::get('auth.sign_in_nav') }}</a></h4>
		
			
		<div class="login-form">
			@if (Session::has('notification'))
			<div class="alert alert-success btn-sm margin-zero" role="alert">
            		{{ Session::get('notification') }}
            		</div>
            	@endif
          	<form action="{{ URL::to('join-team') }}" method="post" name="form" id="signup_form">
           
            <div class="form-group">
              <input type="text" class="form-control login-field" value="{{ Input::old('team_name') }}" name="team_name" id="team_name" placeholder="{{ Lang::get('auth.team_name') }}" title="{{ Lang::get('auth.team_name') }}" autocomplete="off">
              <label class="login-field-icon fui-user" for="team_name"></label>
              
              @if( $errors->first("team_name") )
              <div class="alert alert-danger btn-sm margin-top-alert" role="alert">
            		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<strong>{{$errors->first("team_name")}}</strong>
            		</div>
            	@endif
              
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control login-field" value="{{ Input::old('username') }}" name="username" id="username" placeholder="{{ Lang::get('auth.username') }}" title="{{ Lang::get('auth.username') }}" autocomplete="off">
              <label class="login-field-icon fui-user" for="username"></label>
              
              @if( $errors->first("username") )
              <div class="alert alert-danger btn-sm margin-top-alert" role="alert">
            		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<strong>{{$errors->first("username")}}</strong>
            		</div>
            	@endif
              
            </div>
            
            <div class="form-group">
              <input type="text" class="form-control login-field" value="{{ Input::old('email') }}" name="email" id="email" placeholder="{{ Lang::get('auth.email') }}" title="{{ Lang::get('auth.email') }}" autocomplete="off">
              <label class="login-field-icon fui-mail" for="email"></label>
              
              @if( $errors->first("email") )
              <div class="alert alert-danger btn-sm margin-top-alert" role="alert">
            		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<strong>{{$errors->first("email")}}</strong>
            		</div>
            	@endif
            	
            </div>
            
            <div class="form-group">
              <input type="password" class="form-control login-field" name="password" id="password" placeholder="{{ Lang::get('auth.password') }}" title="{{ Lang::get('auth.password') }}" autocomplete="off">
              <label class="login-field-icon fui-lock" for="password"></label>
            
            @if( $errors->first("password") )
              <div class="alert alert-danger btn-sm margin-top-alert" role="alert">
            		<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		<strong>{{$errors->first("password")}}</strong>
            		</div>
            	@endif
            </div>
        
        @if( $settings->captcha == 'on' )    
            <div class="form-group">
              <input type="text" class="form-control login-field" name="captcha" id="lcaptcha" placeholder="" title="">
              <label class="login-field-icon fui-lock" for="lcaptcha"></label>
            
              <div class="alert alert-danger btn-sm margin-top-alert" id="errorCaptcha" role="alert" style="display: none;">
            		<strong>{{Lang::get('auth.error_captcha')}}</strong>
            		</div>
            </div>
            @endif
                     
           <button type="submit" id="buttonSubmit" class="btn btn-block btn-lg btn-success col-thumb">
           	@if( isset( $settings->team_free ) && $settings->team_free == 'off'  )
           		{{ Lang::get('auth.pay_register') }}
           	@else
           		{{ Lang::get('auth.sign_up') }}
           	@endif
           	</button>
							
    	<label class="text-center">
		   <span class="label-terms">{{ Lang::get('auth.terms') }}</span>
		</label>

          </form></div>
			
		</div><!-- /COL MD -->
		
	</div><!-- /Row -->
    	
 </div><!-- /COL MD -->
    	
</div><!-- /Row -->

    </div> <!-- /container -->
    
    @include('includes.javascript_general')
    
    <script type="text/javascript">
 
 @if( $settings->captcha == 'on' )     
/*
 *  ==============================================  Captcha  ============================== * /
 */
   var captcha_a = Math.ceil( Math.random() * 5 );
   var captcha_b = Math.ceil( Math.random() * 5 );
   var captcha_c = Math.ceil( Math.random() * 5 );
   var captcha_e = ( captcha_a + captcha_b ) - captcha_c;
  
function generate_captcha( id ) {
	var id = ( id ) ? id : 'lcaptcha';
	$("#" + id ).html( captcha_a + " + " + captcha_b + " - " + captcha_c + " = ").attr({'placeholder' : captcha_a + " + " + captcha_b + " - " + captcha_c, title: 'Captcha = '+captcha_a + " + " + captcha_b + " - " + captcha_c });
}
$("input").attr('autocomplete','off');
generate_captcha('lcaptcha');

$('#buttonSubmit').click(function(e){
   	e.preventDefault();
   	var captcha        = $("#lcaptcha").val();
    	if( captcha != captcha_e ){
				var error = true;
		        $("#errorCaptcha").fadeIn(500);
		        $('#lcaptcha').focus();
		        return false;
		      } else {
		      	$(this).css('display','none');
		      	$('#signup_form').submit();
		      }
    });
    
    @else
    
    $('#buttonSubmit').click(function(e){

		$(this).css('display','none');
    });
    
    @endif
    
    
    $('#full_name').focus();
    
    @if (Session::has('notification'))
    	$('#signup_form').remove();
    @endif

    // Tooltip
    $('.shotTooltip').tooltip();
    
    </script>
    
  </body>
</html>
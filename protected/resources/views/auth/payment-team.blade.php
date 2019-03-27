<?php 
	$settings = AdminSettings::first();
	
	$password = Hash::make(Input::get('password'));
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>{{Lang::get('misc.please_wait')}}</title>	
	<meta http-equiv="content-type" content="text/html;charset=UTF-8" />   
	
	{{ HTML::script('public/js/jquery.min.js') }}
	<!-- FLAT UI CSS -->
    <link href="{{ URL::asset('public/css/flat-ui.css') }}" rel="stylesheet"> 
    <link rel="shortcut icon" href="{{URL::asset('public/img/')}}/favicon.ico" />

</head>


<script type="text/javascript">
$(document).on({
    "contextmenu": function(e) {
        console.log("ctx menu button:", e.which); 

        // Stop the context menu
        e.preventDefault();
    },
    "mousedown": function(e) { 
        console.log("normal mouse down:", e.which); 
    },
    "mouseup": function(e) { 
        console.log("normal mouse up:", e.which); 
    }
});

$(document).keydown(function(event){
    if(event.keyCode==123){
        return false;
    }
    else if (event.ctrlKey && event.shiftKey && event.keyCode==73){        
             return false;
    }
});

$('body').keydown(function (event) {

	    if( event.which  == 116 || event.which  == 27  ){
	     	return false;   
	    }
   });//======== FUNCTION 

setTimeout('document.formpaypal.submit()',500); // auto submit form
</script>
<body style="background: #FFF !important;">
	
	<div class="centerdiv" style="position: absolute; top: 50%; left: 50%; width: 340px; height: 200px; margin-top: -100px; margin-left: -160px;">
	<h1>{{Lang::get('misc.please_wait')}} <img src="{{URL::asset('public/img/preload.gif')}}" /></h1>
	</div>
 
 <?php
if ( $settings->paypal_sandbox == 1) {
	// SandBox
	$action = "https://www.sandbox.paypal.com/cgi-bin/webscr";
	} else {
	// Real environment
	$action = "https://www.paypal.com/cgi-bin/webscr";
	}
?>
   		
<form action="<?php echo $action; ?>" method="post" name="formpaypal">

	<input type="hidden" name="cmd" 			value="_xclick">										
	<input type="hidden" name="business" 		value="<?php echo $settings->mail_business; ?>">							
	<input type="hidden" name="return" 			value="{{URL::to('/')}}/payment/team/success">				
	<input type="hidden" name="notify_url" 		value="{{URL::to('/')}}/ajax/teampaypalipn">
	<input type="hidden" name="custom"          value="team_name={{Input::get('team_name')}}&username={{Input::get('username')}}&email={{Input::get('email')}}&password={{$password}}&renew=false">			
	<input type="hidden" name="item_name"  		value="<?php echo Lang::get('users.item_name_paypal_teams'); ?>"> 
	<input type="hidden" name="amount"  		value="<?php echo $settings->price_membership_teams; ?>"> 					
	<input type="hidden" name="currency_code"	value="<?php echo $settings->currency_code; ?>">	
	<input type="hidden" name="cancel_return"   value="{{URL::to('join')}}">			

</form>

</body>

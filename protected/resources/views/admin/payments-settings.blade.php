@extends('layouts.master')

@section('title'){{ Lang::get('admin.payments_settings') }} - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();

     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('admin.payments_settings') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('panel/admin/payments-settings') }}">
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif
            	
			 <div class="form-group @if( $errors->first('mail_business') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.mail_business') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e( $settings->mail_business ) }}" name="mail_business" class="form-control input-sm" id="mail_business" placeholder="{{ Lang::get('admin.mail_business') }}">
			
			@if( $errors->first("mail_business") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("mail_business")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
		
		<div class="form-group @if( $errors->first('email_notifications') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.email_notifications') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->email_notifications }}" name="email_notifications" class="form-control input-sm" id="email_notifications" placeholder="{{ Lang::get('admin.email_notifications') }}">
			    
			    @if( $errors->first("email_notifications") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("email_notifications")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.paypal_sandbox') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="paypal_sandbox" name="paypal_sandbox" class="input-sm btn-block">
					  	<option id="paypal_sandbox-1" value="1">On</option>
					  	<option id="paypal_sandbox-0" value="0">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			<div class="form-group @if( $errors->first('price_jobs') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.price_jobs') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->price_jobs }}" name="price_jobs" class="form-control input-sm" id="price_jobs" placeholder="{{ Lang::get('admin.price_jobs') }}">
			    
			    @if( $errors->first("price_jobs") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("price_jobs")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.duration_jobs') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="duration_jobs" name="duration_jobs" class="input-sm btn-block">
					  	<option id="duration_jobs-15days" value="15days">{{Lang::get('admin.15days')}}</option>
					  	<option id="duration_jobs-30days" value="30days">{{Lang::get('admin.30days')}}</option>
					  	<option id="duration_jobs-60days" value="60days">{{Lang::get('admin.60days')}}</option>
					  	<option id="duration_jobs-90days" value="90days">{{Lang::get('admin.90days')}}</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.currency_code') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="currency_code" name="currency_code" class="input-sm btn-block">
					  	<option id="currency_code-USD" data-symbol="$" value="USD">USD</option>
					  	<option id="currency_code-EUR" data-symbol="€" value="EUR">EUR</option>
					  	<option id="currency_code-GBP" data-symbol="£" value="GBP">GBP</option>
					  	<option id="currency_code-AUD" data-symbol="$" value="AUD">AUD</option>
					  	<option id="currency_code-JPY" data-symbol="¥" value="JPY">JPY</option>
					  	
					  	<option id="currency_code-BRL" data-symbol="R$" value="BRL">BRL</option>
					  	<option id="currency_code-MXN" data-symbol="$"  value="MXN">MXN</option>
					  	<option id="currency_code-SEK" data-symbol="Kr" value="SEK">SEK</option>
					  	<option id="currency_code-CHF" data-symbol="CHF" value="CHF">CHF</option>
					  	
					  	<option id="currency_code-SGD" data-symbol="$" value="SGD">SGD</option>
					  	<option id="currency_code-DKK" data-symbol="Kr" value="DKK">DKK</option>
					  	<option id="currency_code-RUB" data-symbol="руб" value="RUB">RUB</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  			  
			  <div class="form-group @if( $errors->first('cost_per_impression') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.cost_per_impression') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->cost_per_impression }}" name="cost_per_impression" class="form-control input-sm" id="cost_per_impression" placeholder="{{ Lang::get('admin.cost_per_impression') }}">
			    
			    @if( $errors->first("cost_per_impression") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("cost_per_impression")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('cost_per_click') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.cost_per_click') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->cost_per_click }}" name="cost_per_click" class="form-control input-sm" id="cost_per_click" placeholder="{{ Lang::get('admin.cost_per_click') }}">
			    
			    @if( $errors->first("cost_per_click") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("cost_per_click")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->

			  
			  
			  <div class="form-group @if( $errors->first('price_membership_teams') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.price_membership_teams') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->price_membership_teams }}" name="price_membership_teams" class="form-control input-sm" id="price_membership_teams" placeholder="{{ Lang::get('admin.price_membership_teams') }}">
			    
			    @if( $errors->first("price_membership_teams") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("price_membership_teams")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->

			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-info btn-sm btn-sort" id="saveUpdate" disabled="disabled">{{ Lang::get('misc.save_changes') }}</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->

			
 </div>
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('admin.sidebar_admin')
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
  
<script type="text/javascript">

$('#paypal_sandbox-{{$settings->paypal_sandbox}}').attr({'selected':'selected'});
$('#currency_code-{{$settings->currency_code}}').attr({'selected':'selected'});
$('#duration_jobs-{{$settings->duration_jobs}}').attr({'selected':'selected'});

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

</script>
@stop

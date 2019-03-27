@extends('layouts.master')

<?php 
	 $settings = AdminSettings::first();

     ?>
     
@section('title'){{ Lang::get('misc.jobs') }} - @stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home">
        		<i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i>
        		<?php $priceExplode = explode( '.', $settings->price_jobs) ?>
        	</a> {{ Lang::get('misc.post_a_Job') }}  {{$settings->currency_symbol.$priceExplode[0]}} @if( $priceExplode[1] != '00' ) <sup>{{$priceExplode[1]}}</sup>@endif
        	</h1>
       </div>
    </div>
@stop

@section('content') 

 
<!-- Col MD -->
<div class="col-md-8">	
<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  	
		  	<span style="margin-right: 15px;">
		  		<span class="badge">1</span> {{ Lang::get('misc.fill_details') }} 
		  	</span>	
		  	
		  		<i class="fa fa-angle-right" style="margin-right: 15px;"></i>
		  		
	  		<span style="color:#999;">
	  			<span class="badge" style="background-color:#ccc;">2</span> {{ Lang::get('misc.preview_and_pay') }}
	  			</span>
		  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('jobs') }}">
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif
            	
			 <div class="form-group @if( $errors->first('organization_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.organization_name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('organization_name') }}" name="organization_name" class="form-control input-sm" placeholder="{{ Lang::get('misc.organization_name') }}">
			
			@if( $errors->first("organization_name") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("organization_name")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('job_title') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.job_title') }} 
			    	</label>
			    <div class="col-sm-10">
			      <input type="text" name="job_title" value="{{ Input::old('job_title') }}" class="form-control input-sm" placeholder="{{ Lang::get('misc.job_title') }}">
			    
			    @if( $errors->first("job_title") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("job_title")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('url_to_job_description') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.url_to_job') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('url_to_job_description') }}" name="url_to_job_description" class="form-control input-sm" placeholder="{{ Lang::get('misc.placeholder_url_job') }}">
			    	
			    @if( $errors->first("url_to_job_description") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("url_to_job_description")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('location') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.location') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('location') }}" class="form-control input-sm" name="location" id="location" placeholder="{{ Lang::get('misc.placeholder_job_location') }}" >
			    
			     @if( $errors->first("location") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("location")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->

			  			  
			  <h4 class="titleBar title-h4">
			  	<strong>{{ Lang::get('misc.contact_info') }}</strong>
			  	</h4>
			  
			  <hr />
			  
			  <div class="form-group @if( $errors->first('contact_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.contact_name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{Auth::user()->name}}" class="form-control input-sm" name="contact_name" placeholder="{{ Lang::get('misc.contact_name') }}"autocomplete="off" >
			    
			     @if( $errors->first("contact_name") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("contact_name")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('contact_email') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.contact_email') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{Auth::user()->email}}" class="form-control input-sm" name="contact_email" id="location" placeholder="{{ Lang::get('misc.contact_email') }}"autocomplete="off" >
			    
			     @if( $errors->first("contact_email") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("contact_email")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-success btn-sm" id="saveUpdate">{{ Lang::get('misc.preview_and_pay') }}</button>
				
				<a href="javascript:history.back()" class="btn btn-inverse btn-sm">
			      	{{Lang::get('users.cancel')}}
			      </a>
			      
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->
 </div><!-- /COL MD -->
 
<div class="col-md-4">
	
	@include('includes.ads')
          
</div><!-- /End col md-4 -->

 
@stop

@section('javascript')
	
   
<script type="text/javascript">

@if (Session::has('error_job'))
	 $('.popout').html("{{ Session::get('error_job')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
   	//** Changes Form **//
function changesForm () {
var button = $('#searchBtn');
$('form#sortJobs input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form#sortJobs input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});
    
    $('form#sortJobs input, select, textarea, checked').each(function () {
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


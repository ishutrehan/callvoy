@extends('layouts.master')

<?php 
	 $settings = AdminSettings::first();

     ?>
     
@section('title'){{ Lang::get('users.edit') }} {{ Lang::get('misc.job') }} - @stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home">
        		<i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i>
        	</a> {{ Lang::get('users.edit') }} {{ Lang::get('misc.job') }}
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
		  		{{ Lang::get('users.edit') }} {{ Lang::get('misc.job') }} 
		  	</span>	

		  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="">
			 
			 <input type="hidden" name="token" value="{{$data->token}}" />
			 <input type="hidden" name="id" value="{{$data->id}}" />
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }} <a href="{{URL::to('my/jobs')}}">{{Lang::get('misc.my_jobs')}}</a>
            		</div>
            	@endif
            	
			 <div class="form-group @if( $errors->first('organization_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.organization_name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" disabled="disabled" value="{{ e($data->organization_name) }}" name="organization_name" class="form-control input-sm" placeholder="{{ Lang::get('misc.organization_name') }}">
			
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
			      <input type="text" disabled="disabled" name="job_title" value="{{ e($data->workstation) }}" class="form-control input-sm" placeholder="{{ Lang::get('misc.job_title') }}">
			    
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
			      <input type="text" value="{{ e($data->url_job) }}" name="url_to_job_description" class="form-control input-sm" placeholder="{{ Lang::get('misc.placeholder_url_job') }}">
			    	
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
			      <input type="text" value="{{ e($data->location) }}" class="form-control input-sm" name="location" id="location" placeholder="{{ Lang::get('misc.placeholder_job_location') }}" >
			    
			     @if( $errors->first("location") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("location")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" disabled="disabled" class="btn btn-success btn-sm" id="saveUpdate">{{ Lang::get('users.save') }}</button>
			      
			      <a href="{{URL::to('my/jobs')}}" class="btn btn-inverse btn-sm">
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


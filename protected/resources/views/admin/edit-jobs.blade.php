@extends('layouts.master')

@section('title'){{ Lang::get('admin.manage_jobs') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	  
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		<a href="{{URL::to('panel/admin/jobs')}}">{{ Lang::get('admin.manage_jobs') }}</a>
		  		
		  		<i class="fa fa-angle-right" style="margin: 0 5px;"></i>
		  		
		  		{{ Lang::get('users.edit') }}
		  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">

<form class="form-horizontal form-account" method="post" role="form" action="">
			 
			 <input type="hidden" name="token" value="{{$data->token}}" />
			 <input type="hidden" name="id" value="{{$data->id}}" />
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif
            	
			 <div class="form-group @if( $errors->first('organization_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.organization_name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e($data->organization_name) }}" name="organization_name" class="form-control input-sm" placeholder="{{ Lang::get('misc.organization_name') }}">
			
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
			      <input type="text" name="job_title" value="{{ e($data->workstation) }}" class="form-control input-sm" placeholder="{{ Lang::get('misc.job_title') }}">
			    
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
			      
			      <a href="{{URL::to('panel/admin/jobs')}}" class="btn btn-inverse btn-sm">
			      	{{Lang::get('users.cancel')}}
			      </a>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->

			
 </div><!-- /End col md-* -->
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('admin.sidebar_admin')
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
  
<script type="text/javascript">

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
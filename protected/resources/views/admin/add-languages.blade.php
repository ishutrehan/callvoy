@extends('layouts.master')

@section('title'){{ Lang::get('misc.languages') }}  - @stop

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
		  		<a href="{{URL::to('panel/admin/languages')}}">{{ Lang::get('misc.languages') }}</a>
		  		
		  		<i class="fa fa-angle-right" style="margin: 0 5px;"></i>
		  		
		  		{{ Lang::get('misc.add_new') }}
		  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">

<form class="form-horizontal form-account" method="post" role="form" action="">
			 
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }} <a href="{{ URL::to('panel/admin/languages') }}">{{ Lang::get('misc.languages') }}</a>
            		</div>
            	@endif
            	
			 <div class="form-group @if( $errors->first('organization_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.title') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('title') }}" name="title" class="form-control input-sm" placeholder="{{ Lang::get('misc.title') }}">
			
			@if( $errors->first("title") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("title")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			  
			   <div class="form-group @if( $errors->first('organization_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.abbreviation') }}</label>
			    <div class="col-sm-10">
			      <input type="text" maxlength="2" value="{{ Input::old('abbreviation') }}" name="abbreviation" class="form-control input-sm" placeholder="{{ Lang::get('misc.abbreviation') }} : en">
					<small class="help-block margin-bottom-zero">{{ Lang::get('misc.important_note') }}</small>
					
			@if( $errors->first("abbreviation") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("abbreviation")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->

			  			  			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" disabled="disabled" class="btn btn-success btn-sm" id="saveUpdate">{{ Lang::get('users.save') }}</button>
			      
			      <a href="{{URL::to('panel/admin/languages')}}" class="btn btn-inverse btn-sm">
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
@extends('layouts.master')

@section('title'){{ Lang::get('admin.profiles_social') }} - @stop

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
		  		{{ Lang::get('admin.profiles_social') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('panel/admin/profiles-social') }}">
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif

		
		<div class="form-group @if( $errors->first('twitter') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Twitter</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->twitter }}" name="twitter" class="form-control input-sm" id="twitter" placeholder="{{ Lang::get('admin.url_social') }}">
			    
			    @if( $errors->first("twitter") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("twitter")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->

			  			  
			  <div class="form-group @if( $errors->first('facebook') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Facebook</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->facebook }}" name="facebook" class="form-control input-sm" id="facebook" placeholder="{{ Lang::get('admin.url_social') }}">
			    
			    @if( $errors->first("facebook") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("facebook")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('instagram') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Instagram</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->instagram }}" name="instagram" class="form-control input-sm" id="instagram" placeholder="{{ Lang::get('admin.url_social') }}">
			    
			    @if( $errors->first("instagram") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("instagram")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('googleplus') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Google Plus</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->googleplus }}" name="googleplus" class="form-control input-sm" id="googleplus" placeholder="{{ Lang::get('admin.url_social') }}">
			    
			    @if( $errors->first("googleplus") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("googleplus")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('linkedin') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Linkedin</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->linkedin }}" name="linkedin" class="form-control input-sm" id="linkedin" placeholder="{{ Lang::get('admin.url_social') }}">
			    
			    @if( $errors->first("linkedin") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("linkedin")}}</strong>
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

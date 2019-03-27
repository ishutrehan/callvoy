@extends('layouts.app')

@section('title'){{ Lang::get('auth.password') }} - @stop

@section('css_style')
<link href="{{ URL::asset('public/css/jquery.fs.picker.min.css') }}" rel="stylesheet">
@stop

@section('content') 
     
     <?php 
	  use App\Models\AdminSettings;
	  use Illuminate\Support\Facades\Input;
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();

     ?>
<div class="container">
<div class="row">
<div class="col-xl-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('auth.password') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('account/password') }}">
			 @csrf
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }} <i class="fa fa-long-arrow-right"></i> <a href="{{ Url::to('@') }}{{ $user->username }}">{{ Lang::get('users.go_to_profile') }}</a>
            		</div>
            	@endif
            	
            	 @if (Session::has('incorrect_pass'))
			<div class="alert alert-danger btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('incorrect_pass') }}
            		</div>
            	@endif
            
            <!-- **** form-group **** -->
			 <div class="form-group @if( $errors->first('old_password') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.old_password') }}</label>
			    <div class="col-sm-10">
			      <input type="password" value="{{ Input::old('old_password') }}" name="old_password" class="form-control input-sm" id="old_password" placeholder="{{ Lang::get('misc.old_password') }}">
			
			@if( $errors->first("old_password") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("old_password")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    </div><!-- col-sm-10 -->
			  </div><!-- **** form-group **** -->
			  
			  <!-- **** form-group **** -->
			 <div class="form-group @if( $errors->first('password') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.new_password') }}</label>
			    <div class="col-sm-10">
			      <input type="password" value="{{ Input::old('password') }}" name="password" class="form-control input-sm" id="password" placeholder="{{ Lang::get('misc.new_password') }}">
			
			@if( $errors->first("password") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("password")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    </div><!-- col-sm-10 -->
			  </div><!-- **** form-group **** -->
			  
			  <!-- **** form-group **** -->
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
<div class="col-xl-4">
	
	@include('includes.user-card')
	
	@include('includes.sidebar_edit_user')

	@include('includes.ads')
          
          
          
</div><!-- /End col md-4 -->

@stop
</div>
</div>

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

@extends('layouts.master')

@section('title') Google Adsense - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();

     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		 Google Adsense
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('panel/admin/google-adsense') }}">
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif
            	
            	
			<div class="form-group @if( $errors->first('code') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">Google Adsense</label>
			    <div class="col-sm-10">
			      <textarea name="code" rows="10" id="code" class="form-control input-sm textarea-textx">{{ e( $settings->google_adsense ) }}</textarea>
			    
			    @if( $errors->first("code") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("code")}}</strong>
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

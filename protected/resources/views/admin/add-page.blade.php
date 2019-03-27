@extends('layouts.master')

@section('title'){{ Lang::get('admin.add_page') }} - @stop

@section('css_style')

@stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();

     ?>
     
<div class="col-md-12">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	
		  	<div class="btn-block">
		  		<a href="{{URL::to('panel/admin/pages')}}">{{ Lang::get('admin.pages') }}</a>
		  		
		  		<i class="fa fa-angle-right" style="margin: 0 5px;"></i>
		  		
		  		{{ Lang::get('admin.add_page') }}
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('panel/admin/pages/new') }}">
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif

            
            <!-- **** form-group **** -->
			 <div class="form-group @if( $errors->first('title') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.title') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('title') }}" name="title" class="form-control input-sm" id="title" placeholder="{{ Lang::get('misc.title') }}">
			
			@if( $errors->first("title") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("title")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    </div><!-- col-sm-10 -->
			  </div><!-- **** form-group **** -->
			  
			  <!-- **** form-group **** -->
			 <div class="form-group @if( $errors->first('slug') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.slug') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('slug') }}" name="slug" class="form-control input-sm" id="url" placeholder="{{ Lang::get('admin.place_holder_url') }}">
			
			@if( $errors->first("slug") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("slug")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    </div><!-- col-sm-10 -->
			  </div><!-- **** form-group **** -->
			  
			  <!-- **** form-group **** -->
			 <div class="form-group @if( $errors->first('content') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.content') }}</label>
			    <div class="col-sm-10">
			    	<textarea name="content" id="content" rows="5" class="form-control input-sm textarea-textx">{{ Input::old('content') }}</textarea>
			
			@if( $errors->first("content") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("content")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    </div><!-- col-sm-10 -->
			  </div><!-- **** form-group **** -->
			  
			  <!-- **** form-group **** -->
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-info btn-sm btn-sort" id="saveUpdate">{{ Lang::get('auth.send') }}</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->

			
 </div>
@stop

@section('javascript')
{{ HTML::script('public/js/tinymce/tinymce.min.js') }}
<script type="text/javascript">
	tinymce.init({
    selector: "textarea",
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks code fullscreen",
        "insertdatetime media table contextmenu paste autoresize"
    ],
 });
</script>
@stop
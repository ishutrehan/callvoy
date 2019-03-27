@extends('layouts.master')

<?php 
	
	// ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$trueProfile    = true; 
	$projects_users = true;

?>

@section('title'){{ Lang::get('users.projects') }} - {{ e( $user->name ) }} - @stop

 @include('includes.cover-static')

@section('content') 

<!-- Col MD -->
<div class="col-md-12">	

@if( Auth::check() && Auth::user()->id == $user->id && Auth::user()->type_account != 1 )
<!-- ***** Modal ****** -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title text-left" id="myModalLabel"><strong>{{ Lang::get('misc.new_project') }}</strong></h4>
	      </div>
	      
	      <div class="modal-body">
	      	
	     <form class="form-horizontal" id="form-edit-shot" method="post" role="form" action="{{ URL::to('/') }}/add/project">
			 
			 <div class="form-group @if( $errors->first('title') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.title') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="" name="title" class="form-control input-sm" id="title" placeholder="{{ Lang::get('misc.title') }}">
	     			
	     		@if( $errors->first("title") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("title")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('description') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.description') }} ({{ Lang::get('misc.optional') }})</label>
			    <div class="col-sm-10">
			      <textarea name="description" rows="4" id="description" class="form-control input-sm textarea-textx"></textarea>
	             
	             @if( $errors->first("description") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("description")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			  			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button style="padding: 9px 30px;" type="submit" class="btn btn-info btn-sm btn-sort" id="addProject">
			      	{{ Lang::get('users.create') }}
			      	</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
			    
	      </div><!-- modal-body -->
	    </div>
	  </div>
	</div> <!-- ***** Modal ****** -->
	@endif
			
@if( $totalGlobal != 0 )
	
	<h1 class="title-item none-overflow">
	{{ Lang::get('users.projects') }} <small>({{ Helper::formatNumber( $totalGlobal ) }})</small>

	@if( Auth::check() && Auth::user()->id == $user->id && Auth::user()->type_account != 1 )
		<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success pull-right">
			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('misc.new_project') }}
			</button>
	@endif
	</h1> 
<hr />


	@include('includes.projects-view')


@if( $data->getTotal() != $data->count() )
     
    	   <hr />
    	   
  <div class="btn-group paginator-style">
		   <?php echo $data->links(); ?> 
		</div>
		
@endif

				  	
	@else
	
	@if( Auth::check() && $user->id != Auth::user()->id || !Auth::check() )
	
	<div class="btn-block text-center">
	    	<i class="icon-briefcase ico-no-result"></i>
	    </div>
	    
	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- <strong>{{ $user->name }}</strong> {{ Lang::get('users.no_project') }} -
	    	</h3>
	    	
	    @elseif (Auth::check() && $user->id == Auth::user()->id)	
	 
		<div class="btn-block text-center">
	    	<i class="icon-briefcase ico-no-result"></i>
	    </div>
	    
	    <h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.session_no_project') }} -
	    	</h3>	
	    	
	    	@endif
	    	
	    	@if ( Auth::check() && Auth::user()->type_account != 1 && Auth::user()->id == $user->id )  
	   <div class="btn-block text-center" style="margin-bottom: 20px;">
	   	<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success">
			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('misc.new_project') }}
			</button>
	   </div> 
		@endif
	
	
	@endif
	
</div><!-- /COL MD -->

@stop

@section('javascript')
{{ HTML::script('public/js/count.js') }}
<script type="text/javascript">

@if (Session::has('success_add'))
	 $('.popout').html("{{ Session::get('success_add')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
   @if( Session::has('error_add') )
   	$('#myModal').modal('show');
   @endif

$("#addProject").on('click',function(){
    	$(this).css({'display': 'none'})
    });
    
	$("#description").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

</script>
@stop

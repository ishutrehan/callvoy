@extends('layouts.app')

<?php 
	
	// ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$trueProfile = true; 

?>

@section('title'){{ Lang::get('users.lists') }} - {{ e( $user->name ) }} - @stop

 @include('includes.cover-static')

@section('content') 
<div class="container">
<div class="row">
<!-- Col MD -->
<div class="col-xl-8">	

@if( Auth::check() && Auth::user()->id == $user->id )
<!-- ***** Modal ****** -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title text-left" id="myModalLabel"><strong>{{ Lang::get('users.create_list') }}</strong></h4>
	      </div>
	      
	      <div class="modal-body">
	      	
	     <form class="form-horizontal" id="form-edit-shot" method="post" role="form" action="{{ URL::to('/') }}/lists/add">
			 
			 <div class="form-group @if( $errors->first('name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('users.name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="" name="name" class="form-control input-sm" id="title" placeholder="{{ Lang::get('users.name') }}">
	     			
	     		@if( $errors->first("name") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("name")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			
			 <div class="form-group @if( $errors->first('project') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.type') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="project" name="type" class="input-sm btn-block">
					  <option value="1">{{ Lang::get('misc.public') }}</option>
					  	<option value="0">{{ Lang::get('misc.private') }}</option>
					</select>

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
			      <button style="padding: 9px 30px;" type="submit" class="btn btn-info btn-sm btn-sort" id="addLists">
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
	{{ Lang::get('users.lists') }} <small>({{ Helper::formatNumber( $totalGlobal ) }})</small>

	@if( Auth::check() && Auth::user()->id == $user->id )
		<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success pull-right">
			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.create_list') }}
			</button>
	@endif
	</h1> 
<hr />
	
	
	@foreach( $data as $lists )
	
	<?php
	
	if( Str::slug( $lists->name ) == '' ) {
	
		$slugUrl  = '';
	} else {
		$slugUrl  = '-'.Str::slug( $lists->name );
	}
	
	$slugUrlLists = URL::to('@').$lists->user()->username.'/lists/'.$lists->id.$slugUrl;
	 ?>
	 
<div class="media media-designer">
    		<span class="pull-left">
    			<a class="image-thumb" title="{{ e( $lists->user()->name ) }}" href="{{URL::to('@')}}{{$lists->user()->username}}">
    			<img width="45" height="45" class="media-object img-circle" src="{{URL::asset('public/avatar')}}/{{$lists->user()->avatar}}">
    			</a>
    		</span>
    		<div class="media-body">
    			<div class="pull-left">
    				<h4 class="media-heading">
    				<a class="link-user-profile" title="{{ e( $lists->name ) }}" href="{{$slugUrlLists}}">
    					{{ e( $lists->name ) }}
    					</a> 
    					
    					@if( $lists->type == 0 )
    					<small>
    						<i class="icon-lock" title="{{Lang::get('misc.private')}}"></i>
    					</small>
    					@endif
    			</h4>
    			 <!-- List group -->
    	<ul class="list-group list-designer">
    		<li>{{ Lang::get('misc.by') }} 
    			<a class="links-ds" href="{{URL::to('@')}}{{$lists->user()->username}}">{{ e( $lists->user()->name ) }}</a>
    			
    			// <a class="links-ds" href="{{$slugUrlLists}}/members">
    			 			<strong>{{ $lists->users()->count() }}</strong> {{Lang::choice('users.members',$lists->users()->count())}}
    			 			</a>
    			</li>
    			 </ul>
    			</div><!-- /End Pull Left -->
    			
     @if( $lists->users()->count() != 0 ) 	
	  <ul class="pull-right cover-img-desing list-inline" style="margin: 10px;">
	   <!-- Start List -->
	   @foreach( $lists->users()->take(5)->get() as $userlist )
	   <li>		    			
	    <a class="image-thumb"  href="{{URL::to('@')}}{{$userlist->user()->username}}">
	    			<img width="30" class="media-object img-circle showTooltip" data-toggle="tooltip" data-placement="left" title="{{ $userlist->user()->name }}" src="{{URL::asset('public/avatar')}}/{{$userlist->user()->avatar}}">
	    			</a>
	   </li><!-- End List -->
	   @endforeach
	    	</ul>
	    	@endif
	    	
  </div><!-- /End Media Body -->
</div><!-- Media Designer -->
@endforeach


@if( $data->getTotal() != $data->count() )
         	   
  <div class="btn-group paginator-style">
		   <?php echo $data->links(); ?> 
		</div>
		
@endif

				  	
	@else
	
	@if( Auth::check() && $user->id != Auth::user()->id || !Auth::check() )
	
	<div class="btn-block text-center">
	    	<i class="icon-list ico-no-result"></i>
	    </div>
	    
	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- <strong>{{ $user->name }}</strong> {{ Lang::get('users.no_list') }} -
	    	</h3>
	    	
	    @elseif (Auth::check() && $user->id == Auth::user()->id)	
	 
		<div class="btn-block text-center">
	    	<i class="icon-list ico-no-result"></i>
	    </div>
	    
	    <h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.session_no_list') }} -
	    	</h3>	
	    	
 
	   <div class="btn-block text-center" style="margin-bottom: 20px;">
	   	<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success">
			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.create_list') }}
			</button>
	   </div> 
		@endif
	
	
	@endif
</div><!-- /COL MD -->
@stop

@section('sidebar')
	<div class="col-xl-4">
		@include('includes.ads')
	</div>
@stop
</div>
</div>
@section('javascript')
<script type="text/javascript">

@if (Session::has('success_add'))
	 $('.popout').html("{{ Session::get('success_add')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
   @if( Session::has('error_add') )
   	$('#myModal').modal('show');
   @endif

$("#addLists").on('click',function(){
    	$(this).css({'display': 'none'})
    });
    
	$("#description").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

</script>
@stop

@extends('layouts.master')

<?php 
	
	// ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$trueProfile = true; 

?>

@section('title'){{ Lang::get('users.lists_memberships') }} - {{ e( $user->name ) }} - @stop

 @include('includes.cover-static')

@section('content') 

<!-- Col MD -->
<div class="col-md-8">	
			
@if( $totalGlobal != 0 )
	
	<h1 class="title-item none-overflow">
	{{ Lang::get('users.lists_memberships') }} <small>({{ Helper::formatNumber( $totalGlobal ) }})</small>
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
    					{{ e( $lists->name ) }}</a> 
    			</h4>
    			 <!-- List group -->
    	<ul class="list-group list-designer">
    		<li>{{ Lang::get('misc.by') }} 
    			<a class="links-ds" href="{{URL::to('@')}}{{$lists->user()->username}}">{{ e( $lists->user()->name ) }}</a>
    			
    			// <a class="links-ds" href="{{$slugUrlLists}}/members">
    			 			<strong>{{ $lists->members()->count()}}</strong> {{Lang::choice('users.members',$lists->members()->count())}}
    			 			</a>
    			</li>
    			 </ul>
    			</div><!-- /End Pull Left -->
    			
	  @if( $lists->members()->count() != 0 ) 	
	  <ul class="pull-right cover-img-desing list-inline" style="margin: 10px;">
	   <!-- Start List -->
	   @foreach( $lists->members()->take(5)->get() as $userlist )
	   <li>		    			
	    <a class="image-thumb" href="{{URL::to('@')}}{{$userlist->user()->username}}">
	    			<img width="30" class="media-object img-circle showTooltip" data-toggle="tooltip" data-placement="left" title="{{$userlist->user()->name}}"  src="{{URL::asset('public/avatar')}}/{{$userlist->user()->avatar}}">
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
	    	- <strong>{{ $user->name }}</strong> {{ Lang::get('users.no_list_memberships') }} -
	    	</h3>
	    	
	    @elseif (Auth::check() && $user->id == Auth::user()->id)	
	 
		<div class="btn-block text-center">
	    	<i class="icon-list ico-no-result"></i>
	    </div>
	    
	    <h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.session_no_list_member') }} -
	    	</h3>	

		@endif
	
	
	@endif
</div><!-- /COL MD -->
@stop

@section('sidebar')
	<div class="col-md-4">
		@include('includes.ads')
	</div>
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

$("#updateShot").on('click',function(){
    	$(this).css({'display': 'none'})
    });
    
	$("#description").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

</script>
@stop

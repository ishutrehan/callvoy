@extends('layouts.master')

<?php 
	
	// ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$trueProfile = true; 

?>

@section('title'){{ Lang::get('users.lists_members') }} - {{ e( $user->name ) }} - @stop

 @include('includes.cover-static')

@section('content') 

<!-- Col MD -->
<div class="col-md-8">	

			
@if( $totalGlobal != 0 )
	
	<?php
	
	if( Str::slug( $lists->name ) == '' ) {
	
		$slugUrl  = '';
	} else {
		$slugUrl  = '-'.Str::slug( $lists->name );
	}
	
	$slugUrlLists = URL::to('@').$user->username.'/lists/'.$lists->id.$slugUrl;
	 ?>
	 
	<h1 class="title-item none-overflow">
		<a href="{{$slugUrlLists}}">
			{{ $lists->name }}
		</a> /
	{{ Lang::get('users.lists_members') }} <small>({{ Helper::formatNumber( $totalGlobal ) }})</small>

	@if( Auth::check() && Auth::user()->id == $user->id )
		<a href="{{URL::to('designers')}}"  class="btn btn-sm btn-success pull-right">
			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('misc.find_people') }}
			</a>
	@endif
	</h1> 
<hr />
	
	
@foreach( $data as $designer )
	
	@include('includes.users-list')
@endforeach


@if( $data->getTotal() != $data->count() )
         	   
  <div class="btn-group paginator-style">
		   <?php echo $data->links(); ?> 
		</div>
		
@endif

				  	
	@else
	
	@if( Auth::check() && $user->id != Auth::user()->id || !Auth::check() )
	
	<div class="btn-block text-center">
	    	<i class="icon-user ico-no-result"></i>
	    </div>
	    
	 	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.list_no_members') }} -
	    	</h3>
	    	
	    @elseif (Auth::check() && $user->id == Auth::user()->id)	
	 
		<div class="btn-block text-center">
	    	<i class="icon-user ico-no-result"></i>
	    </div>
	    
	 	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.list_no_members') }} -
	    	</h3>	
	    	
 
	   <div class="btn-block text-center" style="margin-bottom: 20px;">
	   	<a href="{{URL::to('designers')}}"  class="btn btn-sm btn-success">
			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('misc.find_people') }}
			</a>
	   </div> 
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

@extends('layouts.master')

@section('title'){{ Lang::get('misc.tags') }} -@stop

@section('content') 
     
     <?php 
     
	 $page = Input::get('page');
	 
     // ** Admin Settings ** //
     $settings = AdminSettings::first();

	 $sql   = Shots::select(DB::raw('GROUP_CONCAT(tags SEPARATOR ",") as tags'))->where('status',1)->get();
	 
     ?>
     	<!-- Col MD -->
<div class="col-md-8">	
	
<h1 class="title-item none-overflow">

	{{ Lang::get('misc.tags') }}
	</h1>
<hr />
     <?php
	    
	    $_tags = strtolower( $sql{0}->tags );
	
	    $tags = array_unique( explode(',', $_tags) );
		
		sort($tags);	    
		
		?>
		
		@foreach( $tags as $query )	
		
		<?php $countTags = Shots::where( 'tags','LIKE', '%'.$query.'%' )->count(); ?>
	
			@if( $countTags != 0 )
			<a href="{{URL::to('tags').'/'.$query }}" class="btn btn-danger tags font-default btn-sm">
					{{ucfirst($query)}} ({{$countTags}})
				</a>
				@endif	
				
    @endforeach

        		
    		@if( $sql{0}->count() == 0  )
    	<div class="btn-block text-center">
	    	<i class="icon-tag ico-no-result"></i>
	    </div>
    		<h3 class="margin-top-none text-center no-result">
	    	- {{ Lang::get('misc.no_results_found') }} -
	    	</h3>
	    	@endif

    		
 </div><!-- /COL MD -->
@stop

@section('sidebar')
<div class="col-md-4">

@if( Auth::check() && Auth::user()->type_account == 2 || Auth::check() && Auth::user()->type_account == 3 )
<a class="btn btn-danger btn-lg btn-block shadow-inset col-thumb" href="{{ URL::to('upload') }}">
		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i> {{ Lang::get('misc.upload') }}
		</a>
		@endif

		@include('includes.ads')
          
          
          
</div><!-- /End col md-4 -->

@stop

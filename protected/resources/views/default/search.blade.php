@extends('layouts.master')

<?php $settings = AdminSettings::first(); ?>

@section('title'){{ e($title) }}@stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home">
        		<i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right myicon-right"></i>
        	</a> {{ Lang::get('misc.search') }} <small>/ {{ e(Str::title( Input::get('q') )) }}</small>
        	</h1>
       </div>
    </div>
@stop

@section('content') 

<!-- Col MD -->
<div class="col-md-12">	
		          
<h1 class="title-item none-overflow">
	{{ e(Str::title( Input::get('q') )) }} <small>{{ $total }} {{ Lang::choice('misc.shots_plural',$total) }}</small>
	
	<small>
		<a href="{{URL::to('search/users?q=').Input::get('q')}}" class="pull-right text-decoration-none">
		<i class="icon-users myicon-right"></i> {{ Lang::get('users.find_users') }} <i class="fa fa-long-arrow-right"></i>
	</a>
	</small>
	</h1>
<hr />

	
	@if( $total != 0 )
	
	@include('includes.shots')
	
	@else
	<div class="btn-block text-center">
	    	<i class="icon-close ico-no-result"></i>
	    </div>
	<h3 class="margin-top-none text-center no-result no-result-mg">
	    	- {{ Lang::get('misc.no_results_found') }} -
	    	</h3>
	@endif

</div><!-- /COL MD -->
@stop

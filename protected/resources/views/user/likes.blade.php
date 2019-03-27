@extends('layouts.master')

<?php $trueProfile = true; ?>

@section('title'){{ $title }} @stop

 @include('includes.cover-static')

@section('content') 

<!-- Col MD -->
<div class="col-md-12">	
			
	@if( $total != 0 )
	
	<h1 class="title-item none-overflow">
	{{ Lang::get('users.likes') }} <small>({{ Helper::formatNumber( User::totalLikes( $user->id ) ) }})</small>
	</h1> 
<hr />

	@include('includes.shots')
	
	@else
	
	@if( Auth::check() && $user->id != Auth::user()->id || !Auth::check() )
	<div class="btn-block text-center">
	    	<i class="icon-heart ico-no-result"></i>
	    </div>
	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- <strong>{{ $user->name }}</strong> {{ Lang::get('users.no_like') }} -
	    	</h3>
	    	
	    @elseif (Auth::check() && $user->id == Auth::user()->id)	
	    <div class="btn-block text-center">
	    	<i class="icon-heart ico-no-result"></i>
	    </div>
	    <h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.session_no_like') }} -
	    	</h3>	
	    	
	    	@endif
	
	
	@endif
	
</div><!-- /COL MD -->

@stop
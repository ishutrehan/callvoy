@extends('layouts.master')

<?php 
	$settings = AdminSettings::first();
	$trueProfile = true; 
			
?>


@section('content') 


<!-- Col MD -->
<div class="col-md-12">	
			
 <div class="btn-block text-center">
	    	<i class="icon-blocked ico-no-result"></i>
	    </div>	
	    <h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.user_suspended') }} -
	    	</h3>
	    	<div class="text-center btn-block user-no-result">
	    		<a class="btn btn-danger" href="{{ URL::to('/') }}"><i class="icon-home myicon-right"></i> {{ Lang::get('error.go_home') }}</a>
	    	</div>
	
</div><!-- /COL MD -->

@stop

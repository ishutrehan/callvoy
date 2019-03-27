@extends('layouts.master')

@section('title'){{ $title }}@stop

<?php 
	 $settings = AdminSettings::first();
	 $userAuth = Auth::user(); 
 ?>

@section('content') 
     	
<!-- Col MD -->
<div class="col-md-12">	
		
@if( $data->count() != 0 )
		
	@include('includes.nav-pills')

@endif
	
	@include('includes.shots')
	
	@if( $data->count() == 0 )
	
	<div class="btn-block text-center">
	    	<i class="icon-quill ico-no-result"></i>
	    </div>
	    
	<div class="no-following-yet">
			<h2 class="margin-top-none text-center">- {{ Lang::get('misc.no_shots_published') }} -</h2>
		</div>
	    	
	@endif

</div><!-- /COL MD -->
@stop



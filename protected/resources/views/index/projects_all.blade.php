@extends('layouts.master')

@section('title') {{ Lang::get('users.projects') }} - @stop

@section('content') 
     	
<!-- Col MD -->
<div class="col-md-12">	
	
	<h2 class="btn-block">
		{{ Lang::get('users.projects') }} <small>({{$data->count()}})</small>
	</h2>
	
	<hr />
	
	@include('includes.projects-view')
	
	@if( $data->count() == 0 )
	
	<div class="btn-block text-center">
	    	<i class="icon-briefcase ico-no-result"></i>
	    </div>
	    
	<div class="no-following-yet">
			<h2 class="margin-top-none text-center">- {{ Lang::get('misc.no_project_published') }} -</h2>
		</div>
	    	
	@endif
	
</div><!-- /COL MD -->

@stop



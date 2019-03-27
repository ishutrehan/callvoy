@extends('layouts.master')

@section('title'){{ Lang::get('misc.search_users') }} -@stop

@section('content') 
     
     <?php 
     
     $page = Input::get('page');
	 $query = Input::get('q');
	 
     // ** Admin Settings ** //
     $settings = AdminSettings::first();

		 $sql   = DB::table('members')
		 ->select(DB::raw('
		count(followers.id) totalFollowers,
		members.id,
		members.username,
		members.name,
		members.location,
		members.hire,
		members.avatar,
		members.status,
		members.type_account
		'))
		->leftjoin('followers', 'members.id', '=', DB::raw('followers.following AND followers.status = "1"'))
		->where('members.status','active')
		->where( 'members.name','LIKE', '%'.$query.'%' )
		->orWhere( 'members.username','LIKE', '%'.$query.'%' )
		->where('members.status','active')
		->groupBy('members.id')
		->orderBy('members.id', 'DESC')
		->paginate( $settings->result_request ); //$settings->result_request

		 
	 //<<---- * Total Search * ------->
	 $totalSearch = $sql->getTotal();

     ?>
     	<!-- Col MD -->
<div class="col-md-8">	
	

<h1 class="title-item none-overflow">

	{{ Lang::get('misc.search_users') }} / {{e($query)}}
	</h1>
<hr />
     
     @foreach( $sql as $designer )	
     
     
     	@include('includes.users-list')
    		
    		@endforeach
    		
    		@if( $sql->count() == 0 )
    	<div class="btn-block text-center">
	    	<i class="icon-users ico-no-result"></i>
	    </div>
    		<h3 class="margin-top-none text-center no-result">
	    	- {{ Lang::get('users.no_members_found') }} -
	    	</h3>
	    	@endif
	    	
	    	
    		@if( $sql->getLastPage() > 1 && $page <= $sql->getLastPage() )

	    		<div class="btn-group paginator-style">
	        		<?php echo $sql->links(); ?> 
	        	</div>
    		
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

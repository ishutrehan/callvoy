@extends('layouts.app')

@section('title'){{ $title }} @stop

@section('content') 
     
     <?php 
     
     $trueProfile = true; 
     $page = Input::get('page');
	 
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Sql Query ** //
		 
		 $sql   = DB::table('members')
		 ->select(DB::raw('
		count(DISTINCT followers.id) totalFollowers,
		count(DISTINCT shots.id) totalShots,
		members.id,
		members.username,
		members.name,
		members.location,
		members.hire,
		members.avatar,
		members.status,
		members.type_account,
		shots.image,
		shots.title,
		shots.id id_shot
		'))
		->leftjoin('followers', 'members.id', '=', DB::raw('followers.following AND followers.status = "1"'))
		->leftjoin('followers as F', 'members.id', '=', DB::raw('F.follower AND F.status = "1"'))
		->leftjoin('shots', 'members.id', '=', DB::raw('shots.user_id AND shots.status = "1"'))
		->where('F.following', '=', $user->id )
		->where('members.status', '=', 'active')
		->groupBy('members.id')
		->orderBy('F.id', 'DESC')
		->paginate( $settings->result_request );
		 

     ?>
     
     @include('includes.cover-static')
<div class="container">
<div class="row">
     	<!-- Col MD -->
<div class="col-xl-8">	

	
<h1 class="title-item none-overflow">
	{{ Lang::get('users.followers') }} <small>({{ Helper::formatNumber( User::totalFollowers( $user->id ) ) }})</small>

	</h1> 

<hr />
     
     @foreach( $sql as $designer )	
     
     @include('includes.users-list')
     
    		@endforeach
    		
    		@if( $sql->count() == 0 )
    		<div class="btn-block text-center">
	    	<i class="icon-users ico-no-result"></i>
	    </div>
    		<h3 class="margin-top-none text-center no-result user-no-result">
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
	<div class="col-xl-4">
		@include('includes.ads')
	</div>
@stop
</div>
</div>


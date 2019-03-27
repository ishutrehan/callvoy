@extends('layouts.master')

@section('title'){{ $title }} @stop

@section('content') 
     
     <?php 
     
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
		->leftjoin('likes as L', 'members.id', '=', DB::raw('L.user_id AND L.status = "1"'))
		->where('L.shots_id', '=', $id )
		->where('members.status', '=', 'active')
		->groupBy('members.id')
		->orderBy('L.id', 'DESC')
		->paginate( $settings->result_request );
		 

     ?>
          
     	<!-- Col MD -->
<div class="col-md-8">	

	
<h1 class="title-item none-overflow">
	
	<a href="{{URL::to('shots')}}/{{$id}}">
		<img src="{{ URL::asset('public/shots_img/') }}/{{$image_shot}}" class="media-object img-rounded img-thumbnail" width="70" />
	</a> 
	
	<a href="{{URL::to('shots')}}/{{$id}}">
		{{ $title_shot }}
		</a> 
		
		/ {{ Lang::get('misc.likes') }} ({{ Helper::formatNumber( $sql->getTotal() ) }})

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
	<div class="col-md-4">
		@include('includes.ads')
	</div>
@stop
<?php 

 if( Auth::check() ) {
 	$followActive = Followers::where( 'follower', Auth::user()->id )
			 ->where( 'following', $user->id )->where('status',1)->first(); 
			 
       if( $followActive ) {
       	  $textFollow   = Lang::get('users.following');
		  $icoFollow    = '-ok';
		  $activeFollow = 'follow_active activeFollow';
       } else {
       		$textFollow   = Lang::get('users.follow');
		    $icoFollow    = '-plus';
			$activeFollow = '';
       }
	   
	   $user_blocked = DB::table('block_user')
	   ->where('user_id',Auth::user()->id)
	   ->where('user_blocked',$user->id)
	   ->orWhere('user_id',$user->id)
	   ->where('user_blocked',Auth::user()->id)
	   ->first();
	   
	   $unblock = DB::table('block_user')
	   ->where('user_id',Auth::user()->id)
	   ->where('user_blocked',$user->id)
	   ->first();
	   
 }//<<<<---- Auth
?>
@section('jumbotron')
<div class="jumbotron static-header-profile jumbotron-cover" style="background: url('{{ URL::asset("public/cover/$user->cover") }}') no-repeat center center #000; background-size: cover;">
      <div class="container wrap-jumbotron">
      	
      	<div class="media media-static-header">
      		<div class="pull-left">
      		<a href="{{ URL::to('@') }}{{ $user->username }}">
        		<img src="{{ URL::asset('public/avatar').'/'.$user->avatar }}" width="110" height="110" class="img-circle border-avatar-profile" />
        		</a> 
      		</div>
      		
      		<div class="media-body none-overflow">
		      	<h1 class="title-item none-overflow media-heading">
		        	<a href="{{ URL::to('@') }}{{ $user->username }}" class="linkUser">
		        		{{ e( $user->name ) }}
		        		</a> 
		        		@if($user->type_account == 2 ) 
			      		<span class="label pro-badge">{{ Lang::get('misc.pro') }}</span>
			      		@endif
			      		
			      		@if($user->type_account == 3 ) 
			      		<span class="label team-badge">{{ Lang::get('misc.team') }}</span>
			      		@endif
		        	</h1>
		        	
		        <div class="btn-block">
		        	 @if( Auth::check() )
		        	 
		        	 @if( $user->id != Auth::user()->id )
		        	 
		        	 @if( !$user_blocked )
				        <button type="button" class="btn btn-default btn-follow-lg btn-sm add-button followBtn {{ $activeFollow }}" data-id="{{ $user->id }}" data-follow="{{ Lang::get('users.follow') }}" data-following="{{ Lang::get('users.following') }}">
		      				<i class="glyphicon glyphicon{{ $icoFollow }} myicon-right"></i> {{ $textFollow }}
		      			</button>
		      			@endif {{-- User Blocked --}}
		      			
	@if( isset( $unblock ) )
      			<a href="javascript:void(0);" data-id="{{$user->id}}" class="btn btn-default btn-follow-lg btn-sm" id="unblock">
      				<i class="glyphicon glyphicon-eye-close myicon-right"></i> {{ Lang::get('users.unblock') }}
      				</a>
      @endif
		      			
		      		@else 
		      			
		      			<a href="{{ URL::to('account') }}" class="btn btn-default btn-follow-lg btn-sm">
		      				<i class="icon-pencil2 myicon-right"></i> {{ Lang::get('users.edit_profile') }}
		      			</a>
		      			
		      			@endif
		      			{{-- End user distinct Auth --}}
		      			
		      			@else
		      			<button type="button" class="btn btn-default btn-follow-lg btn-sm add-button">
		      				<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.follow') }}
		      				</button>
		      			
		      			@endif
		      			{{-- End Auth --}}
		        	</div>
      		</div>
      	</div>

       </div>
    </div>
    
    @include('includes.menu-user')
    
  @stop
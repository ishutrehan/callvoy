<nav class="navbar navbar-default margin-b-10 navbar-user-ui" role="navigation">
    	<div class="container">
    		<div style="width: 100%; text-align: center;">
    		<ul class="nav nav-pills nav-user-profile">
    			<li @if( Request::is('@'.$user->username.'') ) class="active" @endif><a href="{{ URL::to('@') }}{{ $user->username }}">{{ Lang::get('misc.shots') }} <small class="btn-block sm-btn-size text-center counter-sm">{{ Helper::formatNumber( User::totalShots( $user->id ) ) }}</small></a></li>
    			<li @if( Request::is('@'.$user->username.'/followers') ) class="active" @endif><a href="{{ URL::to('@') }}{{ $user->username }}/followers">{{ Lang::get('users.followers') }} <small class="btn-block sm-btn-size text-center counter-sm">{{ Helper::formatNumber( User::totalFollowers( $user->id ) ) }}</small></a></li>
    			<li @if( Request::is('@'.$user->username.'/following') ) class="active" @endif><a href="{{ URL::to('@') }}{{ $user->username }}/following">{{ Lang::get('users.following') }} <small class="btn-block sm-btn-size text-center counter-sm">{{ Helper::formatNumber( User::totalFollowing( $user->id ) ) }}</small></a></li>
    			<!-- <li @if( Request::is('@'.$user->username.'/likes') ) class="active" @endif><a href="{{ URL::to('/') }}/@{{ $user->username }}/likes">Likes <small class="btn-block sm-btn-size text-center counter-sm">{{ Helper::formatNumber( User::totalLikes( $user->id ) ) }}</small></a></li>-->
    			<li class="dropdown">
    				<a href="javascript:void(0);" class="dropdown-toggle toggle-list" data-toggle="dropdown">
    					
    					{{ Lang::get('misc.more') }} 
    					<small class="btn-block sm-btn-size text-center counter-sm">
    						<i class="fa fa-circle more-button"></i>
	    					<i class="fa fa-circle more-button"></i>
	    					<i class="fa fa-circle more-button"></i>
    					</small>
    				</a>
    				
    				<!-- dropdown-menu -->
      				<ul class="dropdown-menu dropdown-settings user-list-nav" id="setting-actions">
      				@if( $user->listed()->count() != 0 )	
      					<li @if( Request::is('@'.$user->username.'/lists/memberships') ) class="active-list" @endif>
      						<a href="{{ URL::to('@') }}{{ $user->username }}/lists/memberships">
      							{{ Lang::get('users.listed') }} ({{ Helper::formatNumber( $user->total_listed() ) }})
      							</a>
      						</li>
      						@endif
      						
      						
      						@if( $user->type_account == 3 )
      							<li @if( Request::is('@'.$user->username.'/members') ) class="active-list" @endif>
      						<a href="{{ URL::to('@') }}{{ $user->username }}/members">
      							{{ Lang::choice('users.members',User::where('team_id',$user->id)->count()) }} ({{ Helper::formatNumber( User::where('team_id',$user->id)->count() ) }})
      							</a>
      						</li>
      						@endif
      						
      					<li @if( Request::is('@'.$user->username.'/lists') ) class="active-list" @endif>
      						<a href="{{ URL::to('@') }}{{ $user->username }}/lists">
      							{{ Lang::get('users.lists') }} ({{ Helper::formatNumber( $user->lists()->where('type',1)->count() ) }})
      							</a>
      						</li>
      						
      						<li @if( Request::is('@'.$user->username.'/likes') ) class="active-list" @endif>
      						<a href="{{ URL::to('@') }}{{ $user->username }}/likes">
      							{{ Lang::get('users.likes') }} ({{ Helper::formatNumber( User::totalLikes( $user->id ) ) }})
      							</a>
      						</li>
      						
      					@if( $user->type_account != 1 )	
      						<li @if( Request::is('@'.$user->username.'/goods') ) class="active-list" @endif>
      						<a href="{{ URL::to('@') }}{{ $user->username }}/goods">
      							{{ Lang::get('misc.goods_for_sale') }} ({{ Helper::formatNumber( $user->shots()->where('url_purchased','!=','')->count() ) }})
      							</a>
      						</li>
      						@endif
      					
      					@if( $user->type_account != 1 )	
      						<li @if( Request::is('@'.$user->username.'/projects') ) class="active-list" @endif>
      						<a href="{{ URL::to('@') }}{{ $user->username }}/projects">
      							{{ Lang::get('users.projects') }} ({{ Helper::formatNumber( $user->projects()->count() ) }})
      							</a>
      						</li>
      						@endif
	               	</ul>
	           		 
    			</li>
    			
    			@if( $user->team_id != 0 )	
      					<?php $team = User::find($user->team_id); ?>
  						<li class="pull-right team-user showTooltip" title="{{e($team->name)}}" data-toggle="tooltip" data-placement="top">
  							<a href="{{ URL::to('@') }}{{ $team->username }}" class="toggle-list" >
  								<img src="{{URL::asset('public/avatar').'/'.$team->avatar}}" width="30" height="30" class="img-circle" />
  							</a>
  							</li>
  						@endif
    		</ul>
    		</div>
    	</div><!-- container -->
  </nav>
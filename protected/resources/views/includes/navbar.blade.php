<?php 
$userAuth = Auth::user(); 
if( Auth::check() ) {
	
	// Notifications	
	$notifications_count = Notifications::where('destination',Auth::user()->id)->where('status',0)->count();
	
	// Messages	
	$messages_count = Messages::where('to_user_id',Auth::user()->id)->where('status','new')->count();
}
?>
<div class="navbar navbar-inverse">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            
            <?php if( isset( $totalNotify ) ) : ?>
        	<span class="notify"><?php echo $totalNotify; ?></span>
        	<?php endif; ?>
        	
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="{{ URL::to('/') }}">
          	<img src="{{ URL::asset('public/img/logo.png') }}" class="logo"/>
          	</a>
        </div><!-- navbar-header --> 
        
        <div class="navbar-collapse collapse">
        	
        	@include('includes.form-search')
        
        	
        	<ul class="nav navbar-nav navbar-right">
        		
        		<li class="@if(Request::is('/'))active @endif dropdown">
        			<a href="javascript:void(0);" data-toggle="dropdown">{{ Lang::get('misc.shots') }}</a>
        			
        			<ul class="dropdown-menu dropdown-nav-session" role="menu" aria-labelledby="dropdownMenu1">
		           		<li class="li-group-nav"><a href="{{ URL::to('/') }}" class="myprofile text-overflow">{{ Lang::get('misc.all') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('popular') }}">{{ Lang::get('misc.popular') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('latest') }}">{{ Lang::get('misc.latest') }}</a></li>
		             </ul>
		             
        			</li>
				
				
				<li class="@if(Request::is('designers'))active @endif dropdown">
					<a href="javascript:void(0);" data-toggle="dropdown">{{ Lang::get('misc.designers') }}</a>
				
					<ul class="dropdown-menu dropdown-nav-session" role="menu" aria-labelledby="dropdownMenu2">
		           		<li class="li-group-nav"><a href="{{ URL::to('designers') }}" class="myprofile text-overflow">{{ Lang::get('misc.all') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('designers') }}?hire=on">{{ Lang::get('misc.for_hire') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('designers/prospects') }}">{{ Lang::get('misc.prospects') }}</a></li>
		             </ul>
				</li>
				
				
				<li class="@if(Request::is('jobs'))active @endif dropdown">
					<a href="javascript:void(0);" data-toggle="dropdown">{{ Lang::get('misc.jobs') }}</a>
					
					<ul class="dropdown-menu dropdown-nav-session" role="menu" aria-labelledby="dropdownMenu3">
		           		<li class="li-group-nav"><a href="{{ URL::to('jobs') }}" class="myprofile text-overflow">{{ Lang::get('misc.all') }}</a></li>
						
						@if( Auth::check() && Auth::user()->location != '' )
							<li class="li-group-nav"><a href="{{ URL::to('jobs') }}?location={{ Auth::user()->location }}">{{ Lang::get('misc.near_you') }}</a></li>
						@endif
						
						<li class="li-group-nav"><a href="{{ URL::to('jobs') }}?anywhere=on&location=">{{ Lang::get('misc.remot_anywhere') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('jobs?teams=on') }}" class="myprofile text-overflow">{{ '@'.Lang::get('misc.teams') }}</a></li>
						@if($settings->allow_jobs == 'on')
						<li class="li-group-nav"><a href="{{ URL::to('jobs/new') }}">{{ Lang::get('misc.post_a_Job') }}</a></li>
		             	@endif
		             </ul>
		             
					</li>
			
			<li class="dropdown">
				<a href="javascript:void(0);" class="dropdown-toggle toggle-list" data-toggle="dropdown">
    					
    					<small style="font-size: 7px;">
    						<i class="fa fa-circle"></i>
	    					<i class="fa fa-circle"></i>
	    					<i class="fa fa-circle"></i>
    					</small> <span class="title-dropdown" style="margin-left: 5px;">{{ Lang::get('misc.more') }}</span>
    				</a>
    				
    				<ul class="dropdown-menu dropdown-nav-session" role="menu" aria-labelledby="dropdownMenu4">
		           		<li class="li-group-nav"><a href="{{ URL::to('teams') }}" class="myprofile text-overflow">{{ Lang::get('misc.teams') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('goods') }}">{{ Lang::get('misc.goods_for_sale') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('projects') }}">{{ Lang::get('users.projects') }}</a></li>
						<li class="li-group-nav"><a href="{{ URL::to('tags') }}">{{ Lang::get('misc.tags') }}</a></li>
		             </ul>
			</li>
		
		@if( Auth::check() )	
			<!-- Messages -->
			<li class="dropdown @if(Request::is('messages'))active @endif">
	          <a href="{{ URL::to('messages') }}" class="messages" title="Messages">
	          	<span class="notify @if( $messages_count != 0 ) displayBlock @endif" id="noti_msg">
	          		@if( $messages_count != 0 ) {{ $messages_count }} @endif
	          	</span>
	          		<span class="fa fa-envelope"></span> <span class="title-dropdown">{{ Lang::get('users.messages') }}</span>
	          	</a> 
           </li>
           
			<li class="dropdown @if(Request::is('notifications'))active @endif">
	          <a href="{{ URL::to('notifications') }}" title="Notifications">
	         
	          	<span class="notify @if( $notifications_count != 0 ) displayBlock @endif" id="noti_connect">
	          		@if( $notifications_count != 0 ) {{ $notifications_count }} @endif
	          		</span>
	          	
	          	<span class="fa fa-bell"></span> <span class="title-dropdown">{{ Lang::get('users.notifications') }}</span>
	          	</a> 
           </li>
           
           <li class="dropdown">
	          <a href="javascript:void(0);" data-toggle="dropdown" class="userAvatar myprofile dropdown-toggle">
	          		<img src="{{ URL::asset('public/avatar').'/'.$userAuth->avatar }}" alt="User" class="img-circle" width="20" height="20"> 
	          	<span class="title-dropdown">{{ Lang::get('users.my_profile') }}</span>
	          	</a>
	          	
	          <ul class="dropdown-menu dropdown-nav-session" role="menu" aria-labelledby="dropdownMenu4">
           		<li class="li-group-nav"><a href="{{ URL::to('@') }}{{ $userAuth->username }}" class="myprofile text-overflow"><i class="glyphicon glyphicon-user myicon-right"></i> {{ e($userAuth->name) }}</a></li>
			
			@if( $userAuth->role == 'admin' || $userAuth->role == 'moderator' )
				<li class="li-group-nav"><a href="{{ URL::to('panel/admin') }}"><i class="icon-cogs myicon-right"></i> {{ Lang::get('admin.admin') }}</a></li>
			@endif	
				
				<li class="li-group-nav"><a href="{{ URL::to('account') }}"><i class="glyphicon glyphicon-cog myicon-right"></i> {{ Lang::get('users.account_settings') }}</a></li>
			
			@if( $userAuth->type_account == 2 || $userAuth->type_account == 3 )
				<li class="li-group-nav"><a href="{{ URL::to('stats') }}"><i class="icon-stats myicon-right"></i> {{ Lang::get('users.stats') }}</a></li>
			@endif
				<li><a href="{{ URL::to('session/logout') }}" class="logout"><i class="glyphicon glyphicon-log-out myicon-right"></i> {{ Lang::get('users.logout') }}</a></li>
           </ul>
        
        </li>
           
			@if( $userAuth->type_account == 2 || $userAuth->type_account == 3 || $userAuth->team_id != 0 )
				<li><a class="log-in" href="{{ URL::to('upload') }}" title="{{ Lang::get('misc.upload') }}">
					<i class="glyphicon glyphicon-cloud-upload myicon-right"></i> <span class="title-dropdown">{{ Lang::get('misc.upload') }}</span></a>
					</li>
			@endif
			
		
		{{-- SESSION NULL --}}	
		@else 	
			
			<li><a class="log-in" href="{{ URL::to('login') }}">{{ Lang::get('auth.sign_in_nav') }}</a></li>
		@endif	
			</ul>
          
</div><!--/.navbar-collapse -->
      </div>
    </div>
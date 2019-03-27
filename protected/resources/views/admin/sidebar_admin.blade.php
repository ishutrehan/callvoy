<div class="list-group">
     		
     	  <!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin') }}" class="list-group-item @if(Request::is('panel/admin'))active @endif"> 
		  	<i class="icon-cog myicon-right"></i> {{ Lang::get('admin.general_settings') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/languages') }}" class="list-group-item @if(Request::is('panel/admin/languages'))active @endif"> 
		  	<i class="icon-earth myicon-right"></i> {{ Lang::get('misc.languages') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/statistics') }}" class="list-group-item @if(Request::is('panel/admin/statistics') )active @endif"> 
		  	<i class="icon-stats myicon-right"></i> {{ Lang::get('users.stats') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/pages') }}" class="list-group-item @if(Request::is('panel/admin/pages'))active @endif"> 
		  	<i class="icon-file myicon-right"></i> {{ Lang::get('admin.pages') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/members') }}" class="list-group-item @if(Request::is('panel/admin/members'))active @endif"> 
		  	<i class="icon-users myicon-right"></i> {{ Lang::get('admin.members') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/members-reported') }}" class="list-group-item @if(Request::is('panel/admin/members-reported'))active @endif"> 
		  	<i class="icon-blocked myicon-right"></i> {{ Lang::get('admin.members_reported') }} 
		  	
		  	@if( MembersReported::all()->count() != 0 )
		  	<span class="label label-danger">{{MembersReported::all()->count()}}</span>
		  	@endif
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
		  <!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/shots-reported') }}" class="list-group-item @if(Request::is('panel/admin/shots-reported'))active @endif"> 
		  	<i class="icon-flag myicon-right"></i> {{ Lang::get('admin.shots_reported') }} 
		  	
		  	@if( ShotsReported::all()->count() != 0 )
		  	<span class="label label-danger">{{ShotsReported::all()->count()}}</span>
		  	@endif
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/payments-settings') }}" class="list-group-item @if(Request::is('panel/admin/payments-settings'))active @endif"> 
		  	<i class="icon-credit myicon-right"></i> {{ Lang::get('admin.payments_settings') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/social-login') }}" class="list-group-item @if(Request::is('panel/admin/social-login'))active @endif"> 
		  	<i class="icon-share myicon-right"></i> {{ Lang::get('admin.social_login') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/profiles-social') }}" class="list-group-item @if(Request::is('panel/admin/profiles-social'))active @endif"> 
		  	<i class="icon-user myicon-right"></i> {{ Lang::get('admin.profiles_social') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/payments') }}" class="list-group-item @if(Request::is('panel/admin/payments') || Request::is('panel/admin/payments-jobs') || Request::is('panel/admin/payments-ads'))active @endif"> 
		  	<i class="glyphicon glyphicon-usd myicon-right"></i> {{ Lang::get('admin.payments') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/lists') }}" class="list-group-item @if(Request::is('panel/admin/lists') )active @endif"> 
		  	<i class="icon-list myicon-right"></i> {{ Lang::get('admin.manage_lists') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/projects') }}" class="list-group-item @if(Request::is('panel/admin/projects') )active @endif"> 
		  	<i class="icon-briefcase myicon-right"></i> {{ Lang::get('admin.manage_projects') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/jobs') }}" class="list-group-item @if(Request::is('panel/admin/jobs') )active @endif"> 
		  	<i class="icon-pushpin myicon-right"></i> {{ Lang::get('admin.manage_jobs') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/ads') }}" class="list-group-item @if(Request::is('panel/admin/ads') )active @endif"> 
		  	<i class="icon-bullhorn myicon-right"></i> {{ Lang::get('admin.manage_ads') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/google-adsense') }}" class="list-group-item @if(Request::is('panel/admin/google-adsense') )active @endif"> 
		  	<i class="icon-google myicon-right"></i> Google Adsense 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('panel/admin/comments') }}" class="list-group-item @if(Request::is('panel/admin/comments') )active @endif"> 
		  	<i class="icon-bubbles2 myicon-right"></i> {{ Lang::get('misc.comments') }} 
		  	
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->

</div><!-- / list-group -->
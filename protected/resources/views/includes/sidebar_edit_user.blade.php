<div class="list-group">
     		
     		<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('messages') }}" class="list-group-item @if(Request::is('messages'))active @endif"> 
		  	<i class="fa fa-envelope myicon-right"></i> {{ Lang::get('users.messages') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		@if( Auth::user()->type_account == 3 )  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('members') }}" class="list-group-item @if(Request::is('members'))active @endif"> 
		  	<i class="icon-users myicon-right"></i> {{ Lang::get('misc.team_members') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	@endif
		  
		  @if( Auth::user()->type_account == 2 || Auth::user()->type_account == 3 )  		
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('stats') }}" class="list-group-item @if(Request::is('stats'))active @endif"> 
		  	<i class="icon-stats myicon-right"></i> {{ Lang::get('users.stats') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	@endif
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('my/ads') }}" class="list-group-item @if(Request::is('my/ads'))active @endif"> 
		  	<i class="icon-bullhorn myicon-right"></i> {{ Lang::get('misc.my_ads') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('my/jobs') }}" class="list-group-item @if(Request::is('my/jobs'))active @endif"> 
		  	<i class="icon-pushpin myicon-right"></i> {{ Lang::get('misc.my_jobs') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  	
     	   <!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('account') }}" class="list-group-item @if(Request::is('account'))active @endif"> 
		  	<i class="glyphicon glyphicon-pencil myicon-right"></i> {{ Lang::get('users.edit_profile') }} 
			  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	
		  
		  <!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('account/avatar_cover') }}" class="list-group-item @if(Request::is('account/avatar_cover'))active @endif"> 
		  	<i class="glyphicon glyphicon-camera myicon-right"></i> {{ Lang::get('misc.avatar_cover') }} 
		  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->
		  	
		  	<!-- **** list-group-item **** -->	
		  <a href="{{ URL::to('account/password') }}" class="list-group-item @if(Request::is('account/password'))active @endif"> 
		  	<i class="glyphicon glyphicon-lock myicon-right"></i> {{ Lang::get('auth.password') }} 
		  	<span class="pull-right">
			  		<i class="fa fa-chevron-right "></i>
			  	</span>
		  	</a> <!-- **** ./ list-group-item **** -->	

</div><!-- / list-group -->
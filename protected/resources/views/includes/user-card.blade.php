<?php  
// namespace App\Helpers;
// use App\Helpers\Helper;
// use Illuminate\Support\Facades\Auth;

?>

<!-- panel-default -->
	<div class="panel panel-default">
			<div class="panel-body padding-top padding-top-zero padding-right-zero padding-left-zero">
				    					
				<div style="background: url({{ asset('protected/public/cover').'/'.Auth::user()->cover }}) no-repeat center center #0088E2; background-size: cover;" class="cover-wall"></div>
				
			<div class="media media-visible pd-right">
				  <a href="{{ url('@') }}{{ Auth::user()->username }}" class="btn-block photo-card-live myprofile">
				    <img class="border-image-profile img-circle photo-card" alt="Image" src="{{ asset('protected/public/avatar').'/'.Auth::user()->avatar }}" width="80" height="80">
				  </a>
				  <div class="media-body">
				    <h4 class="user-name-profile-card btn-block  text-center">
				    	<a class="myprofile" href="{{ url('@') }}{{ Auth::user()->username }}">
				    		<span class="none-overflow">{{ e( Auth::user()->name ) }}</span>
				    		</a>
					</h4>
				  </div>
				</div>
		
	    	<?php 	/*<ul class="nav list-inline nav-pills btn-block nav-user-profile-wall">
	    			<li><a href="{{ url('@') }}{{ Auth::user()->username }}">{{ trans('misc.shots') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalShots( Auth::user()->id ) ) }}</small></a></li>
	    			<li><a href="{{ url('@') }}{{ Auth::user()->username }}/followers">{{ trans('users.followers') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalFollowers( Auth::user()->id ) ) }}</small></a></li>
	    			<li><a href="{{ url('@') }}{{ Auth::user()->username }}/following">{{ trans('users.following') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalFollowing( Auth::user()->id ) ) }}</small></a></li>
	    			</ul>*/

	    			?>
	    		
			</div>
			
			<!-- Panel Body -->
	</div><!-- panel-default -->
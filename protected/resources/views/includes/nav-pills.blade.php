<?php
// Time Frame
switch( Input::get('timeframe') ) {
			case 'week':
				$timeframe_text = Lang::get('misc.week');

				break;
			case 'month':
				$timeframe_text = Lang::get('misc.month');

				break;
			case 'year':
				$timeframe_text = Lang::get('misc.year');

				break;

				default:
					$timeframe_text = Lang::get('misc.all_time');
	}

// Shots Options
switch( Request::path() ) {
			case Request::is('popular'):
				$shots_text = Lang::get('misc.popular_shots');

				break;
			case Request::is('most/commented'):
				$shots_text = Lang::get('misc.most_commented');

				break;
			case Request::is('most/viewed'):
				$shots_text = Lang::get('misc.most_viewed');

				break;

				default:
					$shots_text = Lang::get('misc.last_shots');
	}
 ?>
<ul class="nav nav-pills tabs-index nav-p">
	        @if( Auth::check() )
	        <li class="@if(Request::is('/')) active @endif"><a href="{{ URL::to('/') }}"><i class="icon-user myicon-right"></i> {{Lang::get('users.following')}}</a></li>
	        @endif
	        
	        <!-- Shots -->
	        <li class="dropdown">
	        	<a href="{{ URL::to('latest') }}" class="dropdown-toggle" data-toggle="dropdown">
	        		<i class="icon-lightning myicon-right"></i> {{$shots_text}} <i class="fa fa-angle-down"></i>
	        		</a>
	        		
	        		<ul class="dropdown-menu nav_pills_menu" style="width: 100%;">
	        			<li class="@if(Request::is('latest') || Request::is('/') && Auth::guest() ) active-list @endif"><a href="{{ URL::to('latest') }}"> <i class="glyphicon glyphicon-fire myicon-right"></i> {{Lang::get('misc.last_shots')}} </a></li>
			          	<li class="@if(Request::is('popular')) active-list @endif" ><a href="{{ URL::to('popular') }}"><i class="glyphicon glyphicon-heart myicon-right"></i> {{Lang::get('misc.popular_shots')}}</a></li>
			          	<li class="@if(Request::is('most/commented')) active-list @endif" ><a href="{{ URL::to('most/commented') }}"><i class="icon-bubbles myicon-right"></i> {{Lang::get('misc.most_commented')}}</a></li>
			          	<li class="@if(Request::is('most/viewed')) active-list @endif" ><a href="{{ URL::to('most/viewed') }}"><i class="icon-eye myicon-right"></i> {{Lang::get('misc.most_viewed')}}</a></li>
	        		</ul>
	        </li><!-- Shots -->
	          
	          @if( Request::is('popular') 
	          	|| Request::is('most/commented') 
	          	|| Request::is('most/viewed')
	          	)
	          	
	          <!-- Time Frame -->
          	  <li class="dropdown">
    			 	
    			 	<a href="#" class="dropdown-toggle" data-toggle="dropdown">
    			 		<i class="icon-clock myicon-right"></i> {{ $timeframe_text }} <i class="fa fa-angle-down"></i>
    			 	</a>
    			 	
	    			<ul class="dropdown-menu nav_pills_menu" style="width: 100%;">
      					<li @if( !Input::get('timeframe') || Input::get('timeframe') != 'week' && Input::get('timeframe') != 'month' && Input::get('timeframe') != 'year' ) class="active-list" @endif>
      						<a href="{{ URL::to('/').'/'.Request::path() }}">
      							{{ Lang::get('misc.all_time') }}
      							</a>
      						</li>
      						
      						<li @if( Input::get('timeframe') == 'week' ) class="active-list"  @endif>
      						<a href="{{ URL::to('/').'/'.Request::path() }}?timeframe=week">
      							{{ Lang::get('misc.week') }} 
      							</a>
      						</li>
      						
      						<li @if( Input::get('timeframe') == 'month' ) class="active-list"  @endif>
      						<a href="{{ URL::to('/').'/'.Request::path() }}?timeframe=month">
      							{{ Lang::get('misc.month') }}
      							</a>
      						</li>
      						
      						<li @if( Input::get('timeframe') == 'year' ) class="active-list"  @endif>
      						<a href="{{ URL::to('/').'/'.Request::path() }}?timeframe=year">
      							{{ Lang::get('misc.year') }}
      							</a>
      						</li>
      					</ul>
         	</li><!-- Time Frame -->
         	@endif
       </ul>

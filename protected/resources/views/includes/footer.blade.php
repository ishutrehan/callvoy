<?php
   $totalShots = Shots::where( 'status', 1 )->count();
   $totalDesigners = User::where( 'status', 'active' )->count();
 ?>
<!-- ***** Footer ***** -->
    <footer class="footer-main">
    	<div class="container">
    		
    		<div class="row text-center">
    			<div class="col-md-12">
    				<a href="{{ URL::to('/') }}">
    					<img width="90px" src="{{ URL::asset('public/img/logo-watermark.png') }}" />
    				</a>
    			   <p class="desc-footer">{{ $settings->description }}</p>
    			</div><!-- ./End col-md-* -->
    			
    			<div class="col-md-12 margin-tp-xs">
    				<strong class="number-shots">{{ Helper::formatNumber( $totalShots ) }}</strong> {{ Lang::choice('misc.shots_plural',$totalShots) }} - 
    				<strong class="number-shots">{{ Helper::formatNumber( $totalDesigners ) }}</strong> {{ Lang::choice('misc.designers_plural',$totalDesigners) }}
    				<ul class="list-inline">
    					@if( $settings->twitter != '' )
					   <li><a href="{{$settings->twitter}}" class="ico-social"><i class="fa fa-twitter"></i></a></li>
					    @endif
					    
					    @if( $settings->facebook != '' )
					   <li><a href="{{$settings->facebook}}" class="ico-social"><i class="fa fa-facebook"></i></a></li>
					   @endif
					   
					   @if( $settings->instagram != '' )
					   <li><a href="{{$settings->instagram}}" class="ico-social"><i class="fa fa-instagram"></i></a></li>
					   @endif
					   
					   @if( $settings->linkedin != '' )
					   <li><a href="{{$settings->linkedin}}" class="ico-social"><i class="fa fa-linkedin"></i></a></li>
					   @endif
					   
					   @if( $settings->googleplus != '' )
					   <li><a href="{{$settings->googleplus}}" class="ico-social"><i class="fa fa-google-plus"></i></a></li>
				       @endif
				    </ul>
    			</div><!-- ./End col-md-* -->
    			    			
    			<div class="col-md-12">
    				<ul class="list-inline">
    		@foreach( Pages::all() as $page )
        			<li><a class="link-footer" href="{{ URL::to('/').'/'.$page->slug }}">{{ $page->title }}</a></li>
        	@endforeach
        	<li><a class="link-footer" href="{{ URL::to('api') }}">{{ Lang::get('misc.api') }}</a></li>
        	
        	@foreach( Languages::all() as $lang )
        	<!-- Start Languages -->
        	<li>
        		<a class="link-footer" href="{{ URL::to('lang').'/'.$lang->abbreviation }}">{{ e($lang->name) }}</a>
        	</li>
        	@endforeach

        	<!-- ./End Languages -->
        	
    				</ul>
    			</div><!-- ./End col-md-* -->
    			
    			<div class="col-md-12">
    				<p>&copy; {{ $settings->title }} - <?php echo date('Y'); ?></p>
    			</div><!-- ./End col-md-* -->
    			
    		</div><!-- ./End Row -->
    	</div><!-- ./End Container -->
    </footer><!-- ***** Footer ***** -->
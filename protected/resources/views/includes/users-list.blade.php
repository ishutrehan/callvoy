<?php 
  
  // ** DATE ** //
  $dateNow   = date('Y-m-d G:i:s');
	 
  $shots_last = Shots::where('user_id', $designer->id)
  ->where('status',1)
  ->orWhere('team_id',$designer->id)
  ->where('status',1)
  ->orderBy('id','DESC')
  ->take(3)->get(); 
  
  if( Auth::check() ) {
 	$followActive = Followers::where( 'follower', Auth::user()->id )
			 ->where( 'following', $designer->id )->where('status',1)->first(); 
			 
       if( $followActive ) {
       	  $textFollow   = Lang::get('users.following');
		  $icoFollow    = '-ok';
		  $activeFollow = 'btnFollowActive';
       } else {
       		$textFollow   = Lang::get('users.follow');
		    $icoFollow    = '-plus';
			$activeFollow = '';
       }
	   
	   $unblock = DB::table('block_user')
	   ->where('user_id',Auth::user()->id)
	   ->where('user_blocked',$designer->id)
	   ->orWhere('user_id',$designer->id)
	   ->where('user_blocked',Auth::user()->id)
	   ->first();
	   
 }//<<<<---- Auth
 
 $jobsDesigner = Jobs::where('paypal.payment_status', '=', 'Completed')
	->where('date_end', '>=', $dateNow)
	->where('jobs.user_id', $designer->id)
	->where('members.type_account', 3)
	->leftjoin('members', 'jobs.user_id', '=', 'members.id')
	->leftjoin('paypal_payments_jobs as paypal', 'paypal.item_id', '=', 'jobs.id')
	->count();
    
 ?>
      <div class="media media-designer">
    		<span class="pull-left">
    			<a class="image-thumb" title="{{$designer->name}}" href="{{ URL::to('@').$designer->username }}">
    			<img width="80" height="80" class="media-object img-circle" src="{{ URL::asset('public/avatar') .'/'.$designer->avatar}}" />
    			</a>
    		</span>
    		<div class="media-body media-jobs">
    			<div class="pull-left">
    				<h4 class="media-heading">
    				<a class="link-user-profile" title="{{$designer->name}}" href="{{ URL::to('@').$designer->username }}">{{ e(Str::limit($designer->name, 25,  '...' )) }}</a> 
 
    				@if( $designer->hire == 1 )
    				<small class="shotTooltip" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('misc.available_for_hire') }}"><i class="fa fa-envelope myicon-right"></i></small>
    				@endif
    				
    				@if( $jobsDesigner != 0 )
    				<a href="{{URL::to('@').$designer->username.'/jobs'}}" class="text-decoration-none">
    					<small class="shotTooltip" data-toggle="tooltip" data-placement="top" title="{{ e($designer->name) }} {{ Lang::get('misc.is_hiring') }}"><i class="icon-pushpin"></i></small>
    				</a>
    				
    				@endif
    				
    			</h4>
    			 <!-- List group -->
    			 <ul class="list-group list-designer">
    			 	@if( $designer->location != '' )
    			 	<li><i class="glyphicon glyphicon-map-marker"></i> {{ e(Str::limit($designer->location, 20,  '...')) }}</li>
    			 	@endif
    			 		
    			 		@if( Auth::check() )
    			 		
    @if( $designer->id != Auth::user()->id )
    
    	@if( !$unblock )
    			 <li class="mg-bottom-xs "> 
    			 		<span class="dropdown">
    			 			<button type="button" class="dropdown-toggle btn btn-settings btn-xs" data-toggle="dropdown">
	    			 			<i class="glyphicon glyphicon-cog"></i>
	    			 		</button>
	    			<ul class="dropdown-menu" style="top: 22px;">
      					<li>
      						<a href="javascript:void(0);" id="user{{$designer->id}}" data-user-id="{{$designer->id}}" class="add_remove_lists">
      							{{ Lang::get('users.add_remove_lists') }}
      							</a>
      						</li>
      						
      					</ul>
    			 		</span>	
	    			 		
	    			 		<button type="button" class="btn btn-default btn-xs add-button btn-follow btnFollow myicon-right {{ $activeFollow }}" data-id="{{ $designer->id }}" data-follow="{{ Lang::get('users.follow') }}" data-following="{{ Lang::get('users.following') }}">
	    			 			<i class="glyphicon glyphicon{{ $icoFollow }} myicon-right"></i> {{ $textFollow }}
	    			 		</button>
    			 		</li>
    			 		
    			 		@endif
    			 		{{-- unblock  --}}
    			 		
    			 		@endif
    			 		{{-- Avoid self follow  --}}
    			 		
    			 		@else
    			 		<li class="mg-bottom-xs"> 
	
	    			 		<button type="button" class="btn btn-default btn-xs add-button btn-follow myicon-right shotTooltip" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('misc.sign_in_or_sign_up') }}">
	    			 			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.follow') }}
	    			 		</button>
    			 		</li>
    			 		@endif
    			 		
    			 		<li>
    			 			<a href="{{ URL::to('/') }}/{{ '@'.$designer->username }}" class="links-ds">
    			 			<strong>{{ Helper::formatNumber( User::totalShots( $designer->id ) ) }}</strong> 
    			 			{{ Lang::get('misc.shots') }}</a> | 
    			 			
    			 		<a href="{{ URL::to('/') }}/{{ '@'.$designer->username }}/followers" class="links-ds">
    			 			<strong>{{ Helper::formatNumber( User::totalFollowers( $designer->id ) ) }}</strong> {{ Lang::get('users.followers') }}
    			 			</a>
    			 		</li>
    			 </ul>
    			</div><!-- /End Pull Left -->
    			
      	
      	@if( $shots_last->count() != 0 )
    		<span class="pull-right cover-img-desing">
    			
    			@foreach(  $shots_last as $shots)
    			
    			@if( $shots->image != '' )
    			
    			<?php
				
				if( Str::slug( $shots->title ) == '' ) {
   	    
					$slugUrl  = '';
				} else {
					$slugUrl  = '-'.Str::slug( $shots->title );
				}
				
				$url_shot = URL::to('/').'/shots/'.$shots->id.$slugUrl;
				
    			 ?>
    			<a class="image-thumb" href="{{$url_shot}}">
    			<img width="110" class="media-object img-rounded img-thumbnail" src="{{ URL::asset('public/shots_img') .'/'.$shots->image}}" />
    			</a>
    			@endif
    			@endforeach
    			
    			
    		</span>
    		@endif

    		</div><!-- /End Media Body -->
    		
</div><!-- /End Media -->
    		
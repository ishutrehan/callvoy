<div class="row">
   
   @foreach ( $data as $key )
    	<div class="col-sm-6 col-md-3 col-thumb">
    		
   <?php 
   
   if( Auth::check() ) {
   $likeUser = Like::where( 'shots_id', $key->id )->where('user_id',Auth::user()->id)->where('status',1)->first(); 
   }
   
   /*if( Str::slug( $key->title ) == '' ) {
   	    
		$slugUrl  = '-'.Str::slug( $key->title );
	    $url_shot = URL::to('/').'/shots/'.$key->id.$slugUrl;
	} else {
		$slugUrl = Str::slug( $key->title );
		$url_shot = URL::to('/').'/shots/'.$key->id.'-'.$slugUrl;
	}*/
	if( Str::slug( $key->title ) == '' ) {
   	    
		$slugUrl  = '';
	} else {
		$slugUrl  = '-'.Str::slug( $key->title );
	}
	
	$url_shot = URL::to('/').'/shots/'.$key->id.$slugUrl;
   //print_r($likeUser); 
   ?>
	 
    		<div class="thumbnail @if( isset( $shots_users ) ) padding-top-zero @endif">
    			
    			
    			<div class="caption">
    				<p class="author p-author">
    				
    				@if( $key->user()->type_account == 2 ) 	
    					<a href="{{ URL::to('pro') }}" class="label label-primary btn-pro-xs pull-right">
    						{{ Lang::get('misc.pro') }}
    						</a>
    					@endif
    					
    					@if( $key->user()->type_account == 3 ) 	
    					<a href="{{ URL::to('teams') }}" class="label label-primary btn-team-xs pull-right">
    						{{ Lang::get('misc.team') }}
    						</a>
    					@endif
    					
    					<a href="{{ URL::to('/') }}/{{ '@'.$key->user()->username }}" class="myicon-right">
    						<img src="{{ URL::asset('public/avatar').'/'.$key->user()->avatar }}" width="20" height="20" class="img-circle img-avatar-shots" />
    					</a>
    					
    					<a href="{{ URL::to('/') }}/{{ '@'.$key->user()->username }}" class="myicon-right text-decoration-none" title="{{ $key->user()->name }}">
    					  {{ e( $key->user()->name ) }} 
    					  </a>
    					  
    					  
    					</p>
    			</div><!-- /caption -->
    			
    			
    				<a class="position-relative btn-block" href="{{$url_shot}}">
    				
    				@if( $key->extension == 'gif' )
    					<small class="gif-shot">GIF</small>
    				@endif
    					<img title="{{ e($key->title) }}" src="{{ URL::asset('public/shots_img') .'/'.$key->image }}" class="image-url img-responsive btn-block" />
    				</a>
    			
    			<div class="caption">
    				<h1 class="title-shots">
    					<a title="{{ $key->title }}" class="item-link" href="{{$url_shot}}">
    					 {{ e($key->title) }}
    					</a>
    				</h1>
    			</div><!-- /caption -->
    			
    			<div class="caption">
    				<p class="actions">
    					
    				
    					@if(Auth::check())
    					<span data-id="{{ $key->id }}" data-like="{{ Lang::get('misc.like') }}" data-like-active="{{ Lang::get('misc.unlike') }}" title="@if(!empty($likeUser)) {{ Lang::get('misc.unlike') }} @else {{ Lang::get('misc.like') }} @endif" class="btn btn-xs pull-left @if(!empty($likeUser)) btn-success @else btn-danger @endif btn-like likeButton @if(!empty($likeUser)) active @endif">
    						<i class="glyphicon glyphicon-heart"></i>
    					</span>
    					@else
    						
    					<a href="{{ URL::to('login') }}" class="btn btn-xs pull-left btn-danger btn-like shotTooltip" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('misc.sign_in_or_sign_up') }}">
    						<i class="glyphicon glyphicon-heart"></i>
    					</a>
    					@endif
    				
    					<span class="pull-right">
    						<i class="fa fa-heart myicon-right"></i> <span class="like_count myicon-right strongSpan">{{ Helper::formatNumber( Shots::totalLikes( $key->id ) ) }}</span>
    						<i class="fa fa-eye myicon-right"></i> <span class="myicon-right strongSpan">{{ Helper::formatNumber( Shots::totalVisits( $key->id ) ) }}</span>
    						<i class="fa fa-comment myicon-right"></i> <span class="myicon-right strongSpan">{{ Helper::formatNumber( Shots::totalComments( $key->id ) ) }}</span>
    						@if( $key->attachment != '' )	
    						<i class="icon-attachment myicon-right shotTooltip attachment" title="{{ Lang::get('misc.has_attachments') }}" data-toggle="tooltip" data-placement="top"></i>
    					   @endif
    					</span>
    					
    				</p>
    			</div><!-- /caption -->
    			
    		  </div><!-- /thumbnail -->
    		  
    	   </div><!-- /col-sm-6 col-md-4 -->
    	   
    	  @endforeach
        
    	  </div><!-- /row -->

   	    	  
     @if( $data->getTotal() != $data->count() )
     
    	   <hr />
    	   
    	   @if( Request::is('search') )
			<div class="btn-group paginator-style">
			   <?php echo $data->appends( array( 'q' => $q ) )->links(); ?> 
			</div>

		@else

		<div class="btn-group paginator-style">
		   <?php echo $data->links(); ?> 
		</div>
		
		@endif 
@endif

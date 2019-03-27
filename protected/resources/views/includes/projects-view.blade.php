<div class="row">
	@foreach( $data as $project )
		
	<?php
	$urlProject = URL::to('/').'/@'.$project->user()->username.'/projects/'.$project->id.'-'.$project->slug;
	 ?>
	@if( $project->shots()->count() != 0 )
	
		<div class="col-sm-6 col-md-3 col-thumb">
				<div class="thumbnail @if( isset( $projects_users ) ) padding-top-zero @endif">
				    	
				@if( !isset( $projects_users ) )
    			<div class="caption">
    				<p class="author p-author">
    				
    				@if( $project->user()->type_account == 2 ) 	
    					<a href="{{ URL::to('pro') }}" class="label label-primary btn-pro-xs pull-right">
    						{{ Lang::get('misc.pro') }}
    						</a>
    					@endif
    					
    					@if( $project->user()->type_account == 3 ) 	
    					<a href="{{ URL::to('teams') }}" class="label label-primary btn-team-xs pull-right">
    						{{ Lang::get('misc.team') }}
    						</a>
    					@endif
    					
    					<a href="{{ URL::to('/') }}/{{ '@'.$project->user()->username }}" class="myicon-right">
    						<img src="{{ URL::asset('public/avatar').'/'.$project->user()->avatar }}" width="20" height="20" class="img-circle img-avatar-shots" />
    					</a>
    					
    					<a href="{{ URL::to('/') }}/{{ '@'.$project->user()->username }}" class="myicon-right text-decoration-none" title="{{ $project->user()->name }}">
    					  {{ e( $project->user()->name ) }} 
    					  </a>
    					  
    					  
    					</p>
    			</div><!-- /caption -->
    			@endif
				    	
				      <a href="{{$urlProject}}" class="position-relative btn-block">
				      	<img src="{{ URL::asset('public/shots_img').'/'.$project->shots{0}->image }}" class="img-responsive btn-block">
				      </a>
	
@if( $project->shots()->count() >= 2 || $project->shots()->count() >= 3 )			
				      <!-- caption -->		      
		<div class="caption row" style="margin-top: 15px;">

    @if( $project->shots()->count() >= 2 )
			<div class="col-xs-6 col-md-6 col-right-pd">
				<a href="{{$urlProject}}">
    			<img width="100%" class="img-rounded" src="{{ URL::asset('public/shots_img').'/'.$project->shots{1}->image }}">
    			</a>
			</div>
			@endif
			
    	@if( $project->shots()->count() >= 3 )		
    	<div class="col-xs-6 col-md-6 col-left-pd">		    			    			
    		<a href="{{$urlProject}}">
    			<img width="100%" class="img-rounded" src="{{ URL::asset('public/shots_img').'/'.$project->shots{2}->image }}">
    			</a>
    			</div>
    			
    	@else
	    		<div class="col-xs-6 col-md-6 col-left-pd">		    			    			
	    		<span>
	    			<img width="100%" class="img-rounded" src="{{ URL::asset('public/img')}}/no-img-project.jpg">
	    			</span>
	    			</div>
	    		
    		@endif	
		</div><!-- caption -->
	@endif	{{-- Thumb verify --}}
	

@if( $project->shots()->count() == 1 )
	<div class="caption row" style="margin-top: 15px;">
	    		<div class="col-xs-6 col-md-6 col-right-pd">		    			    			
	    		<span>
	    			<img width="100%" class="img-rounded" src="{{ URL::asset('public/img')}}/no-img-project.jpg">
	    			</span>
	    			</div>
	    		
	    		<div class="col-xs-6 col-md-6 col-left-pd">		    			    			
	    		<span>
	    			<img width="100%" class="img-rounded" src="{{ URL::asset('public/img')}}/no-img-project.jpg">
	    			</span>
	    			</div>
       </div>
       @endif	{{-- Thumb verify 2 --}}
       
		
				  <div class="caption">
				     <h1 class="title-shots">
    					<a title="{{ e( $project->title ) }}" class="item-link" href="{{$urlProject}}">
    					 {{ e( $project->title ) }}
    					</a>
    				</h1>
    				
    				<p>{{ $project->shots()->count() }} {{ Lang::choice('misc.shots_plural',$project->shots()->count()) }} - <small class="timeAgo" style="line-height: normal;" data="{{ date('c',strtotime( $project->shots{0}->created_project )) }}" ></small> </p>
				
				      </div><!-- caption -->
				    </div><!-- thumbnail -->
		</div><!-- col-sm-6 -->
		
		@else
		<div class="col-sm-6 col-md-3 col-thumb">
				    <div class="thumbnail @if( isset( $projects_users ) ) padding-top-zero @endif">
				    	
				    	@if( !isset( $projects_users ) )
    			<div class="caption">
    				<p class="author p-author">
    				
    				@if( $project->user()->type_account == 2 ) 	
    					<a href="{{ URL::to('pro') }}" class="label label-primary btn-pro-xs pull-right">
    						{{ Lang::get('misc.pro') }}
    						</a>
    					@endif
    					
    					<a href="{{ URL::to('/') }}/{{ '@'.$project->user()->username }}" class="myicon-right">
    						<img src="{{ URL::asset('public/avatar').'/'.$project->user()->avatar }}" width="20" height="20" class="img-circle img-avatar-shots" />
    					</a>
    					
    					<a href="{{ URL::to('/') }}/{{ '@'.$project->user()->username }}" class="myicon-right" title="{{ $project->user()->name }}">
    					  {{ e( $project->user()->name ) }} 
    					  </a>
    					  
    					  
    					</p>
    			</div><!-- /caption -->
    			@endif
				      <a class="position-relative btn-block">
				      	<img src="{{ URL::asset('public/shots_img').'/' }}no_image.jpg" class="img-responsive btn-block">
				      </a>
				      
<div class="caption row" style="margin-top: 15px;">

			<div class="col-xs-6 col-md-6 col-right-pd">
				<span>
    			<img width="100%" class="img-rounded" src="{{ URL::asset('public/img')}}/no-img-project.jpg">
    			</span>
			</div>
    	
    	<div class="col-xs-6 col-md-6 col-left-pd">		    			    			
    		<span>
    			<img width="100%" class="img-rounded" src="{{ URL::asset('public/img')}}/no-img-project.jpg">
    			</span>
    			</div>
		</div><!-- caption -->
		
				      <div class="caption">
				     <h1 class="title-shots">
    					<a href="{{$urlProject}}" title="{{ e( $project->title ) }}" class="item-link" href="">
    					 {{ e( $project->title ) }}
    					</a>
    				</h1>
    				
    				<p>0 {{ Lang::choice('misc.shots_plural',0); }} </p>
				 </div><!-- caption -->
				    </div><!-- thumbnail -->
		</div><!-- col-sm-6 -->
		
		@endif {{-- data isset --}}
		@endforeach
</div><!-- row -->
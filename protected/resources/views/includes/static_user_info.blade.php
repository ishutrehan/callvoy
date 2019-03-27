@section('css_style')

<?php 

 if( Auth::check() ) {
 	$followActive = Followers::where( 'follower', Auth::user()->id )
			 ->where( 'following', $user->id )->where('status',1)->first(); 
			 
       if( $followActive ) {
       	  $textFollow   = Lang::get('users.following');
		  $icoFollow    = '-ok';
		  $activeFollow = 'follow_active activeFollow';
       } else {
       		$textFollow   = Lang::get('users.follow');
		    $icoFollow    = '-plus';
			$activeFollow = '';
       }
	   
	   $user_blocked = DB::table('block_user')
	   ->where('user_id',Auth::user()->id)
	   ->where('user_blocked',$user->id)
	   ->orWhere('user_id',$user->id)
	   ->where('user_blocked',Auth::user()->id)
	   ->first();
	   
	   $unblock = DB::table('block_user')
	   ->where('user_id',Auth::user()->id)
	   ->where('user_blocked',$user->id)
	   ->first();
 }//<<<<---- Auth
?>


<style type="text/css">
	.cover-user {
		padding-top: 26px !important;
	    background: url('{{ URL::asset("public/cover/$user->cover") }}') no-repeat center center #000;
	    background-size: cover;
	    position:relative;
	    max-height: 350px;
	    height: 340px;
	}
</style>


<meta property="og:image" content="{{ URL::asset('public/avatar') }}/{{$user->avatar}}" ></meta> 
@stop

@section('jumbotron') 
<div class="jumbotron cover-user jumbotron-cover">
      <div class="container wrap-jumbotron ui-container-cover">
      	<div class="btn-block text-center">
      		<a href="{{ URL::to('/') }}/{{ '@'.$user->username }}" class="position-relative">
				<img src="{{ URL::asset('public/avatar').'/'.$user->avatar }}" width="128" height="128" class="img-circle border-avatar-profile" />
			</a>
      	</div>
      	<h3 class="w_text">{{ e( $user->name ) }} 
      		
      		@if($user->type_account == 2 ) 
      		<span class="label pro-badge">{{ Lang::get('misc.pro') }}</span>
      		@endif
      		
      		@if($user->type_account == 3 ) 
      		<span class="label team-badge">{{ Lang::get('misc.team') }}</span>
      		@endif
      		
      		</h3>
      
      @if( Auth::check() )	
      
      {{-- Profile User Session --}}
      
      	@if( $user->id != Auth::user()->id )	
     
     @if( !isset( $user_blocked ) )
      <!-- DIV User -->	
      <div class="btn-block text-center">
      			<button type="button" class="btn btn-default btn-follow-lg btn-sm add-button followBtn {{ $activeFollow }}" data-id="{{ $user->id }}" data-follow="{{ Lang::get('users.follow') }}" data-following="{{ Lang::get('users.following') }}">
      				<i class="glyphicon glyphicon{{ $icoFollow }} myicon-right"></i> {{ $textFollow }}
      				</button>
      	
		@if( $user->hire == 1 )		
      		<div class="pos-relative block-div">
      			<button title="{{ Lang::get('users.hire_me') }}" type="button" class="btn btn-default btn-more-lg btn-sm msgModal" data-error="{{ Lang::get('misc.error') }}" data-send="{{ Lang::get('users.send_message') }}" data-wait="{{ Lang::get('misc.send_wait') }}" data-success="{{ Lang::get('misc.send_success') }}" data-id="{{ $user->id }}" data-toggle="modal" data-target="#myModal">
      				<i class="fa fa-envelope"></i> 
      			</button>
      		</div>
			
			<!-- ***** Modal ****** -->
					<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
					  <div class="modal-dialog">
					    <div class="modal-content">
					      <div class="modal-header">
					        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
					        <h5 class="modal-title text-left" id="myModalLabel">{{ Lang::get('users.send_message') }} | <strong>{{ $user->name }}</strong></h5>
					      </div>
					      <div class="modal-body">
					      	
					     <form action="{{ URL::to('/') }}/ajax/sendmessage" method="POST" accept-charset="UTF-8" id="send_msg_profile" enctype="multipart/form-data">
					      	<input type="hidden" name="id_user" id="id_user" value="{{ $user->id }}" />
					       <textarea class="form-control textarea-text" name="message" id="message"></textarea>
					        <input type="file" name="fileUpload" id="uploadFile" style="visibility: hidden;">
					    
					      </div><!-- modal-body -->
					      
				<div class="modal-footer">
			        	
			       <div class="alert alert-danger btn-sm text-left col-thumb" role="alert" id="errors" style="display:none;"></div>
			        	
			        	<button type="submit" id="button_message" class="btn btn-info btn-sm btn-sort pull-left">{{ Lang::get('users.send_message') }}</button>
			        	<button type="button" class="btn btn-default btn-sm btn-border pull-left" style="background: #333 !important; color: #FFF !important;" data-dismiss="modal">{{ Lang::get('users.cancel') }}</button>
			        
			        @if( $settings->allow_attachments_messages == 'on' )	
			        	<button data-toggle="tooltip" data-placement="top" title="" type="button" class="btn btn-default btn-sm btn-border pull-left" id="upload_file">
			        		<i class="glyphicon glyphicon-paperclip myicon-right"></i> {{ Lang::get('misc.attach_file_or_image') }}
			        		</button>
			        	@endif
			        		
					  <div class="btn-default btn-xs btn-border btn-block pull-left text-left display-none imageContainer">
					     <div id="previewImage"></div>
					     	<small class="myicon-right file-name"></small> <i class="icon-cancel-circle delete-attach-image pull-right" title="{{ Lang::get('misc.delete') }}"></i>
					     </div>
					     
					     <div class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-attach-file pull-right" title="{{ Lang::get('misc.delete') }}"></i>
					     </div>
					     
					      </div><!-- modal-footer" -->
					     </form>
					     
					    
					    </div>
					  </div>
				
					</div> <!-- ***** Modal ****** -->
		@endif		
		
      			<div class="pos-relative block-div">
      				<button type="button" class="btn btn-default btn-more-lg btn-sm dropdown-toggle" data-toggle="dropdown">
      				<i class="fa fa-sort-desc"></i> 
      				</button>
      				
      <?php 
      // Check if report user
	 $report = MembersReported::where('user_id', '=', Auth::user()->id)->where('id_reported', '=', $user->id)->first();
       ?>
      				<!-- dropdown-menu -->
      				<ul class="dropdown-menu dropdown-settings menu-settings" id="setting-actions">
      					<li><a href="javascript:void(0);" class="add_remove_lists" id="user{{ $user->id }}" data-user-id="{{ $user->id }}">{{ Lang::get('users.add_remove_lists') }}</a></li>
	               	@if( !$report )	
	               		<li><a href="javascript:void(0);" class="report_user_spam" data-id="{{ $user->id }}">{{ Lang::get('users.report_user') }}</a></li>
		               @endif
		                <li><a href="javascript:void(0);" class="block_user_id" data-id="{{ $user->id }}">{{ Lang::get('users.block_user') }}</a></li>
	           		 </ul>
      			</div>
      </div><!-- End DIV User -->
      
      @endif
      {{-- User Block --}}
      
      @if( isset( $unblock ) )
      	<div class="btn-block text-center">
      			<a href="javascript:void(0);" data-id="{{$user->id}}" class="btn btn-default btn-follow-lg btn-sm" id="unblock">
      				<i class="glyphicon glyphicon-eye-close myicon-right"></i> {{ Lang::get('users.unblock') }}
      				</a>
      		</div>
      @endif
      		
      		@else
      		<div class="btn-block text-center">
      			<a href="{{ URL::to('account') }}" class="btn btn-default btn-follow-lg btn-sm">
      				<i class="icon-pencil2 myicon-right"></i> {{ Lang::get('users.edit_profile') }}
      				</a>
      		</div>
      		
      		@endif
      		{{-- Profile User Session --}}
      	
      	@else
      	
      	<div class="btn-block text-center">
      		
      		<button type="button" class="btn btn-default btn-follow-lg btn-sm add-button"><i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.follow') }}</button>
      		
      		@if( $user->hire == 1 )	
      		<div class="pos-relative block-div">
      			<button title="{{ Lang::get('users.hire_me') }}" type="button" class="btn btn-default btn-more-lg btn-sm">
      				<i class="fa fa-envelope"></i> 
      			</button>
      		</div>
      		@endif
      		
      			<div class="pos-relative block-div">
      				<button type="button" class="btn btn-default btn-more-lg btn-sm dropdown-toggle" data-toggle="dropdown">
      				<i class="fa fa-sort-desc"></i> 
      				</button>
      				
      				<!-- dropdown-menu -->
      				<ul class="dropdown-menu dropdown-settings menu-settings" id="setting-actions">
	               		<li><a href="javascript:void(0);">{{ Lang::get('users.report_user') }}</a></li>
		                <li><a href="javascript:void(0);">{{ Lang::get('users.block_user') }}</a></li>
	           		  </ul>
      			</div>
      		</div>
      			
      	@endif
      	{{-- End Auth Check --}}
      		
      		@if($user->location != '' ) 
      		 <p class="subtitle-user">
      		 	
      		 	<i class="glyphicon glyphicon-map-marker"></i> {{ e( $user->location ) }}
      		 	</p> 
      		 @endif
      </div>
    </div> 
    
    @include('includes.menu-user')

@stop
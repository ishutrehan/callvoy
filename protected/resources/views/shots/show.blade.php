@extends('layouts.master')

@section('title'){{ e( $data->title ) }} - @stop

@section('description_custom')@if( $data->description != '' ){{ Helper::removeLineBreak( e( $data->description ) ).' - ' }}@endif @stop

@section('css_style')
{{ HTML::style('public/css/bootstrap-tokenfield.css') }}
{{ HTML::style('public/css/jquery-ui-1.8.2.custom.css') }}
{{ HTML::style('public/css/colorbox.css') }}

<meta  property="og:image" content="{{ URL::asset('public/shots_img') }}/{{$data->image}}" ></meta>

@if( $data->description != '' )
<meta property="og:description" content="{{ Helper::removeLineBreak( e( $data->description ) ) }}"/>
@endif

@stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
if( Auth::check() ) {
	 	
	//<---- Verify Membership Status Of User Post Shot if belongs to a team ----->
	$dateNow   = date('Y-m-d G:i:s');
		
   	$likeUser = Like::where( 'shots_id', $data->id )->where('user_id',Auth::user()->id)->where('status',1)->first(); 
         
    $followActive = Followers::where( 'follower', Auth::user()->id )
			 ->where( 'following', $data->user()->id )->where('status',1)->first(); 
			 
       if( $followActive ) {
       	  $textFollow   = Lang::get('users.following');
		  $icoFollow    = '-ok';
		  $activeFollow = 'btnFollowActive';
       } else {
       		$textFollow   = Lang::get('users.follow');
		    $icoFollow    = '-plus';
			$activeFollow = '';
       }
       
	   // Check if report shot
	 $report = ShotsReported::where('user_id', '=', Auth::user()->id)->where('shots_id', '=', $data->id)->first();
		
		$unblock = DB::table('block_user')
	   ->where('user_id',Auth::user()->id)
	   ->where('user_blocked',$data->user()->id)
	   ->orWhere('user_id',$data->user()->id)
	   ->where('user_blocked',Auth::user()->id)
	   ->first();
	   
	   if( Auth::user()->team_id != 0 ){
	 	$teamName = User::find(Auth::user()->team_id);
	 }
	   
	   // TEAMS Membership Check
	   if( Auth::user()->type_account == 3 ) {
		   	
		$teamMembershipStatus = DB::table('paypal_payments_teams')
		->where('user_id', Auth::user()->id)
		->where('expire','>',$dateNow)
		->where('payment_status', '=', 'Completed')
		->orderBy('id', 'desc')
		->first(); 
	   }// Auth Check 3
	   
	   $membershipTeam = DB::table('paypal_payments_teams')
		->where('user_id',Auth::user()->team_id)
		->where('expire','>',$dateNow)
		->where('payment_status', '=', 'Completed')
		->orderBy('id', 'desc')
		->first(); 
	 
	   
	   
	 }//<------- End IF Auth Check
	 
	 $page = Input::get('page');
	 //Active Like
	 $activeLike = '';
	 
	 if( $data->team_id != 0 ){
	 	
	 	$teamData = User::find($data->team_id);
		
	}//<-- IF $data->team_id
	
	$data->description = strip_tags( $data->description );
	
	$noShowAdOfGoogle = true;
	
	$shot_url = URL::to('/') . '/' . Request::path();
	
	$usersLikes = Like::where( 'shots_id', $data->id )->where('status',1)->orderBy('id','desc')->paginate( 5 );
		
 ?>
     
<div class="col-md-8">
  
  	   	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<h1 class="title-shot">{{ e( $data->title ) }}
		  		
		  		@if( Auth::check() && Auth::user()->role == 'admin' && Auth::user()->id != $data->user()->id  )
		  			<a href="javascript:void(0);" data-id="{{ $data->id }}" class="btn btn-xs btn-danger delete-shot">
   						<i class="glyphicon glyphicon-remove"></i> {{ Lang::get('misc.delete') }}
   					</a>
		  		@endif
		  		</h1>
		  	<span class="btn-block subtitle-shot col-thumb">
		  		{{ Lang::get('misc.by') }} <a href="{{ URL::to('/') }}/{{ '@'.$data->user()->username }}">{{ e( $data->user()->name ) }}</a>
		  		
		  @if( $data->team_id != 0 )
		  		{{ Lang::get('misc.for') }} <a href="{{ URL::to('/') }}/{{ '@'.$teamData->username }}">{{ e( $teamData->name ) }}</a>
		  @endif
		  
		  <span class="ic-sep">|</span> <a href="javascript:void(0);" id="see_full" class="closeTrue">{{ '@'.Lang::get('misc.full_size') }}</a>
		  
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  

<div class="position-relative">
	
	<?php
		
		if( $data->extension == 'gif' ) {
			$classGIF = 'is_gif';
		} else {
			$classGIF = null;
		}
		
	 ?>

		  <div class="btn-block shot-view {{$classGIF}}">
		  	<img src="{{ URL::asset('public/shots_img/large') }}/{{$data->large_image}}" class="imgColor closeTrue viewFull img-responsive" />
		  </div>
            
    </div><!-- div alone -->
          
		  <div class="panel-body">
		  	@if( $data->description != '' )  
			  <h4>
			  	<strong>{{ Lang::get('misc.description') }}</strong>
			  </h4>
			  <p class="desc-shot mentions-links none-overflow">{{ Helper::checkText( $data->description ) }}</p>
			  @endif
			  
			  @if( $data->attachment != '' )  
			  
			  <?php
			  // Switch	psd,ai,ps,zip,eps,cdr
			switch($data->extension_file) {
				case "gif":
				case "pjpeg":
				case "jpeg":
				case "jpg":
				case "JPG":
				case "png":
				case "x-png":
					$file_attachment = "<a data-url='' target='_blank' class='image-thumb preview-attach galery cboxElement' href='".URL::to('public/attachment_shots')."/".$data->attachment."' style='background: url(".URL::asset('public/attachment_shots')."/".$data->attachment.") center center; background-size: cover;'></a>"; 
					break;
			    case "psd":
					$file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <i class="ico-files ico-file-psd myicon-right"></i> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					break;
				case "zip":
					$file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <i class="ico-files ico-file-zip myicon-right"></i> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					break;
				case "ai":
					$file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <i class="ico-files ico-file-ai myicon-right"></i> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					break;
				case "eps":
					$file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <i class="ico-files ico-file-eps myicon-right"></i> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					break;
				case "cdr":
					$file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <i class="ico-files ico-file-cdr myicon-right"></i> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					break;
				case "pdf":
					$file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <i class="ico-files ico-file-pdf myicon-right"></i> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					break;
				case "doc":
				case "docx":
					$file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <i class="ico-files ico-file-doc myicon-right"></i> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					break;
					default:
						 $file_attachment = '<a target="_blank" href="'.URL::to('public/attachment_shots').'/'.$data->attachment.'" class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer" style="display: block; margin-bottom: 25px;"> <small class="myicon-right file-name-file">'.$data->attachment.'</small> </a>';
					
		  	}
			   ?>
			  	<h4>
			  	<strong><i class="icon-attachment myicon-right"></i> {{ Lang::get('misc.attachment') }}</strong>
			  </h4>
			  
			  {{ $file_attachment }}
			  
			  
			  @endif
	
	@if( $usersLikes->getTotal() != 0 )		  
	 
	  <h5>
	  	<strong><i class="fa fa-heart myicon-right active-red"></i> {{ Lang::get('misc.people_like_this') }}</strong>
	  </h5>
		
		<ul class="list-inline list-options details-shot padding-zero">
			@foreach( $usersLikes as $userLike )
			<li>
				<a href="{{ URL::to('@') . $userLike->user()->username }}" class="showTooltip" title="{{$userLike->user()->name}}">
					<img src="{{URL::asset('public/avatar').'/'.$userLike->user()->avatar}}" class="img-circle" width="25" />
					</a>
				</li>
				@endforeach
			
				
			@if( $usersLikes->getTotal() > 5 )	 
				<li>
					<a href="{{$shot_url}}/likes">
						{{ Lang::get('misc.view_all') }} <i class="fa fa-long-arrow-right"></i>
					</a>
				</li>
			@endif
				
     </ul> 
     
     @endif
			  
		</div><!-- Panel Body -->
   </div><!-- Panel Default -->
  
 @if( Auth::check() && Auth::user()->id != $data->user()->id && !$report )  
   <div class="btn-block">
   	<a href="javascript:void(0);" data-id="{{ $data->id }}" class="report-shot pull-right text-decoration-none">
   		<small><i class="icon-flag myicon-right"></i> {{ Lang::get('misc.report_shot') }}</small>
   		</a>
   </div>
   @endif
   
   
@if( Auth::check() && Auth::user()->id == $data->user()->id )  
 
 @if( Auth::user()->type_account == 3 && isset( $teamMembershipStatus ) 
 	|| Auth::user()->team_id != 0 && isset( $membershipTeam ) && Auth::user()->type_account == 1
 	|| Auth::user()->type_account == 2 
 	|| Auth::user()->team_free == 1
	|| $settings->team_free == 'on'
 	)  
   <!-- ***** Modal ****** -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title text-left" id="myModalLabel">{{ Lang::get('users.edit') }} | <strong>{{ e( $data->title ) }}</strong></h4>
	      </div>
	      
	      <div class="modal-body">
	      	
	     <form class="form-horizontal edit-shot" id="form-edit-shot" method="post" role="form" action="{{ URL::to('/') }}/shot/edit" enctype="multipart/form-data">
			 
			 <input type="hidden" name="id_shot" value="{{ $data->id }}" /> 
			 
			 <div class="form-group @if( $errors->first('title') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.title') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e( $data->title ) }}" name="title" class="form-control input-sm" id="title" placeholder="{{ Lang::get('misc.title') }}">
	     		
	     		@if( $errors->first("title") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("title")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->

			  
			 <div class="form-group margin-bottom-zero @if( $errors->first('tags') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.tags') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $data->tags }}" name="tags" class="form-control input-sm" id="tags" placeholder="{{ Lang::get('misc.placeholder_tags') }}">
			   		<small class="help-block">{{ Lang::get('misc.maximum_tags') }} </small>
			   
			   @if( $errors->first("tags") )    	
			<div class="alert alert-danger btn-sm errors-account" style="margin-bottom: 15px;" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("tags")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			   </div>
			  </div><!-- **** form-group **** -->
			  
			  @if( Auth::user()->team_id != 0 ) 
			  
			  <?php 
			  
			  if( $data->team_id != 0 ) {
			  	$checkInTeam = 'checked="checked"';
			  } else {
				$checkInTeam = '';
			  }
			  ?>
			   <!-- **** form-group **** -->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.for_team') }}</label>
			    <div class="col-sm-10">
					<label class="checkbox-inline">
					 <input class="no-show" id="forTeam" type="checkbox" {{$checkInTeam}} value="1" name="for_team" />
					   <span class="input-sm">{{ e($teamName->name) }} </span>
					</label>
			    </div>
			  </div><!-- **** form-group **** -->
			  @endif
			  
			  <?php 
			  
			  if( $data->url_purchased != '' ) {
			  	$valueForSale = 1;
			  	$checkIn = 'checked="checked"';
			  	$styleInput = '';
			  } else {
			  	$valueForSale = 0;
			  	$styleInput = 'style="display: none;"';
				$checkIn = '';
			  }
			  ?>
			  
			  <!-- **** form-group **** -->
			  <div class="form-group @if(Auth::user()->type_account == 1 ) display-none @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.for_sale') }}</label>
			    <div class="col-sm-10">
					<label class="checkbox-inline">
					 <input class="no-show" id="forSale" type="checkbox" {{$checkIn}} value="{{$valueForSale}}" name="for_sale" />
					   <span class="input-sm">{{ Lang::get('misc.yes') }} </span>
					</label>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group forsale @if( $errors->first('url_purchased') ) has-error @endif" {{$styleInput}}>
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.url_purchased') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{$data->url_purchased}}" name="url_purchased" class="form-control input-sm forSaleInput" placeholder="{{ Lang::get('misc.url_purchased') }}">
			  
			   @if( $errors->first("url_purchased") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("url_purchased")}}</strong>
	     	</div><!-- Error -->
	     	
	     	@endif
	     				    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  <div class="form-group forsale @if( $errors->first('price_item') ) has-error @endif" {{$styleInput}}>
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.price_item') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{$data->price_item}}" name="price_item" class="form-control input-sm forSaleInput" placeholder="{{ Lang::get('misc.price_item') }}">
			   
			   @if( $errors->first("price_item") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("price_item")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			   
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('project') ) has-error @endif  @if(Auth::user()->type_account == 1 ) display-none @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.add_project') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="project" name="project" class="input-sm btn-block">
					  <option value="0">- {{ Lang::get('misc.none') }} -</option>
					  <?php foreach( $data->user()->projects as $project ){ ?>
					  	<option id="project{{ $project->id }}" value="{{ $project->id }}">{{ e( $project->title ) }}</option>
					  <?php } ?>
					</select>

			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('description') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.description') }} ({{ Lang::get('misc.optional') }})</label>
			    <div class="col-sm-10">
			      <textarea name="description" rows="4" id="description" class="form-control input-sm textarea-textx">{{ e( $data->description ) }}</textarea>
	             
	             @if( $errors->first("description") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("description")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="alert alert-danger btn-sm display-none" id="errors_shot" role="alert"></div>
	
			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button style="padding: 9px 30px;" disabled="disabled" data-send="{{ Lang::get('users.save') }}" data-wait="{{ Lang::get('misc.send_wait') }}" type="submit" class="btn btn-info btn-sm btn-sort" id="updateShot">
			      	{{ Lang::get('users.save') }}
			      	</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
			    
	      </div><!-- modal-body -->
	      	     
	    
	    </div>
	  </div>
	</div> <!-- ***** Modal ****** -->
	 @endif
	 {{-- End membershipTeam --}}
					
   <div class="btn-block text-right">
   	@if( Auth::user()->type_account == 3 && isset( $teamMembershipStatus ) 
   		|| Auth::user()->team_id != 0 && isset( $membershipTeam ) && Auth::user()->type_account == 1 
   		|| Auth::user()->type_account == 2 
   		|| Auth::user()->team_free == 1
	    || $settings->team_free == 'on'
   		)
   	<a href="javascript:void(0);" class="btn btn-sm btn-success btn-padding" data-send="{{ Lang::get('users.save') }}" data-wait="{{ Lang::get('misc.send_wait') }}" data-id="{{ $data->id }}" data-toggle="modal" data-target="#myModal">
   		<i class="icon-pencil2 myicon-right myicon-right"></i> {{ Lang::get('users.edit') }}
   		</a>
   	@endif
   		
   	<a href="javascript:void(0);" data-id="{{ $data->id }}" class="btn btn-sm btn-danger delete-shot btn-padding">
   		<i class="glyphicon glyphicon-remove myicon-right"></i> {{ Lang::get('misc.delete') }}
   		</a>
   </div>
   @endif
   
   <h4 class="comments-title" id="commentsGrid">
   	<span class="fa fa-comment myicon-right color-strong"></span> 
   	{{ Lang::get('misc.comments') }} ({{ $comments_sql->getTotal() }})
   	</h4>
   <hr />

@if( Auth::check() )   

@if( !$page || $comments_sql->getCurrentPage() == 1 )

@if( Auth::user()->type_account == 2 
	|| Auth::user()->type_account == 3 && isset( $teamMembershipStatus )
	|| Auth::user()->team_id != 0 && isset( $membershipTeam ) && Auth::user()->type_account == 1 
   	|| Auth::user()->type_account == 2
   	|| Auth::user()->team_free == 1
	|| $settings->team_free == 'on'
	|| $settings->user_no_pro_comment_on == 'on'
	)
 @if( !$unblock )  
   @if ($errors->first("comment"))   	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{ $errors->first("comment") }}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
	<div class="media">
            <span class="pull-left">
                <img alt="Image" src="{{ URL::asset('public/avatar')}}/{{ Auth::user()->avatar }}" class="media-object img-circle" width="50">
            </span>
            
            <div class="media-body">
            <form action="{{ URL::to('comment/send') }}" method="post">
            	<div class="form-group text-form @if( $errors->first('comment') ) has-error @endif">
            		<input type="hidden" name="id_shot" value="{{ $data->id }}" />
            		<textarea name="comment" rows="4" id="comments" class="form-control input-sm textarea-comments mentions-textarea"></textarea>
            	</div>
                
                <div class="form-group ">
                	<button type="submit" class="btn btn-info btn-sm btn-sort" id="saveUpdate">{{ Lang::get('misc.post_comment') }}</button>
                </div>
                </form>
            </div><!-- media body -->
 </div><!-- media -->
 <hr />
       @endif {{-- Auth diferent author  --}}
    @endif {{-- Page null --}}
    @endif {{-- unblock --}}
 @endif {{-- Auth True --}}
 
 <div class="col-thumb">
 
   @foreach($comments_sql as $comment)
   <div class="media media-comments position-relative" id="comment{{ $comment->id }}">
    		<span class="pull-left">
    			<a href="{{ URL::to('@') }}{{ $comment->user()->username }}">
    			<img width="50" height="50" class="media-object img-circle" src="{{ URL::asset('public/avatar').'/'.$comment->user()->avatar }}">
    		</a>
    		</span>
    		<div class="media-body media-body-comments">
    			<h4 class="media-heading col-thumb">
    				<a class="text-decoration-none" href="{{ URL::to('@') }}{{ $comment->user()->username }}">{{ $comment->user()->name }}</a>
    					</h4>
    			<p class="comments-p mentions-links">
    				{{ Helper::checkText( $comment->reply ) }}
    			</p>
    			
    			
    			<div class="btn-block">
    				<small class="timeAgo small-comment" data="{{ date('c', strtotime( $comment->date )) }}"></small> 
@if( Auth::check()  )	

<?php 
    
    $comment_like = CommentsLikes::where('user_id', '=', Auth::user()->id)->where('comment_id', '=', $comment->id)->where('status',1)->first(); 
    
	 if( isset( $comment_like ) ) {
	 	$textLike = Lang::get('misc.unlike');
		$activeLike = 'active-red';
	 } else {
	 	$textLike = Lang::get('misc.like');
		$activeLike = '';
	 }
    ?>
    
@if( $comment->user_id != Auth::user()->id )
    				- 
    
			<small class="small-comment">
				<a href="javascript:void(0);" data-id="{{$comment->id}}" class="{{ $activeLike }} myicon-right text-decoration-none comment-like">
					{{ $textLike }}
					</a>
				</small>
    					
    				@else 
    				-
    				<small class="small-comment">
    					<a href="javascript:void(0);" data-id="{{$comment->id}}" class="myicon-right text-decoration-none comment-delete">{{ Lang::get('misc.delete') }}</a>
    					</small>
    					
    					@endif
    					
    				@endif {{--  Session active --}}
    				
    					<small data-id="{{$comment->id}}" data-toggle="collapse" href="#collapse{{$comment->id}}" aria-expanded="false" style="cursor:pointer;" class="pull-right comments-likes like-small @if( $comment->total_likes() == 0 ) display-none @endif">
    						<span class="{{ $activeLike }} fa fa-heart myicon-right"></span> 
    						<span class="count">{{ $comment->total_likes() }}</span>
    					</small>
    			</div>
    		</div>
   </div><!-- media -->

	   <ul class="list-inline collapse text-right" id="collapse{{$comment->id}}">
	   	<li>{{ Lang::get('misc.loading') }}</li>
	   </ul>

   
   
   @endforeach
   
   
   
  </div><!-- media box -->
   
   @if( $comments_sql->getLastPage() > 1 && Input::get('page') <= $comments_sql->getLastPage() )
   <hr />
   <div class="btn-group paginator-style">
	        		<?php echo $comments_sql->links(); // $comments_sql->fragment('commentsGrid')->links() ?> 
	        	</div>
	        	@endif
	        	   
   @if( $comments_sql->count() == 0 )
   
    		<h3 class="margin-top-none text-center no-result row-margin-20">
	    	- {{ Lang::get('misc.no_comments_yet') }} -
	    	</h3>
	    	@endif
	 
	 
	 @if( Auth::guest() )
	 <hr />
	 	<div class="alert alert-warning text-center" role="alert">
	 		{{ Lang::get('auth.logged_in_comments') }} <a href="{{URL::to('login')}}">{{ Lang::get('auth.sign_in') }}</a>
	 	
	 	@if( $settings->registration_active == '1' )
	 		- <a href="{{URL::to('join')}}">{{ Lang::get('auth.sign_up') }}</a>
	 	@endif
	 		
	 	</div>
	 @endif
	 	
	
</div><!-- /col-md-8 -->
@stop

@section('sidebar')
<div class="col-md-4">
	  <button type="button" class="btn btn-default btn-block btn-border btn-lg show-toogle" data-toggle="collapse" data-target=".responsive-side" style="margin-bottom: 20px;">
		   <i class="fa fa-bars"></i>
		</button>
	
	<div class="responsive-side collapse">
		
		<!-- panel-default -->
	<div class="panel panel-default">
			<div class="panel-body padding-top padding-top-zero padding-right-zero padding-left-zero">
				    					
				<div style="background: url({{ URL::asset('public/cover').'/'.$data->user()->cover }}) no-repeat center center #0088E2; background-size: cover;" class="cover-wall"></div>
				
			<div class="media media-visible pd-right">
				  <a href="{{ URL::to('@') }}{{ $data->user()->username }}" class="btn-block photo-card-live myprofile">
				    <img class="border-image-profile img-circle photo-card" alt="Image" src="{{ URL::asset('public/avatar').'/'.$data->user()->avatar }}" width="80" height="80">
				  </a>
				  <div class="media-body">
				    <h4 class="user-name-profile-card btn-block  text-center">
				    	<a class="myprofile" href="{{ URL::to('@') }}{{ $data->user()->username }}">
				    		<span class="none-overflow">{{ $data->user()->name }}</span>
				    		</a>
					</h4>
					
					@if( Auth::check() )
    			 		
    			 		
    			 @if( $data->user()->id != Auth::user()->id )
    			    @if( !$unblock )
    			 		<div class="btn-block text-center col-thumb">
	    			 		<button type="button" class="btn btn-default btn-xs add-button btn-follow btnFollow myicon-right {{ $activeFollow }}" data-id="{{ $data->user()->id }}" data-follow="{{ Lang::get('users.follow') }}" data-following="{{ Lang::get('users.following') }}">
	    			 			<i class="glyphicon glyphicon{{ $icoFollow }} myicon-right"></i> {{ $textFollow }}
	    			 		</button>
    			 		</div>
    			 		@endif
    			 		{{-- unblock  --}}
    			 		
    			 		@endif
    			 		{{-- Avoid self follow  --}}
    			 		
    			 		@else
    			 	<div class="btn-block text-center col-thumb">	
    			 		<button type="button" class="btn btn-default btn-xs add-button btn-follow myicon-right shotTooltip" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('misc.sign_in_or_sign_up') }}">
    			 			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.follow') }}
    			 		</button>
    			 	</div>
    			 		@endif
				  </div>
				</div>
		
	    		<ul class="nav list-inline nav-pills btn-block nav-user-profile-wall">
	    			<li><a href="{{ URL::to('@') }}{{ $data->user()->username }}">{{ Lang::get('misc.shots') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalShots( $data->user()->id ) ) }}</small></a></li>
	    			<li><a href="{{ URL::to('@') }}{{ $data->user()->username }}/followers">{{ Lang::get('users.followers') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalFollowers( $data->user()->id ) ) }}</small></a></li>
	    			<li><a href="{{ URL::to('@') }}{{ $data->user()->username }}/following">{{ Lang::get('users.following') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalFollowing( $data->user()->id ) ) }}</small></a></li>
	    			</ul>
	    		
			</div><!-- Panel Body -->
	</div><!-- panel-default -->

@if( isset( $next ) || isset( $previous ) )	
	<div class="li-title btn-block col-thumb">
		{{ Lang::get('misc.more_by') }}
		<a href="{{ URL::to('@') }}{{ $data->user()->username }}"><strong>{{ e( $data->user()->name ) }}</strong></a>
		</div>
	
	<div class="row" style="margin-bottom: 20px;">

@if( isset( $previous ))

<?php
	
	if( Str::slug( $previous->title ) == '' ) {
	
		$slugUrl  = '';
	} else {
		$slugUrl  = '-'.Str::slug( $previous->title );
	}
	
	$slugUrlPrev = URL::to('/').'/shots/'.$previous->id.$slugUrl;
	 ?>
	<!-- Previous -->	
	<div class="col-xs-6 col-md-6">
    	<a class="image-thumb" id="prev" title="{{ e($previous->title) }}" href="{{$slugUrlPrev}}">
    			<img width="100%"class="media-object img-rounded img-thumbnail" src="{{ URL::asset('public/shots_img') }}/{{$previous->image}}">
    			</a>
    </div><!-- col-xs-4 col-md-4 -->
    
    @else
    <div class="col-xs-6 col-md-6">
	    <a class="image-thumb">
	    	<img width="100%"class="media-object img-rounded img-thumbnail" src="{{ URL::asset('public/img') }}/start.jpg" alt="Image">
	    </a>
    </div><!-- col-xs-4 col-md-4 -->
    @endif
    
    
    @if( isset( $next ))
    <?php
	
	if( Str::slug( $next->title ) == '' ) {
	
		$slugUrl  = '';
	} else {
		$slugUrl  = '-'.Str::slug( $next->title );
	}
	
	$slugUrlNext = URL::to('/').'/shots/'.$next->id.$slugUrl;
	 ?>
	<!-- Next -->	
	<div class="col-xs-6 col-md-6">
    	<a class="image-thumb" title="{{ e($next->title) }}"  id="next" href="{{$slugUrlNext}}">
    			<img width="100%"class="media-object img-rounded img-thumbnail" src="{{ URL::asset('public/shots_img') }}/{{$next->image}}">
    			</a>
    </div><!-- col-xs-4 col-md-4 -->
    
    @else
    <div class="col-xs-6 col-md-6">
	    <a class="image-thumb">
	    	<img width="100%"class="media-object img-rounded img-thumbnail" src="{{ URL::asset('public/img') }}/end.jpg" alt="Image">
	    </a>
     </div><!-- col-xs-4 col-md-4 -->
    @endif
    
	</div><!-- row  -->
	
	@endif
	
	@if(Auth::check())
		@if( !$unblock )
		<span data-id="{{ $data->id }}" data-like="{{ Lang::get('misc.like') }}" data-like-active="{{ Lang::get('misc.unlike') }}" title="@if(!empty($likeUser)) {{ Lang::get('misc.unlike') }} @else {{ Lang::get('misc.like') }} @endif" class="btn btn-lg btn-block btn-like-shots @if(!empty($likeUser)) btn-success @else btn-danger @endif btn-like likeButton @if(!empty($likeUser)) active @endif">
			<i class="glyphicon glyphicon-heart myicon-right"></i> {{ Lang::get('misc.like') }}
		</span>
		@endif
		{{-- Unblock--}}
		
		@else
			
		<a href="{{ URL::to('login') }}" class="btn btn-lg btn-block btn-like-shots btn-danger btn-like shotTooltip" data-toggle="tooltip" data-placement="top" title="{{ Lang::get('misc.sign_in_or_sign_up') }}">
			<i class="glyphicon glyphicon-heart myicon-right"></i> {{ Lang::get('misc.like') }}
		</a>
		@endif
			
		<ol class="details-shot">
			<li class="li-title"><strong>{{ Lang::get('misc.details') }}</strong></li>
			<li><span class="icon-clock myicon-right color-strong"></span> {{ Lang::get('misc.published') }} <span class="timeAgo pull-right color-strong" data="{{ date('c', strtotime( $data->date ) ) }}"></span></li>
			<li><span class="fa fa-eye myicon-right color-strong"></span> {{ Lang::get('misc.views') }} <strong class="pull-right color-strong">{{ number_format( Shots::totalVisits( $data->id ) ) }}</strong></li>
			<li><span class="fa fa-heart myicon-right color-strong"></span> {{ Lang::get('misc.likes') }} <strong class="pull-right color-strong" id="countLikes">{{ number_format( Shots::totalLikes( $data->id ) ) }}</strong></li>
			<li><span class="fa fa-comment myicon-right color-strong"></span> {{ Lang::get('misc.comments') }} <strong class="pull-right color-strong">{{ number_format( Shots::totalComments( $data->id  ) ) }}</strong></li>
		    <li><span class="fa fa-share-alt myicon-right color-strong"></span> {{ Lang::get('misc.share') }} 
		    	
		<ul class="list-inline pull-right">
			<li><a title="Facebook" href="https://www.facebook.com/sharer/sharer.php?u={{ $shot_url }}" target="_blank"><img src="{{URL::asset('public/img/social')}}/facebook.png" width="20" class="img-circle" /></a></li>
			<li><a title="Twitter" href="https://twitter.com/intent/tweet?url={{ $shot_url }}&text={{ e( $data->title ) }}" data-url="{{ $shot_url }}" target="_blank"><img width="20" src="{{URL::asset('public/img/social')}}/twitter.png" class="img-circle" /></a></li>
			<li><a title="Google Plus" href="https://plus.google.com/share?url={{ $shot_url }}" target="_blank"><img width="20" class="img-circle" src="{{URL::asset('public/img/social')}}/googleplus.png" /></a></li>
			<li style="padding-right: 0;"><a title="Pinterest" href="//www.pinterest.com/pin/create/button/?url={{ $shot_url }}&media={{ URL::to('/') . '/public/shots_img/' . $data->image }}&description={{ e( $data->title ) }}" target="_blank"><img width="20" src="{{URL::asset('public/img/social')}}/pinterest.png" class="img-circle" /></a></li>
     </ul> 
     	
     </li>
		   
		   @if( $data->url_purchased != '' )
		   		<?php 
		   		$url =  parse_url($data->url_purchased);
				$host = $url['host'];
		   		?>
		   	<li><span class="icon-cart myicon-right color-strong"></span> <a href="{{e($data->url_purchased)}}" target="_blank">{{ Lang::get('misc.buy_at') }} {{e(Str::title($host))}} ${{$data->price_item}}</a>  </li>
		   @endif
		   
		   @if( isset( $data->project->id ) ) 
		   <?php
			$urlProject = URL::to('/').'/@'.$data->user()->username.'/projects/'.$data->project->id.'-'.$data->project->slug;
	 		?>
		    <li><span class="icon-folder-open myicon-right color-strong"></span> {{ Lang::get('misc.in') }} <a href="{{$urlProject}}">{{ $data->project->title }}</a> </li>
		    @endif
		    
		    <li class="li-title"><strong>{{ Lang::get('misc.color_palette') }}</strong></li>
		    <div class="colorContainer btn-block pull-left col-thumb"></div>
		
		    
		    <?php 
	           $tags = explode(',', $data->tags); 
	           $count_tags = count( $tags );
	           ?>
			<li class="li-title"><strong>{{ Str::upper( Lang::get('misc.tags') ) }}</strong></li>
			<li>
				@for( $i = 0; $i < $count_tags; ++$i )
				<a href="{{URL::to('tags') }}/{{$tags[$i]}}" class="btn btn-danger tags font-default btn-xs">
					{{ $tags[$i] }}
				</a>
				@endfor
			</li>
		</ol>
		
	<hr class="margin-top-zero" />
		
		
		@include('includes.ads')
	</div><!-- responsive collapse --> 
	
	@include('includes.ads_google')
	
</div><!-- /End col md-4 -->

@stop

@section('javascript')
   
   {{ HTML::script('public/js/jquery.colorbox.js') }}
   
  {{ HTML::script('public/js/jquery.form.js') }}
  {{ HTML::script('public/js/colorthief.js') }}
  
  @if( Auth::check() && !$page && Auth::user()->type_account != 1 
  		|| Auth::check() && $comments_sql->getCurrentPage() == 1 && Auth::user()->type_account != 1 
  		|| Auth::check() && !$page && $settings->user_no_pro_comment_on == 'on'
  		|| Auth::check() && $comments_sql->getCurrentPage() == 1 && $settings->user_no_pro_comment_on == 'on'
  		)
  	{{ HTML::script('public/js/mentions.js') }}
  	
  	@endif
  
  @if( Auth::check() && Auth::user()->id == $data->user()->id )	
    {{ HTML::script('public/js/bootstrap-tokenfield.js') }}
  @endif
  
<script type="text/javascript">


$(".galery").colorbox({
   		height: '100%',
   		imgError : 'Error occurred'
   	});
   	
//<<<---------- Comments Likes 
$(document).on('click','.comments-likes',function() {
   	
   	
   	element  = $(this);
   	var id   = element.attr("data-id");
   	var info = 'comment_id=' + id;

   		
   		element.removeClass('comments-likes');
   		
   		$.ajax({
		   type: "POST",
		   url: "{{ URL::to('/') }}/ajax/commentslikes",
		   data: info,
		   success: function( data ) {
		   		
                $( '#collapse'+ id ).html(data);
                $('.showTooltip').tooltip();

				
				if( data == '' ){
					$( '#collapse'+ id ).html("{{Lang::get('misc.error')}}");
				}

				}//<-- $data 
			});
   });
   
@if( Auth::check() )

  $('#project{{$data->id_project}}').attr({'selected':'selected'});
  
  @if (Session::has('success_update'))
	 $('.popout').html("{{ Session::get('success_update')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
   @if( Session::has('error_update') )
   	$('#myModal').modal('show');
   @endif
   
  @if( Auth::user()->id == $data->user()->id || Auth::user()->role == 'admin' )
  
  //<<<---------- Delete Account      
  $(".delete-shot").click(function() {
   	
   	element = $(this);
   	var id  = element.attr("data-id");
   	var info= 'shot_id=' + id;
   	
   	bootbox.confirm("{{ Lang::get('misc.delete_shot') }}", function(r) {
   		if( r == true ) {
   			
   		$.ajax({
		   type: "POST",
		   url: "{{ URL::to('/') }}/delete/shot",
		   dataType: 'json',
		   data: info,
		   success: function( data ) {
		   	
				if(data.success == true ){
					var location = "{{ URL::to('/') }}";
   					window.location.href = location;
				} else {
					bootbox.alert(data.error);
					window.location.reload();
				}

				}//<-- RESULT 
			});
	
	 		}//END IF R TRUE 
	  }); //Jconfirm  
   });
   
   @endif

@if( Auth::user()->id == $data->user()->id )   

  	$('#tags').on('tokenfield:createdtoken', function (e) {
	    var re = /^[a-z0-9][\w\s\ÀÈÌÒÙàèìòùÁÉÍÓÚÝáéíóúýÂÊÎÔÛâêîôûÃÑÕãñõÄËÏÖÜäëïöüçÇßØøÅåÆæÞþÐð\-]+$/i
	    var valid = re.test(e.attrs.value)
	    if (!valid) {
	      $(e.relatedTarget).addClass('invalid').remove()
	    }
	  }).tokenfield({
		limit: 10,
		beautify: false
		});

    
		//** Changes Form **//
function changesForm () {
var button = $('#updateShot');
$('form.edit-shot input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form.edit-shot  input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});
    
    $('form.edit-shot  input, select, textarea, checked').each(function () {
        if( trim( $(this).val() ) != $(this).data('val') || $(this).is(':checked') != $(this).data('checked') ){
            changed = true;
            ifChange = true;
            button.css({'opacity':'1','cursor':'pointer'})
        }
       
    });
    button.prop('disabled', !changed);
});
}//<<<--- Function
changesForm();

  @endif

// Comments Delete
$('.comment-delete').click(function(){
			element = $(this);
			element.removeClass('comment-delete');
			$.post("{{URL::to('/')}}/comment/delete", { comment_id: $(this).data('id') }, function(data){
				if(data.success == true ){
					url = '{{URL::to("/")."/".Request::path()}}';
					window.location.reload();
				} else {
					bootbox.alert(data.error);
					window.location.reload();
				}
				
				if( data.session_null ) {
					window.location.reload();
				}
			},'json');
		});
		
		// Likes Comments
		$('.comment-like').click(function(){
			element  = $(this);
			
			element.html('<i class="fa fa-spinner fa-spin"></i>');
			
			$.post("{{URL::to('/')}}/comment/like", { comment_id: $(this).data('id') }, function(data){
				if(data.success == true ){
					if( data.type == 'like' ) {
						element.html('{{ Lang::get("misc.unlike") }}').addClass('active-red')
						element.parents('.btn-block').find('.count').html(data.count).fadeIn();
						element.parents('.btn-block').find('.like-small').fadeIn();
						element.parents('.btn-block').find('.fa').addClass('active-red');
						element.blur();
						
					} else if( data.type == 'unlike' ) {
						element.html('{{ Lang::get("misc.like") }}').removeClass('active-red');
					
					if( data.count == 0 ) {
						element.parents('.btn-block').find('.count').html(data.count).fadeOut();
						element.parents('.btn-block').find('.like-small').fadeOut();
					} else {
						element.parents('.btn-block').find('.count').html(data.count).fadeIn();
						element.parents('.btn-block').find('.like-small').fadeIn();
					}	
						
						
						element.parents('.btn-block').find('.fa').removeClass('active-red');
						element.blur();
					}
				} else {
					bootbox.alert(data.error);
					window.location.reload();
				}
				
				if( data.session_null ) {
					window.location.reload();
				}
			},'json');
		});
		
		
// Report Shot
$('.report-shot').click(function(){
			element = $(this);
			element.removeClass('comment-delete');
			$.post("{{URL::to('/')}}/report/shot", { shot_id: $(this).data('id') }, function(data){
				if(data.success == true ){
					element.remove();
					$('.popout').html("{{Lang::get('misc.reported_success')}}").fadeIn(500).delay(5000).fadeOut();
				} else {
					bootbox.alert(data.error);
					window.location.reload();
				}
				
				if( data.session_null ) {
					window.location.reload();
				}
			},'json');
		});
@endif

	@if( $page )
	
		scrollElement('#commentsGrid');
	
	@endif

$(document).ready(function(){
$('body').keyup(function (event) {
    var next        = $('#next').attr('href');
    var prev        = $('#prev').attr('href');
    
         if ( event.target.tagName.toLowerCase() !== 'input' 
		        && event.target.tagName.toLowerCase() !== 'textarea' 
		        && event.keyCode == 37 && prev != undefined) {
            // Anterior
            location.href=prev;
            
        } else if ( event.target.tagName.toLowerCase() !== 'input' 
		        && event.target.tagName.toLowerCase() !== 'textarea' 
		        && event.keyCode == 39 && next != undefined) {
            // Siguiente
            location.href=next;
        }
        
        @if( Auth::check() )
        if ( event.target.tagName.toLowerCase() !== 'input' 
		        && event.target.tagName.toLowerCase() !== 'textarea' 
		        && event.keyCode == 76 ) {
            $(".likeButton").trigger('click');
        }
        @endif
        
    });// keyup
    
    $('.comments-p').readmore({
		maxHeight: 120,
		moreLink: '<a href="javascript:void(0);">'+ReadMore+'</a>',
		lessLink: '<a href="javascript:void(0);">'+ReadLess+'</a>',
		sectionCSS: 'display: block; width: 100%;',
	});
});//document
		
$("#comments").charCount({ allowed: {{ $settings->comment_length }}, warning: 10, css: 'counterBio' });

$('input,select').not('#btnItems').keypress(function(event) { return event.keyCode != 13; });
  
$("#description").charCount({ allowed: {{ $settings->shot_length_description }}, warning: 10, css: 'counterBio' });

/******************************************************/
/****************** FULL IMAGE **********************/
/****************************************************/


/*$(window).on("navigate", function (event, data) {
  var direction = data.state.direction;
  if (direction == 'back') {
    $('.wrap-full-image').hide();
	$('body').removeClass('noscroll');
  }
  
  /*if (direction == 'forward') {
    // Test
  }
});*/


 $(document).on('click','.closeTrue, .viewFull', function(){
	
	
	$('#see_full,.viewFull').removeClass('closeTrue');
	
	//**** page , Title, New Url
	//window.history.pushState({}, null, '#fullsize');
	//parent.location.hash = 'hello';
	
	<?php
		if( $data->original_image != '' ) {
			$image_full = 'original/'.$data->original_image;
		} else {
			$image_full = 'large/'.$data->large_image;
		}
	 ?>
	
	$('.container-image-full').html('<img id="fullImage" src="{{ URL::asset("public/shots_img") }}/{{$image_full}}" class="img-responsive imageFull">');
	$('#titleShotFull').html('{{e($data->title)}}');
   //
 });//======== FUNCTION 
 
$(document).on('click','#see_full, .viewFull', function(){
	
	  	
	 window.location.hash = '#fullsize';
  
	//window.history.pushState({}, null, '#fullsize');
	$('.wrap-full-image').show();
	$('body').addClass('noscroll');
	
 });//======== FUNCTION 
 
 $(window).on('popstate', function() {
	     $('.wrap-full-image').hide();
		 $('body').removeClass('noscroll');

	    });

 $(document).on('click','#closeFull', function(){
	$('.wrap-full-image').hide();
	$('body').removeClass('noscroll');
	window.history.back();
	
 });//======== FUNCTION 
 
 // *************** SEE FULL REAL PIXELS
 $(document).on('click','#fullImage', function(){
		
	var _this = $(this);
	
	if( _this.hasClass('img-responsive') ) {
		
		_this.removeClass('img-responsive');
		_this.css({'cursor':'zoom-out'});
		
		$('.details-full-image').hide();
	} else {
		
		_this.css({'cursor':'zoom-in'})
		_this.addClass('img-responsive');
		
		$('.details-full-image').show();
	}
    });//======== FUNCTION 
 
 //******** CLOSE WITH ESC
$('body').keydown(function (event) {
 if( event.which  == 27 && $(this).hasClass('noscroll')  ) {
 	$('.wrap-full-image').hide();
	$('body').removeClass('noscroll');
	$('#see_full').blur();
	window.history.back();
	//window.history.pushState({}, null, '');
 }
});//======== FUNCTION 
 
 

</script>
@stop

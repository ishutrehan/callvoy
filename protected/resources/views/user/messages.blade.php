@extends('layouts.app')

@section('title'){{ Lang::get('users.messages') }} - @stop

@section('css_style')
<link href="{{ asset('protected/public/css_theme/jquery.fs.picker.min.css') }}" rel="stylesheet">
@stop

@section('content') 
     
     <?php 
 	use App\Models\AdminSettings;
	use App\Models\Advertising;
	use App\Models\Messages;
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
	 $noShowAdOfGoogle = true;
	 
     ?>
 <div class="container">
	<div class="row">
<div class="col-xl-4">
 
 <button type="button" class="btn btn-default btn-block btn-border btn-lg show-toogle" data-toggle="collapse" data-target=".responsive-side" style="margin-bottom: 20px;">
		   <i class="fa fa-bars"></i>
		</button>
		
 <div class="responsive-side collapse">    	
    @include('includes.user-card')
	
	@include('includes.sidebar_edit_user')

	@include('includes.ads')
	
	</div>
	
	@include('includes.ads_google')
			
 </div>
@stop

@section('sidebar')
<div class="col-xl-8">
	
	<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('users.messages') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="content">
		
		  	@foreach( $message as $msg )
		  	 
		  	 <?php
		  	 
		  	 if( $msg->last()->from_user_id == Auth::user()->id && $msg->last()->to()->id != Auth::user()->id ){
		  	 	$avatar   = $msg->last()->to()->avatar;
				$name     = $msg->last()->to()->name;
				$userID   = $msg->last()->to()->id;
				$username = $msg->last()->to()->username;
				$iconUndo = '<i class="icon-undo2 myicon-right"></i> ';
				
		  	 } else if( $msg->last()->from_user_id == Auth::user()->id   ){
		  	 	$avatar   = $msg->last()->to()->avatar;
				$name     = $msg->last()->to()->name;
				$userID   = $msg->last()->to()->id;
				$username = $msg->last()->to()->username;
				$iconUndo = null;
		  	 } else{
		  	 	$avatar   = $msg->last()->from()->avatar;
				$name     = $msg->last()->from()->name;
				$userID   = $msg->last()->from()->id;
				$username = $msg->last()->from()->username;
				$iconUndo = null;
		  	 }
		  	 
		  	 if( $msg->last()->attach_file != '' ) {
		  	 	$attach = '<i class="icon-attachment myicon-right"></i>';
		  	 } else {
		  	 	$attach = null;
		  	 }
			 /* New - Readed */
				 if( $msg->last()->status == 'new' && $msg->last()->from()->id != Auth::user()->id )  {
				 	$styleStatus = ' unread-msg'; 
				 } else {
					 $styleStatus = null; 
				 }
				 
				 
				 // Messages	
	$messages_count = Messages::where('from_user_id',$userID)->where('to_user_id',Auth::user()->id)->where('status','new')->count();
	
		  	  ?>
				<li class="li-group-msg hoverList" >
	      		<a href="{{URL::to('/')}}/messages/{{ $userID.'-'.$username }}" class="see_msg list-group-item border-group{{$styleStatus}}">
	                         <div class="media">
	                            <div class="pull-left">
	                               <img src="{{ URL::asset('public/avatar')}}/{{$avatar}}" alt="Image" class="border-image-profile-2 media-object img-circle image-dropdown">
	                            </div>
	                            <div class="media-body clearfix">
	                               <div class="pull-right small">
	                               	<span class="timestamp timeAgo myicon-right" data="{{ date('c',strtotime( $msg->last()->created_at ) ) }}"></span>
	                               	</div>
	                               
	                               <div class="media-nowrap">
	                               	<strong class="media-heading">{{ e( $name ) }}</strong>
	                               </div>
	                               
	                               <p class="text-col">
	                               	@if( $messages_count > 1 )
	                               	<span class="label label-default">{{$messages_count}}</span>
	                               	@endif
	                                  <small>{{$iconUndo.$attach}}{{ e( $msg->last()->message ) }}</small>
	                               </p>
	                            </div>
	                         </div>
	                      </a>
	          	       </li>
	          	      @endforeach() 
	   </div><!-- /End content -->
	          	
	          	@if( $message->count() == 0 )
          <div class="panel-body">
          	<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('misc.no_messages') }} -
	    	</h3>
          </div>
          
          @endif
   </div><!-- Panel Default -->

          
          @if( $message->getLastPage() > 1 && Input::get('page') <= $message->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $message->links(); ?> 
	        	</div>
	        	
    		@endif
    		
</div><!-- /End col md -->

@stop
</div>
</div>
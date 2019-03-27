@extends('layouts.master')

@section('title'){{ Lang::get('users.messages') }} - @stop

@section('css_style')
{{ HTML::style('public/css/colorbox.css') }}
@stop

@section('content') 
     
     <?php 
     use App\Models\AdminSettings;
	use App\Models\Advertising;
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
	 //User 
	 $user_conversation = User::select('name')->where('id',$id_user_conversation)->first();
	 
	 $noShowAdOfGoogle = true;

     ?>
     
<div class="col-md-4">
 
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
<div class="col-md-8">
	
	<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		<a href="{{URL::to('messages')}}" title="{{ Lang::get('users.back_to_messages') }}" class="myicon-right">{{ Lang::get('users.messages') }}</a> <i class="fa fa-angle-right myicon-right"></i>  {{e($user_conversation->name)}}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
	@if( $message->count() != 0 )	  
		  <div class="content" id="contentDIV" style="max-height: 400px;   overflow: auto;" data="{{$id_user_conversation}}">
		
		  	@foreach( $message as $msg )
		  	 
		  	 <?php
		  	 
		  	 if( $msg->from_user_id  == Auth::user()->id ){
		  	 	$avatar   = $msg->to()->avatar;
				$name     = $msg->to()->name;
				$userID   = $msg->to()->id;
				$username = $msg->to()->username;
				
		  	 } else if ( $msg->to_user_id  == Auth::user()->id ) {
		  	 	$avatar   = $msg->from()->avatar;
				$name     = $msg->from()->name;
				$userID   = $msg->from()->id;
				$username = $msg->from()->username;
		  	 }
			 
			 $attach = 'public/attachment_messages'."/".$msg->attach_file;
			 
			 $ext = pathinfo( $attach );
			 
			 $formats_image = array(
					"gif", 
					"jpeg", 
					"jpg",
					"JPG",
					"png",
					"x-png",
					"gif"
				);
				
				if( $msg->attach_file != '' && in_array( $ext['extension'], $formats_image ) ) {
					$attach_file = "<a data-url='' target='_blank' class='galery' href='".URL::to('public/attachment_messages')."/".$msg->attach_file."'><img width='300' class='img-responsive' src='".URL::asset('public/attachment_messages')."/".$msg->attach_file."' /></a>";
				} else if( $msg->attach_file != '' && !in_array( $msg->extension_file, $formats_image ) ) {
					$attach_file = '<div class="btn-default btn-xs btn-border text-left list-media-mv"> <a  target="_blank" href="'.URL::to('public/attachment_messages').'/'.$msg->attach_file.'" class="btn-block "> <i class="glyphicon glyphicon-paperclip myicon-right"></i> '.$msg->attach_file.' <span class="icon-download pull-right"></span> </a> </div>';
				} else {
					$attach_file = null;
				}
			 
		  	  ?>
				<li data="{{$msg->id}}" class="media li-group list-group-item border-group list-slimscroll chatlist margin-zero">
	                         <div class="media">
	                            <div class="pull-left">
	                            <a href="{{URL::to('@').$msg->from()->username}}">
                               		<img width="40" src="{{ URL::asset('public/avatar')}}/{{$msg->from()->avatar}}" alt="Image" class="border-image-profile-2 media-object img-circle">
	                              </a>
	                            </div>
	                            <div class="media-body clearfix">
	                            	
	                      <div class="pull-right small">
							<a href="javascript:void(0);" class="link-post showTooltip removeMsg" data-delete="{{ Lang::get('misc.delete_message') }}" data="{{$msg->id}}" title="{{Lang::get('misc.delete')}}" data-toggle="tooltip" data-placement="left">
								<i class="glyphicon glyphicon-trash"></i>
								</a>
							</div>
							
	                               <div class="pull-right small">
	                               	<span class="timestamp timeAgo myicon-right" data="{{ date('c',strtotime( $msg->created_at ) ) }}"></span>
	                               	</div>
	                               
	                               <div class="media-nowrap">
	                               	<a href="{{URL::to('@').$msg->from()->username}}" class="text-decoration-none">
	                               		<strong class="media-heading">{{ e( $msg->from()->name ) }}</strong>
	                               	</a>
	                               </div>
	                               <p class="text-col paragraph none-overflow">{{ e( $msg->message ) }}</p>
	                               
			                     {{$attach_file}}
						          
	                            </div><!-- media-body -->
	                         </div>
	          	       </li>
	          	      @endforeach() 
	   </div><!-- /End content -->
	   
	   <div class="panel-footer"> 
	   	
	   	 <div id="errors" class="alert alert-danger btn-sm text-left col-thumb display-none" role="alert"></div>
	   	 
	   	<div class="media"> 
	   		<span href="#" class="pull-left"> 
	   			<img src="{{URL::asset('public/avatar')}}/{{Auth::user()->avatar}}" class="media-object img-circle" width="40"> 
	   			</span> 
	   			<div class="media-body"> 
	   				<form action="{{URL::to('message/send')}}" method="post" accept-charset="UTF-8" id="form_reply_post" enctype="multipart/form-data"> 
	   				@csrf
	   					<input type="hidden" name="id_user" id="id_user" value="{{$id_user_conversation}}">
	   					<input type="file" name="fileUpload" id="uploadFile" style="visibility: hidden;"> 
	   					
	   					<div class="position-relative">
	   						<textarea class="form-control" rows="3" id="message" name="message"></textarea>
	   					</div>
	   					
					<p class="help-block"> 
						<button type="submit" id="button-reply-msg" data-send="{{ Lang::get('auth.send') }}" data-wait="{{ Lang::get('misc.send_wait') }}" class="btn btn-info btn-sm btn-sort col-thumb">
							{{Lang::get('auth.send')}}
						</button> 
					
					@if( $settings->allow_attachments_messages == 'on' )	
						<button data-toggle="tooltip" data-placement="top" title="" type="button" class="btn btn-inverse btn-sm col-thumb" id="upload_file">
			        		<i class="glyphicon glyphicon-paperclip myicon-right"></i> {{ Lang::get('misc.attach_file_or_image') }}
			        		</button>
			        		@endif
						</p> 
						
						 <div class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-attach-file pull-right" title="{{ Lang::get('misc.delete') }}"></i>
					     </div>
				</form> 
			</div><!-- media-body --> 
		</div><!-- media --> 
	</div>
	 @endif
	
	          	
	          	@if( $message->count() == 0 )
          <div class="panel-body">
          	<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('misc.no_messages') }} -
	    	</h3>
          </div>
          
          @endif
   </div><!-- Panel Default -->

         
</div><!-- /End col md -->

@stop

@section('javascript')

{{ HTML::script('public/js/jquery.colorbox.js') }}
{{ HTML::script('public/js/chat.js') }}
  
<script type="text/javascript">

//scrollElement( 'li.media:last' );

/*var myDiv = $("#contentDIV").get(0);
myDiv.scrollTop = myDiv.scrollHeight;*/

$(function () {
    var container = $('#contentDIV');
    var height = container[0].scrollHeight;
    container.animate({scrollTop:height},500);
});

/*$(function () {
  $('#contentDIV').scrollTop(1E10);
});*/


$("#message").focus();

$(".galery").colorbox({
   		height: '100%',
   		imgError : '{{Lang::get("misc.error")}}'
   	});
   	
$("#message").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

$('.paragraph').readmore({
		maxHeight: 120,
		moreLink: '<a href="javascript:void(0);">'+ReadMore+'</a>',
		lessLink: '<a href="javascript:void(0);">'+ReadLess+'</a>',
		sectionCSS: 'display: block; width: 100%;',
	});
	
//================== START FILE - FILE READER
$("#uploadFile").change(function(){
	$('.fileContainer').fadeOut(100);
	var loaded = false;
	if(window.File && window.FileReader && window.FileList && window.Blob){
		if($(this).val()){ //check empty input filed
			if($(this)[0].files.length === 0){return}
			
			var oFile = $(this)[0].files[0];
			var fsize = $(this)[0].files[0].size; //get file size
			var ftype = $(this)[0].files[0].type; // get file type
			
			var allowed_file_size = {{ $settings->file_size_allowed }};	
						
			if(fsize>allowed_file_size){
				$('.popout').html("{{ Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ) }}").fadeIn(500).delay(4000).fadeOut();
				return false;
			}
			
			$('.fileContainer').fadeIn();
			$('.file-name-file').html(oFile.name);
			
		}
	} else{
		$('.popout').html('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.').fadeIn(500).delay(4000).fadeOut();
		return false;
	}
});
//================== END FILE - FILE READER ==============>


</script>
@stop

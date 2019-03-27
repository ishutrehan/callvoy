@extends('layouts.app')

@section('title'){{ Lang::get('misc.avatar_cover') }} - @stop

@section('css_style')
<link href="{{ URL::asset('protected/public/css_theme/jquery.fs.picker.min.css') }}" rel="stylesheet">
@stop

@section('content') 
     
     <?php 
     use App\Models\AdminSettings;
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();

     ?>
<div class="container">
<div class="row">
<div class="col-xl-8">
	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('misc.avatar_cover') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		<div class="panel-body">

  <!-- *********** form AVATAR ************* -->  
  <form class="form-horizontal" action="<?php echo Url::to('/'); ?>/ajax/avatar" method="POST" id="formAvatar" accept-charset="UTF-8" enctype="multipart/form-data">
  @csrf
			  
			  <div class="form-group">
			  <?php /*  <label for="exampleInputFile" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.avatar').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ) }} - 128x128px</label>*/ ?>
			    <label for="exampleInputFile" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.avatar')  }} - 128x128px</label>
			    
	<div class="col-sm-10">
			    	
	  <div class="labelAvatar" style="background-image: url({{ URL::asset('protected/public/avatar/').'/'.$user->avatar }} )">
		
	    	<?php if( $user->avatar != 'default.jpg' ): ?>
				<div class="deletePhoto" data="{{ $user->avatar }}" style="background: none; cursor: pointer;" title="{{ Lang::get('misc.delete') }}" id="loader_gif_1"></div>
			<?php endif; ?>
			</div>
			
			<button type="button" class="btn btn-default btn-border btn-sm" id="avatar_file" style="margin-top: 10px;">
	    		<i class="icon-camera"></i> {{ Lang::get('misc.select_image') }}
	    		</button>
	    		 <input type="file" name="photo" id="uploadAvatar" accept="image/*" style="visibility: hidden;">
			      	 	
			    </div>
		  	</div><!-- **** form-group **** -->
		
			</form><!-- *********** form AVATAR ************* -->
			
			 <!-- *********** form COVER ************* -->  
  <form class="form-horizontal" action="<?php echo Url::to('/'); ?>/ajax/cover" method="POST" id="formCover" accept-charset="UTF-8" enctype="multipart/form-data">
  @csrf
			  
			  <div class="form-group">
			 <?php /*   <label for="exampleInputFile" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.cover').' '.Helper::formatBytes( $settings->file_size_allowed, 0 )  }} - 1500px</label>*/ ?>
			    <label for="exampleInputFile" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.cover') }} - 1500px</label>
			    
			    <div class="col-sm-10">
	  
	  <?php 
			if( $user->cover != 'cover.jpg' ):
				$cover = 'background-image: url( '.URL::asset("protected/public/cover/$user->cover").' );';
			else:
				$cover = 'background-image: url( '.URL::asset("protected/public/cover/cover.jpg").' );';;
				endif;
				?>
				
	  <div class="label_cover" id="coverUser" style="-webkit-border-radius: 5px; border-radius: 5px; width: 100%; height: 200px; background-color: #D1D1D1; background-position: center center; background-size: cover; <?php echo $cover; ?>">
	  	
	    	<?php if( $user->cover != 'cover.jpg' ): ?>
			<div class="deleteCover" data="<?php echo $user->cover; ?>" style="background: none; cursor: pointer; position: absolute; top: 0; right: 0;" title="{{ Lang::get('misc.delete') }}" id="loader_gif_2"></div>
			<?php endif; ?>

			 </div>
			
			<button type="button" class="btn btn-default btn-border btn-sm" id="cover_file" style="margin-top: 10px;">
	    		<i class="icon-camera"></i> {{ Lang::get('misc.select_image') }}
	    		</button>
	    		 <input type="file" name="photo" id="uploadCover" accept="image/*" style="visibility: hidden;">
			      	 	
			    </div>
		  	</div><!-- **** form-group **** -->
		
			</form><!-- *********** form COVER ************* -->

		</div><!-- Panel Body -->
</div><!-- Panel Default -->
			
 </div>
@stop

@section('sidebar')
<div class="col-xl-4">
	
	@include('includes.user-card')
	
	@include('includes.sidebar_edit_user')

	@include('includes.ads')
          
          
          
</div><!-- /End col md-4 -->

@stop
</div>
</div>
@section('javascript')
    
<script type="text/javascript">

//<<<<<<<=================== * UPLOAD AVATAR  * ===============>>>>>>>//
    
 $(document).on('change', '#uploadAvatar', function(){
    $('#loader_gif_1').remove();  		 
	$('<div id="loader_gif_1"></div>').appendTo('.labelAvatar');
   
   (function(){
	 $("#formAvatar").ajaxForm({
	 dataType : 'json',	
	 success:  function(e){
	 if( e ){
        if( e.error == 1 ){
		$('#loader_gif_1').remove();
		$('.popout').html( ''+ e.output +'' ).fadeIn().delay(3500).fadeOut(200);
		if( e.reload == 1 ){
			window.location.reload();
		  } 
		}//<-- Error
		$('#uploadAvatar').val('');
		if( e.error == 0 ) {
				$('.popout').fadeOut();
				$('#loader_gif_1').css({'cursor':'pointer',background:'url("'+URL_BASE+'/public/avatar/'+ e.photo +'") center center no-repeat #FFF'}).addClass('deletePhoto').attr( 'data', e.photo ).attr( 'title','{{ Lang::get('misc.delete') }}' );
				$('.photo-card-live').html('<img width="80" height="80" src="'+URL_BASE+'/public/avatar/'+ e.photo +'" alt="Image" class="border-image-profile img-circle photo-card" />');
				$('.userAvatar').html('<img class="img-circle" width="20" height="20" src="'+URL_BASE+'/public/avatar/'+ e.photo +'" />');
			}
		}//<-- e
			else {
				bootbox.alert('{{Lang::get("misc.error")}}');
				$('#uploadAvatar').val('');
			}
			
			if( e.session_null ) {
				window.location.reload();
			}
		}//<----- SUCCESS
		}).submit();
		})(); //<--- FUNCTION %
	});//<<<<<<<--- * LIVE * --->>>>>>>>>>>
	
		//===== DELETE PHOTO AVATAR
		$(document).on('click',".deletePhoto",function(){ 
			//=== PARAM
			var element     = $(this);
			var id          = element.attr("data");
			var info        = 'token_id=' + id + '&type=avatar';
			$.ajax({
			   type: "GET",
			   url: URL_BASE+"/ajax/trash",
			   data: info,
			   dataType: 'json',
			   success: function( output ){
			   	
			   if( output.status == 1 ) {
			   	$('.photo-card-live').html('<img width="80" height="80" src="'+URL_BASE+'/public/avatar/default.jpg" alt="Image" class="border-image-profile img-circle photo-card" />');
			   	$('.userAvatar').html('<img width="20" height="20" class="img-circle" src="'+URL_BASE+'/public/avatar/default.jpg" />');
			   	   $('.labelAvatar').css({ background: 'url("'+URL_BASE+'/public/avatar/default.jpg") center center no-repeat #FFF' });
			    	$('#photoId').val('');
			   	     element.fadeOut('fast',function(){
		   		      element.remove();
		   		});//<-- FUNCTION
					   }
					 }//<-- OUTPUT
				});//<-- AJAX
			});//<<<<<<<--- * CLICK * --->>>>>>>>>>>
	
	//<<<<<<<=================== * UPLOAD COVER  * ===============>>>>>>>//
	$(document).on('change', '#uploadCover', function(){
    $('#loader_gif_2').remove();  		 
	$('<div id="loader_gif_2"></div>').appendTo('.label_cover');
	(function(){
	 $("#formCover").ajaxForm({
	 dataType : 'json',	
	 success:  function(e){
	 if( e ){
        if( e.error == 1 ){
		$('#loader_gif_2').remove();
		$('.popout').html( ''+ e.output +'' ).fadeIn().delay(3500).fadeOut(200);
		if( e.reload == 1 ){
			window.location.reload();
		  } 
		}//<-- Error
		$('#uploadCover').val('');
		if( e.error == 0 ) {	
				$('.popout').fadeOut();
				$('#preview_cover').html('<img src="'+URL_BASE+'/public/cover/'+ e.photo +'" />');
				$('#loader_gif_2').css({'cursor':'pointer',background:'url("'+URL_BASE+'/public/cover/'+ e.photo +'") center center no-repeat #FFF','background-size': 'cover'}).addClass('deleteCover').attr( 'data', e.photo ).attr( 'title','{{ Lang::get('misc.delete') }}' );
				 $('.cover-wall').css({ background: 'url("'+URL_BASE+'/public/cover/'+ e.photo +'") center center #0038de','background-size': 'cover' });		
			}
		}//<-- e
			else {
				bootbox.alert('{{Lang::get("misc.error")}}');
				$('#uploadCover').val('');
			}
			
			if( e.session_null ) {
				window.location.reload();
			}
		}//<----- SUCCESS
		}).submit();
		})(); //<--- FUNCTION %
	});//<<<<<<<--- * LIVE * --->>>>>>>>>>>
	
	//===== DELETE COVER
		$(document).on('click',".deleteCover",function(){ 
			//=== PARAM
			var element     = $(this);
			var id          = element.attr("data");
			var info        = 'token_id=' + id + '&type=cover';
			$.ajax({
			   type: "GET",
			   url: ""+URL_BASE+"/ajax/trash",
			   data: info,
			   dataType: 'json',
			   success: function( output ){
			   if( output.status == 1 ) {
			   	   $('#coverUser').css({ background: 'url("'+URL_BASE+'/public/cover/cover.jpg") center center no-repeat #FFF','background-size': 'cover' });
			   	   $('.cover-wall').css({ background: 'url("'+URL_BASE+'/public/cover/cover.jpg") center center #0038de','background-size': 'cover' });
			    	$('#photoId').val('');
			   	     element.fadeOut('fast',function(){
		   		      element.remove();
		   		});//<-- FUNCTION
		   		
		   		 $('#preview_cover > img').slideUp('fast',function(){
		   		      $('#preview_cover > img').remove();
		   		});//<-- FUNCTION
		   		
				}//<--- TRUE
					 }//<-- OUTPUT
				});//<-- AJAX
			});//<<<<<<<--- * CLICK * --->>>>>>>>>>>

</script>
@stop

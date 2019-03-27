@extends('layouts.master')

@section('title'){{ Lang::get('misc.upload_shot') }} - @stop

@section('css_style')
{{ HTML::style('public/css/bootstrap-tokenfield.css') }}
{{ HTML::style('public/css/jquery-ui-1.8.2.custom.css') }}
@stop

@section('content') 
     
     <?php 

	use App\Models\AdminSettings;
	use App\Models\Shots;
	use Illuminate\Support\Facades\Input;
	use Illuminate\Support\Facades\Auth;
	use View;
	use DB;
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
	 // Projects
	 $projects = Projects::where( 'user_id', Auth::user()->id )->get();
	 
	 if( Auth::user()->team_id != 0 ){
	 	$teamName = User::find(Auth::user()->team_id);
	 }
	 
	 // TEAMS Membership Check
	   if( Auth::user()->type_account == 3 ) {
	   	
		$_dateNow   = date('Y-m-d G:i:s');
		   	
		$teamMembershipStatus = DB::table('paypal_payments_teams')
		->where('user_id', Auth::user()->id)
		->where('expire','>',$_dateNow)
		->where('payment_status', '=', 'Completed')
		->orderBy('id', 'desc')
		->first(); 
	   }
	   
	   // Members Team
	   if( Auth::user()->team_id != 0 ){
	 	$teamData = User::find(Auth::user()->team_id);
		 
		//<---- Verify Membership Status Of User Post Shot if belongs to a team ----->
		$dateNow   = date('Y-m-d G:i:s');

		$membershipTeam = DB::table('paypal_payments_teams')
		->where('user_id',Auth::user()->team_id)
		->where('expire','>',$dateNow)
		->where('payment_status', '=', 'Completed')
		->orderBy('id', 'desc')
		->first(); 
	 }//<-- IF $data->team_id
	 
	 $noShowAdOfGoogle = true;
	 
     ?>
     
<div class="col-md-4">
  
  <button type="button" class="btn btn-default btn-block btn-border btn-lg show-toogle" data-toggle="collapse" data-target=".responsive-side" style="margin-bottom: 20px;">
		   <i class="fa fa-bars"></i>
		</button>
	
	<div class="responsive-side collapse">

		@include('includes.user-card')
		
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
		  		{{ Lang::get('misc.upload_shot') }}
		  	</span><!-- **btn-block ** -->
		  	
		  	
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body display-none show-in">
		  	 <div class="alert alert-success btn-sm" id="success_shot" role="alert"></div>
		  </div>

@if( Auth::user()->type_account == 2 
	|| Auth::user()->type_account == 3 && isset( $teamMembershipStatus )
	|| Auth::user()->team_id != 0 && isset( $membershipTeam ) && Auth::user()->type_account == 1 
   	|| Auth::user()->type_account == 2
   	|| Auth::user()->team_free == 1
	|| $settings->team_free == 'on'
	)		 

<div class="position-relative remove-this">
	<small class="edit_pos" title="edit position"></small>

		  <div class="btn-block shot-preview" id="shotPreview" title="{{ Lang::get('misc.click_select_image') }}">
            	<i class="icon-image2" style="font-size: 80px; vertical-align: middle;"></i>
            </div>
            
   </div><!-- div alone -->
            
		  <div class="panel-body remove-this">
				  
			<form class="form-horizontal" id="form-upload-shot" method="post" role="form" action="{{ URL::to('/') }}/ajax/upload" enctype="multipart/form-data">
			@csrf
			 			 
			 <input type="file" name="fileShot" id="fileShot" accept="image/*" style="visibility: hidden;">
			 
			 
			 <!-- **** form-group **** -->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.image') }}</label>
			    <div class="col-sm-10">
			    	
					<label class="radio-inline">
					 <input class="no-show image_option" type="radio" checked="checked" value="0" name="image" />
					   <span class="input-sm">{{ Lang::get('misc.crop_image') }}</span>
					</label>
					
					<label class="radio-inline">
					 <input class="no-show image_option" type="radio" value="1" name="image" />
					   <span class="input-sm">{{ Lang::get('misc.full_size') }}</span>
					</label>
					
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			 <div class="form-group @if( $errors->first('title') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.title') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="" name="title" class="form-control input-sm" id="title" placeholder="{{ Lang::get('misc.title') }}">
			
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
			      <input type="text" value="" name="tags" class="form-control input-sm" id="tags" placeholder="{{ Lang::get('misc.placeholder_tags') }}">
			   		<small class="help-block">{{ Lang::get('misc.maximum_tags') }} </small>
			   </div>
			  </div><!-- **** form-group **** -->
			 
			 @if( Auth::user()->team_id != 0 && isset( $membershipTeam ) ) 
			 
			   <!-- **** form-group **** -->
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.for_team') }}</label>
			    <div class="col-sm-10">
					<label class="checkbox-inline">
					 <input class="no-show" id="forTeam" type="checkbox" value="1" name="for_team" />
					   <span class="input-sm">{{ $teamName->name }} </span>
					</label>
			    </div>
			  </div><!-- **** form-group **** -->
			  @endif
			  
			  <!-- **** form-group **** -->
			  <div class="form-group @if(Auth::user()->type_account == 1 ) display-none @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.for_sale') }}</label>
			    <div class="col-sm-10">
					<label class="checkbox-inline">
					 <input class="no-show" id="forSale" type="checkbox" value="0" name="for_sale" />
					   <span class="input-sm">{{ Lang::get('misc.yes') }} </span>
					</label>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group forsale @if( $errors->first('url_purchased') ) has-error @endif" style="display: none;">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.url_purchased') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="" name="url_purchased" class="form-control input-sm forSaleInput" placeholder="{{ Lang::get('misc.url_purchased') }}">
			      </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  <div class="form-group forsale @if( $errors->first('price_item') ) has-error @endif" style="display: none;">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.price_item') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="" name="price_item" class="form-control input-sm forSaleInput" placeholder="{{ Lang::get('misc.price_item') }}">
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('project') ) has-error @endif @if(Auth::user()->type_account == 1 ) display-none @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.add_project') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="project" name="project" class="input-sm btn-block">
					  <option value="0">- {{ Lang::get('misc.none') }} -</option>
					  <?php foreach( $projects as $project ){ ?>
					  	<option value="{{ $project->id }}">{{ $project->title }}</option>
					  <?php } ?>
					</select>
			    
			    @if( $errors->first("project") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("project")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			@if( $settings->allow_attachments == 'on' )  
			  <div class="form-group margin-bottom-zero @if( $errors->first('tags') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.attach_file') }} ({{ Lang::get('misc.optional') }})</label>
			    <div class="col-sm-10">
			    	<button type="button" class="btn btn-default btn-border btn-sm btn-block" id="attachFile">
			      	{{ Lang::get('misc.select_file') }}
			      	</button>
			      	
			      	<input type="file" name="attach_file" id="attach_file" style="visibility: hidden;">
			      	
			      	<div class="btn-default btn-xs btn-border btn-block pull-left text-left display-none fileContainer">
					     	<i class="glyphicon glyphicon-paperclip myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-attach-file-2 pull-right" title="{{ Lang::get('misc.delete') }}"></i>
					     </div>
					     
			      	<small class="help-block none-overflow">{{ Lang::get('misc.attach_file_support').' '.$settings->file_support_attach }}</small>
			    </div>
			  </div><!-- **** form-group **** -->
			  @endif

			  
			  <div class="form-group @if( $errors->first('description') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.description') }} ({{ Lang::get('misc.optional') }})</label>
			    <div class="col-sm-10">
			      <textarea name="description" id="descriptionUpload" rows="4" class="form-control input-sm textarea-textx mentions-textarea"></textarea>
			    
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
			      <button style="padding: 9px 30px;" data-send="{{ Lang::get('auth.send') }}" data-wait="{{ Lang::get('misc.send_wait') }}" type="submit" class="btn btn-info btn-sm btn-sort" id="send">
			      	{{ Lang::get('auth.send') }}
			      	</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->
		
		@else
		
		@if( Auth::user()->type_account == 3 )
			<div class="panel-body">
		  	 <div class="alert alert-warning btn-sm margin-zero" role="alert">
		  	 	{{Lang::get('misc.membership_expired_teams')}} <a href="{{URL::to('renew/membership')}}" class="btn btn-success btn-xs">{{Lang::get('misc.renewed_for_one_year')}}</a>
		  	 </div>
		  </div>
		@endif
		
		@if( Auth::user()->team_id != 0 )
			<div class="panel-body">
		  	 <div class="alert alert-warning btn-sm margin-zero" role="alert">{{Lang::get('misc.team_membership_expired')}}</div>
		  </div>
		@endif
		
		
		@endif {{-- Valid Payment --}}
		

   </div><!-- Panel Default -->
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')

  {{ HTML::script('public/js/bootstrap-tokenfield.js') }}
  {{ HTML::script('public/js/draggable_background.js') }}
  {{ HTML::script('public/js/jquery.form.js') }}
  {{ HTML::script('public/js/mentions.js') }}
  
<script type="text/javascript">

	// Images Crop or Full Size
	$('.image_option').click(function () {
		  
		  var $_val = $(this).val();
		   
		  if ( $_val == 0 ) {
		   	 
		   	 $('#shotPreview').css({'background-color': '#DDD', 'background-size': 'cover' })
		   }
		   else if( $_val == 1 ) {
		   	 $('#shotPreview').css({'background-color': '#FFF', 'background-size': 'contain'})
		   }
		});
		
		
	// TAGS	
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
		
  $('input,select').not('#btnItems').keypress(function(event) { return event.keyCode != 13; });
  
  $("#descriptionUpload").charCount({ allowed: {{ $settings->shot_length_description }}, warning: 10, css: 'counterBio' });


//================== START FILE IMAGE FILE READER
$("#fileShot").change(function(){
	
	$('#shotPreview').css({ backgroundImage: 'none', textIndent: '0px'});
	
	
	var loaded = false;
	if(window.File && window.FileReader && window.FileList && window.Blob){
		if($(this).val()){ //check empty input filed
			oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
			if($(this)[0].files.length === 0){return}
			
			
			var oFile = $(this)[0].files[0];
			var fsize = $(this)[0].files[0].size; //get file size
			var ftype = $(this)[0].files[0].type; // get file type
			
			
			
			if(!rFilter.test(oFile.type)) {
				$('#fileShot').val('');
				$('.popout').html("{{ Lang::get('misc.formats_available') }}").fadeIn(500).delay(4000).fadeOut();
				return false;
			}
				
			var allowed_file_size = {{ $settings->file_size_allowed }};	
						
			if(fsize>allowed_file_size){
				$('#fileShot').val('');
				$('.popout').html("{{ Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ) }}").fadeIn(500).delay(4000).fadeOut();
				return false;
			}
			
			oFReader.onload = function (e) {
				
				var image = new Image();
			    image.src = oFReader.result;
			    
				image.onload = function() {
			    	
			    	if( image.width < 650 ) {
			    		$('#fileShot').val('');
			    		$('.popout').html("{{Lang::get('misc.width_min')}}").fadeIn(500).delay(4000).fadeOut();
			    		return false;
			    	} else {
			    		$('#shotPreview').css({backgroundImage: 'url('+e.target.result+')', textIndent: '-9999px'});
						
						/*$('.shot-preview').backgroundDraggable({
						  done: function(e) {
						    backgroundPosition = $('.shot-preview').css('background-position');
						    $('#position_shot').val(backgroundPosition);
						  }
						});// End Draggable Bg*/
			    	}//<<<< ELSE
			    };// <<--- image.onload

				
           }
           
           oFReader.readAsDataURL($(this)[0].files[0]);
			
		}
	} else{
		$('.popout').html('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.').fadeIn(500).delay(4000).fadeOut();
		return false;
	}
});

//================== START FILE - FILE READER
$("#attach_file").change(function(){
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
				$("#attach_file").val('');
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

@extends('layouts.app')

<?php 

	$settings = AdminSettings::first();

?>
     
@section('title'){{ Lang::get('misc.create_ad') }} - @stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home">
        		<i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i>
        	</a> {{ Lang::get('misc.create_ad') }} 
        	</h1>
       </div>
    </div>
@stop

@section('content') 

<div class="container">
<div class="row">
<!-- Col MD -->
<div class="col-xl-8">	
<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  	
		  	<span>
		  		<i class="icon-pencil2 myicon-right"></i> {{ Lang::get('misc.create_ad') }} 
		  	</span>	
		  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body" id="removePanel">
				  
			<form class="form-horizontal form-account" id="form-ads" method="post" role="form" action="{{ URL::to('ads/add') }}" enctype="multipart/form-data">
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif
            	
			 <div class="form-group @if( $errors->first('campaign_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.campaign_name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('campaign_name') }}" name="campaign_name" class="form-control input-sm" placeholder="{{ Lang::get('misc.campaign_name') }}">
			
			@if( $errors->first("campaign_name") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("campaign_name")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('ad_title') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.ad_title') }} 
			    	</label>
			    <div class="col-sm-10">
			      <input type="text" maxlength="50" name="ad_title" id="ad_title" value="{{ Input::old('ad_title') }}" class="form-control input-sm" placeholder="{{ Lang::get('misc.ad_title') }}">
			    
			    @if( $errors->first("ad_title") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("ad_title")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('ad_description') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.ad_description') }}</label>
			    <div class="col-sm-10">
			    	<input type="text" maxlength="100" id="ad_description" name="ad_description" class="form-control input-sm" placeholder="{{ Lang::get('misc.ad_description') }}" />
			    	
			    @if( $errors->first("ad_description") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("ad_description")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('ad_url') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.ad_url') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ Input::old('ad_url') }}" class="form-control input-sm" name="ad_url" id="ad_url" placeholder="{{ Lang::get('misc.ad_url') }}" >
			    
			     @if( $errors->first("ad_url") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("ad_url")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group margin-bottom-zero @if( $errors->first('tags') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.ad_image') }}</label>
			    <div class="col-sm-10">
			    	<button type="button" class="btn btn-default btn-border btn-sm btn-block" id="upload_image">
			      	{{ Lang::get('misc.select_an_image') }}
			      	</button>
			      	
			      	<input type="file" name="uploadImage" accept="image/*" id="uploadImage" style="visibility: hidden;">
			      	
			      	<div class="btn-default btn-xs btn-border btn-block pull-left text-left display-none imageContainer">
					     	<i class="fa fa-image myicon-right"></i>
					     	<small class="myicon-right file-name-file"></small> <i class="icon-cancel-circle delete-image pull-right" title="{{ Lang::get('misc.delete') }}"></i>
					     </div>
					     
			      	<small class="help-block">{{ Lang::get('misc.attach_file_support') }} JPG,PNG,GIF</small>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <h4 class="titleBar title-h4">
			  	<strong>{{ Lang::get('misc.how_want_spend') }}</strong>
			  	</h4>
			  <hr />
			  
			  
			  <div class="form-group margin-bottom-zero">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.optimize_for') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select data-per-impression="{{$settings->cost_per_impression}}" data-per-click="{{$settings->cost_per_click}}" data-impression="{{$settings->currency_symbol.$settings->cost_per_impression}} {{Lang::get('misc.per_impression')}}" data-click="{{$settings->currency_symbol.$settings->cost_per_click}} {{Lang::get('misc.per_click')}} " id="type" name="type" class="input-sm btn-block">
					  <option value="clicks" id="clicks" data="{{$settings->cost_per_click}}">{{ Lang::get('misc.clicks') }}</option>
					   <option value="impressions" id="impressions" data="{{$settings->cost_per_impression}}">{{ Lang::get('misc.impressions') }}</option>
					  </select>
					  <small class="help-block" id="perData"><strong>{{$settings->currency_symbol.$settings->cost_per_click}} {{Lang::get('misc.per_click')}}</strong></small>
			    </div>
			  </div>
			  
			  <div class="form-group ">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.quantity') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="quantity" name="quantity" class="input-sm btn-block">
					  <option value="1000">1,000</option>
					  <option value="2000">2,000</option>
					  <option value="3000">3,000</option>
					  <option value="4000">4,000</option>
					  <option value="5000">5,000</option>
					  </select>
			    </div>
			  </div>
			  
			  <hr />
			  
			   <h4 class="btn-block text-right">
			  	<strong>{{ Lang::get('misc.total') }}: {{$settings->currency_symbol}}<span id="total"></span></strong>
			  	</h4>
			  
			  <div class="alert alert-danger btn-sm display-none" id="errors" role="alert"></div>
			   	
			  <div class="form-group text-right margin-top-mv">
			  	
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" data-send="{{ Lang::get('misc.save_pay') }}" data-wait="{{ Lang::get('misc.send_wait') }}" class="btn btn-success btn-sm" id="add_ad">
			      	{{ Lang::get('misc.save_pay') }}
			      	</button>
				
				<a href="javascript:history.back()" class="btn btn-inverse btn-sm">
			      	{{Lang::get('users.cancel')}}
			      </a>
			      
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->
 </div><!-- /COL MD -->
 
<div class="col-xl-4">
	
	<!-- Start Panel -->
<div class="panel panel-default">
	<span class="panel-heading btn-block grid-panel-title">
		<span class="icon-bullhorn myicon-right"></span> {{ Lang::get('misc.preview') }}
		</span>
		
<div class="panel-body">
	<div class="btn-df li-group">
		<a href="javascript:void(0);" target="_blank" class="displayBlock position-relative">
			<div id="imagePreview" class="imageAdPreview"></div>
			<img src="{{ URL::asset('public/ad/ad.png') }}" class="img-responsive btn-block" />
		</a>
		
		<a href="javascript:void(0);" target="_blank" class="btn-block links-ads" id="ad_title_preview">{{ Lang::get('misc.advertise_here') }}</a>
		
		<p class="desc-ads" id="ad_description_preview">
			{{ Lang::get('misc.description_ad') }}
		</p>
	</div>
	</div><!-- Panel Body -->
</div><!--./ Panel Default -->
          
</div><!-- /End col md-4 -->
 
@stop
</div>
</div>

@section('javascript')
	   
<script type="text/javascript">

@if (Session::has('error_job'))
	 $('.popout').html("{{ Session::get('error_job')}}").fadeIn(500).delay(5000).fadeOut();
   @endif

var typeDefault     = $('#type option:selected').attr('data');
var quantityDefault = $('#quantity option:selected').val();
$('#total').html( typeDefault * quantityDefault );

// Preview Title
$('#ad_title').keyup(function(e){
	
	var valueClean  = $(this).val().replace(/\#+/gi,'%23');
    var _valueClean = $(this).val().replace(/<(?=\/?script)/ig, "&lt;");
   
   if( trim(_valueClean) != '' || trim(_valueClean) !=  0 ) {
   	$('#ad_title_preview').html( trim( escapeHtml(_valueClean) ) );
   }
    
});

// Preview Description
$('#ad_description').keyup(function(e){
	
	var valueClean  = $(this).val().replace(/\#+/gi,'%23');
    var _valueClean = $(this).val().replace(/<(?=\/?script)/ig, "&lt;");
    
    if( trim(_valueClean) != '' || trim(_valueClean) !=  0 ) {
    	$('#ad_description_preview').html( trim( escapeHtml(_valueClean) ) );
    }
});

// Optimize for:
$(document).on('change', '#type', function(){
	
	var perClick       = $(this).attr('data-click');
    var perImpression  = $(this).attr('data-impression');
    var dataClick      = $(this).attr('data-per-click');
    var dataImpression = $(this).attr('data-per-impression');
    var quantity       = $('#quantity option:selected').val();
    			  
	if ( $(this).val() == 'clicks' ) {
		$('#perData').html('<strong>'+perClick+'</strong>');
		$('#total').html( dataClick * quantity );
		
	} else {
		$('#perData').html('<strong>'+perImpression+'</strong>');
		$('#total').html( dataImpression * quantity );
	}
});

// Quantity:
$(document).on('change', '#quantity', function(){
	
    var dataClick      = $('#type').attr('data-per-click');
    var dataImpression = $('#type').attr('data-per-impression');
    var type           = $('#type option:selected').attr('data');
    var _value         = $(this).val();
    
	$('#total').html( type * _value );

});

$('.delete-image').click(function(){
    	$('.imageContainer').fadeOut(100);
    	$('.file-name-file').html('');
    	$('#uploadImage').val('');
    	$('#imagePreview').css({ backgroundImage: 'none', backgroundColor: 'transparent'});
    });
    
//================== START FILE IMAGE FILE READER
$("#uploadImage").change(function(){
	
	$('#imagePreview').css({ backgroundImage: 'none', backgroundColor: 'transparent'});
	$('.imageContainer').fadeOut(100);
	
	var loaded = false;
	if(window.File && window.FileReader && window.FileList && window.Blob){
		if($(this).val()){ //check empty input filed
			oFReader = new FileReader(), rFilter = /^(?:image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/png|image)$/i;
			if($(this)[0].files.length === 0){return}
			
			
			var oFile = $(this)[0].files[0];
			var fsize = $(this)[0].files[0].size; //get file size
			var ftype = $(this)[0].files[0].type; // get file type
			
			
			
			if(!rFilter.test(oFile.type)) {
				$('#uploadImage').val('');
				$('.popout').html("{{ Lang::get('misc.formats_available') }}").fadeIn(500).delay(4000).fadeOut();
				return false;
			}
				
			var allowed_file_size = {{ $settings->file_size_allowed }};	
						
			if(fsize>allowed_file_size){
				$('#uploadImage').val('');
				$('.popout').html("{{ Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ) }}").fadeIn(500).delay(4000).fadeOut();
				return false;
			}
			
			oFReader.onload = function (e) {
				
				var image = new Image();
			    image.src = oFReader.result;
			    
				image.onload = function() {
			    	
			    	if( image.width < 268 ) {
			    		$('#uploadImage').val('');
			    		$('.popout').html("{{Lang::get('misc.width_min_ads')}}").fadeIn(500).delay(4000).fadeOut();
			    		return false;
			    	} else {
			    		$('.imageContainer').fadeIn();
						$('.file-name-file').html(oFile.name);
			    		$('#imagePreview').css({backgroundImage: 'url('+e.target.result+')', display: 'block', backgroundColor: '#FFF'});
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

</script>
@stop


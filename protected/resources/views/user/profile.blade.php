@extends('layouts.app')

<?php 
	$settings = AdminSettings::first();
	$trueProfile = true; 
	
	if( $user->type_account != 3 ) {
		$shots_users = true;
	}
	
	if( $user->type_account == 3 ) {
		$members = User::where('team_id',$user->id)->get();
		$countMembers = $members->count();
	} else {
		$members      = 0;
		$countMembers = null;
	}
	
	$dateNow   = date('Y-m-d G:i:s'); 

	$_jobs = Jobs::where('paypal.payment_status', '=', 'Completed')
	->where('date_end', '>=', $dateNow)
	->where('jobs.user_id', $user->id)
	->where('members.type_account', 3)
	->leftjoin('members', 'jobs.user_id', '=', 'members.id')
	->leftjoin('paypal_payments_jobs as paypal', 'paypal.item_id', '=', 'jobs.id')
	->count();
			
?>

@section('title'){{ $title }}@stop



 @include('includes.static_user_info')
 

     

@section('content') 


@if( $user->bio != '' || $user->website != '' || $user->twitter != '' || $user->skills != '' || $countMembers != 0 || $_jobs != 0 )
<!-- container -->  
    <div class="container">
    <!-- row -->  
    <div class="row">

	      <!-- col-md -->  
	      <div class="col-xl-12">
	      	<!-- Start Panel -->
      	<div class="panel panel-default margin-zero panel-user">

    				<div class="panel-body padding-bottom-zero padding-top-zero">
    					
    					<ul class="list-grid">
    						
    						<h3 class="text-center">{{ Lang::get('users.about_me') }}</h3>
    									                   
			                   @if( $user->bio != '' )
			                   <h4 class="text-center bio-user none-overflow">{{ e($user->bio) }}</h4>
			                   @endif
			                   
			                   @if( $countMembers != 0 )
			                   
			                   <ul class="list-inline btn-block text-center col-thumb">
			                   	@foreach($members as $member)
			                   	<li>
			                   		<a href="{{URL::to('@').$member->username}}" title="{{e($member->name)}}" class="showTooltip" data-toggle="tooltip" data-placement="top">
			                   			<img width="30" class="img-circle" height="30" src="{{URL::asset('public/avatar/').'/'.$member->avatar}}">
			                   			</a>
			                   	</li>
			                   	@endforeach
			                   </ul>
			                   @endif
			                   
			                   @if( $user->skills != '' )
			                   
			                   <?php 
			                   $skills = explode(',', $user->skills); 
			                   $count_skills = count( $skills );
			                   ?>
			                   <li class="text-center">
			                     <strong>{{ Lang::get('misc.skills') }}:</strong> 
			                     
			                     @for( $i = 0; $i < $count_skills; ++$i )
			                     <a href="{{URL::to('designers?skills=') }}{{Helper::spacesUrl( $skills[$i])}}" class="urls-custom">{{ $skills[$i] }},</a>
			                     @endfor
			                     
			                     </li>
			                   @endif
			                   
			             @if( $user->website != '' || $user->twitter != '' )
    						<li class="text-center">
    						
    						@if( $user->website != '' )
    							<a target="_blank" href="{{ e( $user->website ) }}" class="urls-bio">
    								<i class="icon-earth myicon-right"></i> {{ e( Helper::removeHTPP( trim( Str::limit($user->website, $limit = 35, $end = '...'), '/' ) ) ) }}
    							</a> 
    							@endif
    						
    						@if( $user->twitter != '' )	
    							<a target="_blank" href="https://twitter.com/{{ $user->twitter }}"  class="urls-bio">
    								<i class="icon-twitter myicon-right"></i> {{ '@'.$user->twitter }}
    								</a>
    							@endif
    						
    						@if( $_jobs != 0 )	
    							<a href="{{URL::to('@').$user->username.'/jobs'}}" class="urls-bio">
    								<i class="icon-pushpin myicon-right"></i> {{ Lang::get('misc.jobs') }}
    							</a>
    							@endif
    								
    							</li>
    					@endif
    						
    						<hr />
    					</ul>
    				</div><!-- Panel Body -->
    			</div><!--./ Panel Default -->
	      </div><!-- col-md -->  
	   </div><!-- row -->  
	 </div><!-- container --> 
@endif


<!-- Col MD -->
<div class="col-xl-12">	
			
	@if( $total != 0 )
	
	@include('includes.shots')
	
	@else
	
	@if( Auth::check() && $user->id != Auth::user()->id || !Auth::check() )
	
	<div class="btn-block text-center">
	    	<i class="icon-quill ico-no-result"></i>
	    </div>
	    
	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- <strong>{{ $user->name }}</strong> {{ Lang::get('users.no_shot') }} -
	    	</h3>
	    	
	    @elseif (Auth::check() && $user->id == Auth::user()->id)
	    <div class="btn-block text-center">
	    	<i class="icon-quill ico-no-result"></i>
	    </div>	
	    <h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.session_no_shot') }} -
	    	</h3>
	    	
	@endif
	    	
	@endif
	
</div><!-- /COL MD -->

@stop

@section('javascript')

<script type="text/javascript">

@if( Auth::check() )  
//================== START FILE IMAGE FILE READER
$("#uploadImage").change(function(){
	$('.imageContainer').fadeOut(100);
	var loaded = false;
	if(window.File && window.FileReader && window.FileList && window.Blob){
		if($(this).val()){ //check empty input filed
			oFReader = new FileReader(), rFilter = /^(?:image\/bmp|image\/cis\-cod|image\/gif|image\/ief|image\/jpeg|image\/jpeg|image\/jpeg|image\/pipeg|image\/png|image\/\/x\-cmu\-raster|image\/x\-cmx|image\/x\-icon|image\/x\-portable\-anymap|image\/x\-portable\-bitmap|image\/x\-portable\-graymap|image\/x\-portable\-pixmap|image)$/i;
			if($(this)[0].files.length === 0){return}
			
			var oFile = $(this)[0].files[0];
			var fsize = $(this)[0].files[0].size; //get file size
			var ftype = $(this)[0].files[0].type; // get file type
			
			if(!rFilter.test(oFile.type)) {
				$('.popout').html("{{ Lang::get('misc.formats_available') }}").fadeIn(500).delay(4000).fadeOut();
				return false;
			}
				
			var allowed_file_size = {{ $settings->file_size_allowed }};	
						
			if(fsize>allowed_file_size){
				$('.popout').html("{{ Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ) }}").fadeIn(500).delay(4000).fadeOut();
				return false;
			}
			
			oFReader.onload = function (e) {
				//$('#upload-a').attr('src', e.target.result);
				$('.imageContainer').fadeIn();
				$('#previewImage').css({backgroundImage: 'url('+e.target.result+')'});
				$('.file-name').html(oFile.name);
           }
           
           oFReader.readAsDataURL($(this)[0].files[0]);
			
		}
	} else{
		$('.popout').html('Can\'t upload! Your browser does not support File API! Try again with modern browsers like Chrome or Firefox.').fadeIn(500).delay(4000).fadeOut();
		return false;
	}
});
//================== END FILE IMAGE FILE READER ==============>

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

// Report User
$('.report_user_spam').click(function(){
			element = $(this);
			$.post("{{URL::to('/')}}/report/user", { user_id: $(this).data('id') }, function(data){
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
		
		// Block Shot
$('.block_user_id').click(function(){
			element = $(this);
			$.post("{{URL::to('/')}}/block/user", { user_id: $(this).data('id') }, function(data){
				if(data.success == true ){
					element.remove();
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
		
   
    $("#message").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });
    
		@endif 
		{{-- End Auth verify --}}
</script>
@stop

@extends('layouts.master')

<?php 
	
	// ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$trueProfile = true; 

?>

@section('title'){{ Lang::get('misc.jobs') }} - {{ e( $user->name ) }} - @stop

 @include('includes.cover-static')

@section('content') 

<!-- Col MD -->
<div class="col-md-8">	

			
@if( $total != 0 )

  <div class="list-group">
  	
  	<span class="panel-heading btn-block grid-panel-title li-group">
	({{ $total }}){{ Lang::get('misc.jobs') }}
	</span>
	
   	<ul class="margin-zero padding-zero btn-block">	
	
@foreach( $data as $job )

<!-- Start -->
   	<!-- /Start List -->
	          <li class="li-group" id="job{{$job->id}}">
				<a href="{{ $job->url_job }}" target="_blank" class="list-group-item item-jobs border-group">	          		
				<div class="media media-jobs">
				      <div class="media-left">
				      @if( $job->type_account == 3 )
				        <img src="{{ URL::asset('public/avatar') }}/{{ $job->avatar }}" data-url="{{URL::to('@').$job->username}}" class="border-image-profile media-object img-circle image-dropdown job-image showTooltip teamJob" data-toogle="tooltip" data-placement="top" title="{{e($job->name)}}">
				      @endif
				      </div>
				      <div class="media-body clearfix">
				      	
				      	<h4 class="media-heading none-overflow">
				      		<strong>{{ e( $job->organization_name ) }}</strong> <span class="hiring">{{ Lang::get('misc.is_hiring') }}</span> <strong>{{ e( $job->workstation ) }}</strong>
				      		</h4>
				        
				        <p class="text-col">
				        	<i class="glyphicon glyphicon-map-marker myicon-right"></i> {{ e( $job->location ) }} 
				        </p>
				        
				         <small class="text-col jobs-date timeAgo" data="{{ date('c', strtotime( $job->date_start )) }}"></small>
				      </div>
				    </div>
				   </a>
	          </li><!-- /End List -->
   	<!-- /End -->
	
@endforeach
	</ul><!-- /End List -->
  </div><!-- /End list-group -->


@if( $data->getTotal() != $data->count() )
         	   
  <div class="btn-group paginator-style">
		   <?php echo $data->links(); ?> 
		</div>
		
@endif

				  	
	@else
	
	@if( Auth::check() && $user->id != Auth::user()->id || !Auth::check() )
	
	<div class="btn-block text-center">
	    	<i class="icon-pushpin ico-no-result"></i>
	    </div>
	    
	 	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.no_jobs_available') }} -
	    	</h3>
	    	
	    @elseif (Auth::check() && $user->id == Auth::user()->id)	
	 
		<div class="btn-block text-center">
	    	<i class="icon-pushpin ico-no-result"></i>
	    </div>
	    
	 	<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('misc.no_jobs_post') }} -
	    	</h3>
	    	
	    	<div class="btn-block text-center">
	    		<a href="{{ URL::to('/') }}/jobs/new" class="btn btn-sm btn-success no-shadow">
        		{{ Lang::get('misc.post_a_Job') }}
        		</a>
	    	</div>
		@endif
	
	
	@endif
</div><!-- /COL MD -->
@stop

@section('sidebar')
	<div class="col-md-4">
		@include('includes.ads')
	</div>
@stop

@section('javascript')
{{ HTML::script('public/js/count.js') }}
<script type="text/javascript">

@if (Session::has('success_add'))
	 $('.popout').html("{{ Session::get('success_add')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
   @if( Session::has('error_add') )
   	$('#myModal').modal('show');
   @endif

$("#updateShot").on('click',function(){
    	$(this).css({'display': 'none'})
    });
    
	$("#description").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

</script>
@stop

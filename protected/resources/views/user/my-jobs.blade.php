@extends('layouts.app')

@section('title'){{ Lang::get('misc.my_jobs') }} - @stop

@section('content') 
     
     <?php 
     use App\Models\AdminSettings;
     use App\Models\Jobs;
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
	 $dateNow   = date('Y-m-d G:i:s');
	 
	 $jobs = Jobs::where('jobs.user_id',Auth::user()->id)
	 ->leftjoin('paypal_payments_jobs as paypal', 'paypal.item_id', '=', 'jobs.id')
	 ->where('paypal.payment_status', '=', 'Completed')
	 ->orderBy('jobs.id','DESC')
	 ->select('jobs.*')
	 ->get();

     ?>
<div class="container">
<div class="row">
<div class="col-xl-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('misc.my_jobs') }}
		  		
		  		@if( $jobs->count() != 0 && $settings->allow_jobs == 'on' )
	    		<a href="{{ URL::to('/') }}/jobs/new" class="btn btn-xs btn-success no-shadow pull-right">
        		{{ Lang::get('misc.post_a_Job') }}
        		</a>
        	@endif
        	
		  	</div><!-- **btn-block ** -->
		  	
		  	
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
		  	
	@if( $jobs->count() != 0 )			  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
            <th class="active">ID</th>
            <th class="active">{{ Lang::get('misc.job_title') }}</th>
            <th class="active">{{ Lang::get('misc.location') }}</th>
            <th class="active">{{ Lang::get('misc.status') }}</th>
            <th class="active">{{ Lang::get('misc.expire') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $jobs as $job )
        	
        	<?php
        	
        	if( $job->date_end >= $dateNow ) {
        		$status = Lang::get('misc.active');
        	} else {
        		$status = Lang::get('misc.expired');
        	}
        	
        	 ?>
          <tr>
            <th scope="row">{{$job->id}}</th>
            <td><a href="{{URL::to('jobs')}}#job{{$job->id}}" title="{{e($job->workstation)}}"><strong>{{e(Str::limit($job->workstation,25,'...'))}}</strong></a></td>
            <td>{{e(Str::limit($job->location,25,'...'))}}</td>
            <td>{{$status}}</td>
            <td>{{date('Y-m-d G:s:i',strtotime($job->date_end))}}</td>
            <td>
            	<ul class="padding-zero margin-zero">
            		<li>
            			<a href="{{URL::to('edit/job')}}/{{$job->token}}">{{ Lang::get('users.edit') }}</a>
            		</li>
            		<li>
            			<a class="deleteJob" href="{{URL::to('delete/job')}}/{{$job->token}}">{{ Lang::get('misc.delete') }}</a>
            		</li>
            		@if( $job->date_end <= $dateNow )
            		<li>
            			<a href="{{URL::to('activate/job')}}/{{$job->token}}">{{ Lang::get('misc.activate_ad') }}</a>
            		</li>
            		@endif
            	</ul>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div><!-- Responsive -->

@else
<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('misc.no_jobs_post') }} -
	    	</h3>
	    
	    @if( $settings->allow_jobs == 'on')	
	    	<div class="btn-block text-center">
	    		<a href="{{ URL::to('/') }}/jobs/new" class="btn btn-sm btn-success no-shadow">
        		{{ Lang::get('misc.post_a_Job') }}
        		</a>
	    	</div>
	    	@endif
@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->

			
 </div><!-- /End col md-* -->
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
 //<<<---------- Delete Account      
  $(".deleteJob").click(function(e) {
   	e.preventDefault();
   	
   	var element = $(this);
	var url     = element.attr('href');
   	bootbox.confirm("{{ Lang::get('misc.delete_job_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });

</script>
@stop

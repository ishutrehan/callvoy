@extends('layouts.master')

@section('title'){{ Lang::get('admin.manage_jobs') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$dateNow   = date('Y-m-d G:i:s');
	$jobs = Jobs::orderBy('id','desc')->paginate(20);
 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('admin.manage_jobs') }} ({{$jobs->count()}})
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
          <tr class="hoverTr">
          	<td>{{$job->id}}</td>
            <td><a href="{{URL::to('jobs')}}#job{{$job->id}}" title="{{e($job->workstation)}}"><strong>{{e(Str::limit($job->workstation,25,'...'))}}</strong></a></td>
            <td>{{e(Str::limit($job->location,25,'...'))}}</td>
            <td>{{$status}}</td>
            <td>{{date('Y-m-d G:s:i',strtotime($job->date_end))}}</td>
          	<td>
          		<ul class="padding-zero margin-zero">
            		<li>
		          		<a href="{{URL::to('panel/admin/jobs/edit')}}/{{$job->token}}">{{ Lang::get('users.edit') }}</a>
		          		</li>
		          		<li>
		          		<a data-url="{{URL::to('panel/admin/jobs/delete')}}/{{$job->token}}" class="delete" style="cursor: pointer;">{{ Lang::get('misc.delete') }}</a>
		          	</li>
            	</ul>
          		</td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div><!-- Responsive -->

@else 

<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('misc.no_jobs_available') }} -
	    	</h3>

@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    @if( $jobs->getLastPage() > 1 && Input::get('page') <= $jobs->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $jobs->links(); ?> 
	        	</div>
    		@endif

			
 </div><!-- /End col md-* -->
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('admin.sidebar_admin')
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
  
<script type="text/javascript">

 //<<<---------- Delete Account      
  $(".delete").click(function(e) {
   	e.preventDefault();
   	
   	var element = $(this);
	var url     = element.attr('data-url');
   	bootbox.confirm("{{ Lang::get('misc.delete_job_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop
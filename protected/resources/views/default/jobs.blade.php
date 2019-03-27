@extends('layouts.master')

<?php 
	 
	 $settings = AdminSettings::first();
	 
	 $teams    = Input::get('teams');
	 $location = Input::get('location');
	 $anywhere = Input::get('anywhere');
	 
	 // ** Sort
	 if( isset( $location ) && $location != '' ) {
	 	$sortQuery = $location;
	 } else if( isset( $anywhere ) && $location == '' ) {
	 	$sortQuery = 'anywhere';
	 } else {
	 	$sortQuery = '';
	 }
	 
	 //** Sort Companies
	 if( isset( $teams ) ) {
	 	$array = array( 3 );
	 } else {
	 	$array = array( 1, 2, 3 );
	 }
	 
	 if( !isset( $location ) && !isset( $teams ) ) {
	 	$sortQuery = '';
		$array = array( 1, 2, 3 );
	 }
	 
     $dateNow   = date('Y-m-d G:i:s'); 
	 
	 $jobs_sql   = DB::table('jobs')
	->select(DB::raw('
			members.name, 
			members.avatar,
			members.username,
			members.type_account,
			jobs.id,
			jobs.organization_name,  
			jobs.workstation, 
			jobs.url_job, 
			jobs.location, 
			jobs.date_start, 
			jobs.date_end
	'))
	->leftjoin('members', 'jobs.user_id', '=', 'members.id')
	->leftjoin('paypal_payments_jobs as paypal', 'paypal.item_id', '=', 'jobs.id')
	->where('paypal.payment_status', '=', 'Completed')
	->where('date_end', '>=', $dateNow)
	->where( 'jobs.location','LIKE', '%'.$sortQuery.'%' )
	->whereIn('members.type_account', $array)
	->groupBy('jobs.id')
	->orderBy('jobs.id', 'desc')
	->get();
	
	 $count_jobs = count( $jobs_sql );
	 
	 switch( $settings->duration_jobs ) {
			case '15days':
				$duration_jobs  = Lang::get('admin.15days');
				break;
			case '30days':
				$duration_jobs  = Lang::get('admin.30days');
				break;
			case '60days':
				$duration_jobs  = Lang::get('admin.60days');
				break;
			case '90days':
				$duration_jobs  = Lang::get('admin.90days');
				break;
		}

?>
     
@section('title'){{ Lang::get('misc.jobs') }} - @stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home">
        		<i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i>
        		</a> {{ Lang::get('misc.jobs_for_designers') }}
        		@if(  $settings->allow_jobs == 'on' )
        		<?php $priceExplode = explode( '.', $settings->price_jobs) ?>
        	<a href="{{ URL::to('/') }}/jobs/new" class="btn btn-sm btn-success no-shadow btn-postjob">
        		{{ Lang::get('misc.post_a_Job') }} {{$settings->currency_symbol.$priceExplode[0]}} 
        		@if( $priceExplode[1] != '00' ) <sup>{{$priceExplode[1]}}</sup>@endif
        		</a>
        		@endif
        	</h1>
       </div>
    </div>
@stop

@section('content') 

@if( $count_jobs != 0 )    
<!-- Col MD -->
<div class="col-md-8">	
   <div class="list-group">
   	
   	<span class="panel-heading btn-block grid-panel-title li-group">
	({{ $count_jobs }}) {{ Lang::get('misc.jobs_available') }}
	</span>
	
   	<ul class="margin-zero padding-zero btn-block">
   	@foreach( $jobs_sql as $job )
   	
   	<!-- Start -->
   	<!-- /Start List -->
	          <li class="li-group" id="job{{$job->id}}">
				<a href="{{ $job->url_job }}" target="_blank" class="list-group-item item-jobs border-group">	          		
				<div class="media media-jobs">
				      <div class="media-left">
				      	@if( $job->type_account == 3 )
				        <img src="{{ URL::asset('public/avatar') }}/{{ $job->avatar }}" data-url="{{URL::to('@').$job->username}}" class="border-image-profile media-object img-circle image-dropdown job-image showTooltip teamJob" data-toogle="tooltip" data-placement="top" title="{{e($job->name)}}">
				      @else
				      	<img src="{{ URL::asset('public/avatar') }}/job_avatar.jpg" class="border-image-profile media-object img-circle image-dropdown job-image">
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
 </div><!-- /COL MD -->
 
<div class="col-md-4">
	@if( $settings->allow_jobs == 'on' )
	<a href="{{ URL::to('/') }}/jobs/new" class="btn btn-success btn-lg btn-block shadow-inset">
		{{ Lang::get('misc.post_a_Job') }} {{$settings->currency_symbol.$priceExplode[0]}}
		@if( $priceExplode[1] != '00' ) <sup>{{$priceExplode[1]}}</sup>@endif
		<h5 class="btn-block margin-zero">{{$duration_jobs}}</h5>
		</a>
	<hr />
	@endif
	
<div class="panel panel-default">
<span class="panel-heading btn-block grid-panel-title" href="about">
	<i class="icon-filter2 myicon-right"></i> {{ Lang::get('misc.sort_jobs') }}
	</span>
    			<div class="panel-body">
    				<!-- Form -->
    				<form role="search" id="sortJobs" action="" method="get">
    					<dl class="margin-zero">
    					<div class="form-group">		
    						<dd>
    							<label class="checkbox-inline">
    								<input <?php if( isset($teams) && $teams == 'on'): echo 'checked="checked"'; endif ?> class="no-show" name="teams" type="checkbox" value="on">
    								<span class="input-sm">{{ Lang::get('misc.only_teams') }}</span>
    							</label>
    							
    							<label class="checkbox-inline">
    								<input <?php if( isset($anywhere) && $anywhere == 'on'): echo 'checked="checked"'; endif ?> class="no-show" name="anywhere" type="checkbox" value="on">
    								<span class="input-sm">{{ Lang::get('misc.remot_anywhere') }}</span>
    							</label>
    							
    						</dd>
    					</div><!-- form-group -->
    					
    					<div class="form-group">		
    						<dt><i class="icon-location myicon-right"></i> {{ Lang::get('misc.location') }}</dt>
    						<dd>
    							<input type="search" class="form-control input-sm" value="{{ e(Input::get('location')) }}" name="location" placeholder="{{ Lang::get('misc.placeholder_location') }}">
    						</dd>
    					</div><!-- form-group -->
    					
    					</dl><!-- Margin Zero DL -->

    					 <div class="form-group">
    					 	<button type="submit" id="searchBtn" disabled="disabled" value="search" class="btn btn-info btn-sm btn-block btn-sort">
    					 		{{ Lang::get('misc.find') }} <span class="glyphicon glyphicon-arrow-right"></span>
    					 		</button>
    					 </div><!-- form-group -->
    					 
    					</form><!-- Form -->
    					
    				</div><!-- Panel Body -->
    			</div><!-- panel-default -->
          
</div><!-- /End col md-4 -->
@endif

@if( $count_jobs == 0 )
<div class="col-md-12">
	
	<h1 class="title-item none-overflow">({{ $count_jobs }}) {{ Lang::get('misc.jobs') }} <small>{{ Lang::get('misc.available') }}</small></h1>
	<hr />
	
	<div class="btn-block text-center">
	    	<i class="icon-close ico-no-result"></i>
	    </div>
	    
   	<h3 class="margin-top-none text-center no-result">
	    	- {{ Lang::get('misc.no_jobs_available') }} -
	    	</h3>
	    	</div><!-- /End col md-4 -->

   	@endif
 
@stop

@section('javascript')
	
   
<script type="text/javascript">
@if (Session::has('success_add_job'))
	 $('.popout').html("{{ Session::get('success_add_job')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   	//** Changes Form **//
function changesForm () {
var button = $('#searchBtn');
$('form#sortJobs input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form#sortJobs input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});
    
    $('form#sortJobs input, select, textarea, checked').each(function () {
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
   </script>
@stop


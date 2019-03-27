@extends('layouts.master')

<?php 
	 $settings = AdminSettings::first();

     ?>
     
@section('title'){{ Lang::get('misc.jobs') }} - @stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home">
        		<i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i>
        		<?php $priceExplode = explode( '.', $settings->price_jobs) ?>
        	</a> {{ Lang::get('misc.post_a_Job') }} {{$settings->currency_symbol.$priceExplode[0]}} @if( $priceExplode[1] != '00' ) <sup>{{$priceExplode[1]}}</sup>@endif
        	</h1>
       </div>
    </div>
@stop

@section('content') 

 
<!-- Col MD -->
<div class="col-md-8">	
<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  	
		  	<span style="color:#999; margin-right: 15px;">
		  		<span class="badge" style="background-color:#ccc;">1</span> {{ Lang::get('misc.fill_details') }} 
		  	</span>	
		  	
		  		<i class="fa fa-angle-right" style="margin-right: 15px;"></i>
		  		
	  		<span>
	  			<span class="badge">2</span> {{ Lang::get('misc.preview_and_pay') }}
	  			</span>
		  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="list-group">
				  
			<ul class="margin-zero padding-zero btn-block">
				<li class="li-group">
				<a href="{{ Input::get('url_to_job_description') }}" target="_blank" class="list-group-item item-jobs border-group">	          		
				<div class="media media-jobs">
				      <div class="media-left">
					  @if( Auth::user()->type_account == 3 )
				      					      	<img src="{{URL::to('public/avatar')}}/{{Auth::user()->avatar}}" class="border-image-profile media-object img-circle image-dropdown job-image">
				      		 @else
								<img src="{{URL::to('public/avatar')}}/job_avatar.jpg" class="border-image-profile media-object img-circle image-dropdown job-image">
										  @endif
										  </div>
				      <div class="media-body clearfix">
				      	
				      	<h4 class="media-heading none-overflow">
				      		<strong>{{ e(Input::get('organization_name')) }}</strong> <span class="hiring">{{Lang::get('misc.is_hiring')}}</span> <strong>{{ e(Input::get('job_title')) }}</strong>
				      		</h4>
				        
				        <p class="text-col">
				        	<i class="glyphicon glyphicon-map-marker myicon-right"></i> {{ Input::get('location') }} 
				        </p>
				        
				         <small class="text-col jobs-date timeAgo" data="{{date('c',time())}}"></small>
				      </div>
				    </div>
				   </a>
	          </li>
			</ul>
				  
		</div><!-- list-group -->

   </div><!-- Panel Default -->
   
   <div class="btn-block text-right">
   	
   		
   		<form action="{{URL::to('jobs/payment')}}" method="post">
   			<input type="hidden" name="organization_name" value="{{ Input::get('organization_name') }}">
   			<input type="hidden" name="job_title" value="{{ e(Input::get('job_title')) }}">
   			<input type="hidden" name="url_to_job_description" value="{{ e(Input::get('url_to_job_description')) }}">
   			<input type="hidden" name="location" value="{{ e(Input::get('location')) }}">
   			<input type="hidden" name="contact_name" value="{{ e(Input::get('contact_name')) }}">
   			<input type="hidden" name="contact_email" value="{{ e(Input::get('contact_email')) }}">
   			<input type="hidden" name="token" value="{{ Str::random($length = 40) }}">
   	
   	<a href="javascript:history.back()" class="btn btn-sm btn-inverse btn-padding">
   		<i class="icon-pencil2 myicon-right myicon-right"></i> {{ Lang::get('users.edit') }}
   		</a>
   				
   	<button type="submit" class="btn btn-sm btn-success btn-padding">
   		<i class="icon-paypal myicon-right"></i> {{ Lang::get('misc.pay_paypal') }}
   		</button>	
   		
   		</form>
   		
   	
   </div>
 </div><!-- /COL MD -->
 
<div class="col-md-4">
	
	@include('includes.ads')
          
</div><!-- /End col md-4 -->

 
@stop

@section('javascript')
	
   
   <script type="text/javascript">
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


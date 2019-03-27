@extends('layouts.master')

<?php 
	 $settings = AdminSettings::first();
	 
	 
	 
	 // Total Stats
	   $total   = Advertising::totalStats( $data->id, $data->type );
	   
	   // Payments
	   $payments = DB::table('paypal_payments_ads')->where('item_id',$data->id)->orderBy('id','DESC')->first();
	   
	   // URL Activate Ad
	   $url_activate = URL::to('ads/activate').'/'.$data->code;
	
	   // Status
	   if( $data->status == 'active' ) {
	   	$status = Lang::get('misc.active');
	   } else {
	   	$status = Lang::get('misc.stopped');
	   }
	   
	   if( !isset( $payments->payment_status )  ){
	   	$statusPayment = Lang::get('admin.unpaid');
	   } else {
	   	$statusPayment = Lang::get('admin.paid');
	   }
			   
     ?>
  
@section('title'){{ Lang::get('admin.manage_ads') }} - @stop


@section('content') 

 
<!-- Col MD -->
<div class="col-md-8">	
<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block" style="color:#999;">
		  	
		  	<span>
		  		<i class="fa fa-angle-left" style="margin: 0 5px;"></i>
		  		
		  		<a href="javascript:history.back()">{{ Lang::get('auth.back') }}</a>
		  		
		  		<span style="margin: 0 5px;">/</span>
		  		
		  		{{ Lang::get('admin.manage_ads') }} 
		  	</span>	
		  			  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
            <th class="active">{{ Lang::get('admin.detail') }}</th>
            <th class="active">{{ Lang::get('admin.description') }}</th>
          </tr>
        </thead>
        <tbody>
        	<!-- Start Tr -->
        	<tr>
	          <th scope="row">
	            ID
	          </th>
	          <td>{{$data->id}}</td>
	        </tr><!-- End Tr -->
	        
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	           {{ Lang::get('admin.user') }}
	          </th>
	          <td><a href="{{URL::to('@').$data->user()->username}}" target="_blank"><strong>{{'@'.e($data->user()->username)}}</strong></a></td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	            {{ Lang::get('misc.campaign_name') }}
	          </th>
	          <td>{{e($data->campaign_name)}}</td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	            {{ Lang::get('misc.ad_type') }}
	          </th>
	          <td>{{$data->quantity}} - {{$data->type}}</td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	           {{ Lang::get('misc.balance') }}
	          </th>
	          <td>{{$data->balance}}</td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	           {{ Lang::get('users.stats') }}
	          </th>
	          <td>{{$total}} {{Str::title($data->type)}}</td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	           {{ Lang::get('admin.date') }}
	          </th>
	          <td>{{$data->created_at}}</td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	           {{ Lang::get('misc.status') }}
	          </th>
	          <td>{{$status}}</td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	           {{ Lang::get('admin.payment_status') }}
	          </th>
	          <td class="text-capitalize">{{$statusPayment}}</td>
	        </tr><!-- End Tr -->
	        
	        <!-- Start Tr -->
	        <tr>
	          <th scope="row">
	           {{ Lang::get('admin.link_ad') }}
	          </th>
	          <td><a href="{{e($data->ad_url)}}" target="_blank"><strong>{{e($data->ad_url)}}</strong></a></td>
	        </tr><!-- End Tr -->
	        
        </tbody>
      </table>
</div><!-- Responsive -->

<a data-url="{{URL::to('panel/admin/ads/delete')}}/{{$data->code}}" class="delete btn btn-danger btn-sm pull-right">{{ Lang::get('misc.delete') }}</a>

		</div><!-- Panel Body -->

   </div><!-- Panel Default -->
 </div><!-- /COL MD -->
 
<div class="col-md-4">
	
	<!-- Start Panel -->
<div class="panel panel-default">
	<span class="panel-heading btn-block grid-panel-title">
		<span class="icon-bullhorn myicon-right"></span> {{ Lang::get('misc.preview') }}
		</span>
		
<div class="panel-body">
	<div class="btn-df li-group">
		<a href="javascript:void(0);" target="_blank" class="displayBlock position-relative">
			<div id="imagePreview" class="imageAdPreview"></div>
			<img src="{{ URL::asset('public/ad/').'/'.$data->ad_image }}" class="img-responsive btn-block" />
		</a>
		
		<a href="javascript:void(0);" target="_blank" class="btn-block links-ads" id="ad_title_preview">{{e($data->ad_title)}}</a>
		
		<p class="desc-ads" id="ad_description_preview">
			{{e($data->ad_desc)}}
		</p>
	</div>
	</div><!-- Panel Body -->
</div><!--./ Panel Default -->

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
   	bootbox.confirm("{{ Lang::get('misc.delete_ad_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop
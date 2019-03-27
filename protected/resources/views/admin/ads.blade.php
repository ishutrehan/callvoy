@extends('layouts.master')

@section('title'){{ Lang::get('admin.manage_ads') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$dateNow   = date('Y-m-d G:i:s');
	$ads = Advertising::orderBy('id','desc')->paginate(20);
 
     ?>
     
<div class="col-md-12">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block" style="color:#999;">
		  		
		  		<i class="fa fa-angle-left" style="margin: 0 5px;"></i>
		  		
		  		<a href="{{URL::to('panel/admin')}}">{{ Lang::get('admin.back_panel_admin') }}</a>
		  		
		  		<span style="margin: 0 5px;">/</span>
		  		
		  		{{ Lang::get('admin.manage_ads') }} ({{$ads->count()}})
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
@if( $ads->count() != 0 )	
		  				  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
          	<th class="active">ID</th>
          	<th class="active">{{ Lang::get('admin.user') }}</th>
          	<th class="active">{{ Lang::get('admin.image') }}</th>
          	<th class="active">{{ Lang::get('misc.campaign_name') }}</th>
            <th class="active">{{ Lang::get('misc.ad_type') }}</th>
            <th class="active">{{ Lang::get('misc.balance') }}</th>
            <th class="active">{{ Lang::get('admin.date') }}</th>
            <th class="active">{{ Lang::get('users.stats') }}</th>
            <th class="active">{{ Lang::get('misc.status') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $ads as $ad )
        	<?php
        		// Total Stats
        	   $total   = Advertising::totalStats( $ad->id, $ad->type );
			   
			   // Payments
			   $payments = DB::table('paypal_payments_ads')->where('item_id',$ad->id)->orderBy('id','DESC')->first();
			   
			   // URL Activate Ad
			   $url_activate = URL::to('ads/activate').'/'.$ad->code;

			   // Status
			   if( $ad->status == 'active' ) {
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
          <tr class="hoverTr">
          	<td>{{$ad->id}}</td>
          	<td><a href="{{URL::to('@').$ad->user()->username}}" target="_blank"><strong>{{'@'.e(Str::limit($ad->user()->username,10,'...'))}}</strong></a></td>
          	<td><img src="{{URL::asset('public/ad').'/'.$ad->ad_image}}" width="50" /></td>
            <td>{{e(Str::limit($ad->campaign_name,25,'...'))}}</td>
            <td class="text-capitalize">{{$ad->quantity}} - {{$ad->type}}</td>
            <td>{{$ad->balance}}</td>
            <td>{{$ad->created_at}}</td>
            <td>{{$total}} {{Str::title($ad->type)}}</td>
            <td class="text-capitalize">{{$statusPayment}}</td>
          	<td>
          		<ul class="padding-zero margin-zero">
          			<li>
          				<a href="{{URL::to('panel/admin/ads/details')}}/{{$ad->code}}">
          					{{Lang::get('admin.view')}}
          				</a>
          			</li>
		          		<li>
		          		<a data-url="{{URL::to('panel/admin/ads/delete')}}/{{$ad->code}}" class="delete" style="cursor: pointer;">{{ Lang::get('misc.delete') }}</a>
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
	    	- {{ Lang::get('misc.no_results_found') }} -
	    	</h3>

@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    @if( $ads->getLastPage() > 1 && Input::get('page') <= $ads->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $ads->links(); ?> 
	        	</div>
    		@endif

			
 </div><!-- /End col md-* -->
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
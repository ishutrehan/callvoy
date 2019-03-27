@extends('layouts.app')

@section('title'){{ Lang::get('misc.my_ads') }} - @stop

@section('content') 
     
     <?php 

    use App\Models\AdminSettings;
    use App\Models\Advertising;
    use Illuminate\Support\Str;

    

     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
	 $dateNow   = date('Y-m-d G:i:s');
	 
	 $ads = Advertising::where('user_id',Auth::user()->id)
	 ->orderBy('id','DESC')
	 ->get();

     ?>
 <div class="container">
   
 <div class="row">
<div class="col-xl-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('misc.my_ads') }}
		  		
		  		@if( $ads->count() != 0 && $settings->allow_ads == 'on' )
			  		<a href="{{ URL::to('/') }}/ad/new" class="btn btn-xs btn-success no-shadow pull-right">
	        		{{ Lang::get('misc.create_ad') }}
	        		</a>
	        	@endif
        		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
		  	
	@if( $ads->count() != 0 )			  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
            <th class="active">ID</th>
            <th class="active">{{ Lang::get('misc.campaign_name') }}</th>
            <th class="active">{{ Lang::get('misc.ad_type') }}</th>
            <th class="active">{{ Lang::get('misc.balance') }}</th>
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
			   // $payments = DB::table('paypal_payments_ads')->where('item_id',$ad->id)->orderBy('id','DESC')->first();
			   
			   // URL Activate Ad
			   $url_activate = URL::to('ads/activate').'/'.$ad->code;

			   // Status
			   if( $ad->status == 'active' ) {
			   	$status = Lang::get('misc.active');
			   } else {
			   	$status = Lang::get('misc.stopped');
			   }
        	 ?>

          <tr>
            <th scope="row">{{$ad->id}}</th>
            <td>{{e(Str::limit($ad->campaign_name,25,'...'))}}</td>
            <td class="text-capitalize">{{$ad->quantity}} - {{$ad->type}}</td>
            <td>{{$ad->balance}}</td>
            <td>{{$total}} {{Str::title($ad->type)}}</td>
            <td class="text-capitalize">{{$status}}</td>
            <td>
            	<ul class="padding-zero margin-zero">
            		<li>
            			<a href="{{URL::to('edit/ad')}}/{{$ad->code}}">{{ Lang::get('users.edit') }}</a>
            		</li>
            		<li>
            			
            			<a class="deleteAd" href="{{URL::to('delete/ad')}}/{{$ad->code}}">{{ Lang::get('misc.delete') }}</a>
            		</li>
            		@if( !isset( $payments->payment_status )  )
            		<li>
            			<a href="{{$url_activate}}">{{ Lang::get('misc.activate_ad') }}</a>
            		</li>
            		
            		@elseif( isset( $payments->payment_status ) && $payments->payment_status == 'Completed' && $ad->balance >= $ad->quantity )
            		 <li>
            			<a href="{{$url_activate}}">{{ Lang::get('misc.activate_ad') }}</a>
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
	    	- {{ Lang::get('misc.no_ads_post') }} -
	    	</h3>
	    
	    @if( $settings->allow_ads == 'on' )	
	    	<div class="btn-block text-center">
	    		<a href="{{ URL::to('/') }}/ad/new" class="btn btn-sm btn-success no-shadow">
        		{{ Lang::get('misc.create_ad') }}
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

	@if (Session::has('success_add_ad'))
	 $('.popout').html("{{ Session::get('success_add_ad')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
 //<<<---------- Delete Account      
  $(".deleteAd").click(function(e) {
   	e.preventDefault();
   	
   	var element = $(this);
	var url     = element.attr('href');
   	bootbox.confirm("{{ Lang::get('misc.delete_ad_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop

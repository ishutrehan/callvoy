@extends('layouts.master')

@section('title'){{ Lang::get('admin.payments_jobs') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	
	$payments_all = DB::table('paypal_payments_jobs')
	->orderBy('id','desc')->paginate(20);
 
   	 	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block" style="color: #999;">
		  		<a href="{{URL::to('panel/admin/payments')}}">{{ Lang::get('admin.payments_teams') }}</a> <i class="fa fa-angle-right" style="margin: 0 5px;"></i>
		  		{{ Lang::get('admin.payments_jobs') }} <i class="fa fa-angle-right" style="margin: 0 5px;"></i>
		  		<a href="{{URL::to('panel/admin/payments-ads')}}">{{ Lang::get('admin.payments_ads') }}</a> 
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
	
	<h4 class="margin-top-zero">
		{{Lang::get('admin.total_earnings')}}: {{$settings->currency_symbol}}{{DB::table('paypal_payments_jobs')->sum('payment_amount')}}
		</h4>
		
@if( $payments_all->count() != 0 )	
		  				  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
          	<th class="active">ID</th>
            <th class="active">{{ Lang::get('admin.email_payer') }}</th>
            <th class="active">{{ Lang::get('admin.date') }}</th>
            <th class="active">{{ Lang::get('admin.item_name') }}</th>
            <th class="active">{{ Lang::get('admin.status') }}</th>
            <th class="active">{{ Lang::get('admin.amount') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $payments_all as $pay )

          <tr>
          	<td>{{$pay->id}}</td>
          	<td>{{$pay->payer_email}}</td>
          	<td>{{$pay->datenow}}</td>
          	<td>{{$pay->item_name}}</td>
          	<td>{{$pay->payment_status}}</td>
          	<td>{{$pay->payment_amount}}</td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div><!-- Responsive -->

@else 

<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('admin.no_payments') }} -
	    	</h3>

@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    @if( $payments_all->getLastPage() > 1 && Input::get('page') <= $payments_all->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $payments_all->links(); ?> 
	        	</div>

	        	
    		@endif

			
 </div><!-- /End col md-* -->
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('admin.sidebar_admin')
          
</div><!-- /End col md-4 -->

@stop
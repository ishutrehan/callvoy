@extends('layouts.app')

<?php 
	 $settings = AdminSettings::first();
	 
	 if( $data->type == 'clicks' ) {
	 	$totalPrice = ($settings->cost_per_click * $data->quantity);
	 } else {
	 	$totalPrice = ($settings->cost_per_impression * $data->quantity);
	 }
	 
	 // Payments
	$payments = DB::table('paypal_payments_ads')->where('item_id',$data->id)->orderBy('id','DESC')->first();

     ?>
     
@section('title'){{ Lang::get('misc.activate_ad_pay') }} - @stop

@section('css_style')

{{ HTML::script('public/js/jquery.min.js') }}

<script type="text/javascript">
$(document).on({
    "contextmenu": function(e) {
        console.log("ctx menu button:", e.which); 

        // Stop the context menu
        e.preventDefault();
    },
    "mousedown": function(e) { 
        console.log("normal mouse down:", e.which); 
    },
    "mouseup": function(e) { 
        console.log("normal mouse up:", e.which); 
    }
});

$(document).keydown(function(event){
    if(event.keyCode==123){
        return false;
    }
    else if (event.ctrlKey && event.shiftKey && event.keyCode==73){        
             return false;
    }
});

$('body').keydown(function (event) {

	    if( event.which  == 116 || event.which  == 27  ){
	     	return false;   
	    }
   });//======== FUNCTION 

</script>
@stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home">
        		<i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i>
        	</a> {{ Lang::get('misc.activate_ad_pay') }} 
        	</h1>
       </div>
    </div>
@stop

@section('content') 
<div class="container">
	<div class="row">
 
<!-- Col MD -->
<div class="col-xl-8">	
<!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  	
		  	<span>
		  		{{ Lang::get('misc.activate_ad_pay') }} 
		  	</span>	
		  			  		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
		  	
		    <div class="alert alert-success btn-sm display-none" id="success_response" role="alert">
		    	{{Lang::get('misc.success_update_ad')}} <i class="fa fa-long-arrow-left"></i> <a href="{{URL::to('my/ads')}}">{{Lang::get('misc.back_to_my_ads')}}</a>
		    	</div>
		    
<div id="removePanel">	  
<form class="form-horizontal form-account" method="post" id="form-ads" action="{{URL::to('ads/payment')}}" role="form" enctype="multipart/form-data">
  
  <input type="hidden" name="item_name" id="item_name" value="{{ e($data->campaign_name) }} - {{ $data->quantity }} {{ $data->type }}">
  <input type="hidden" name="amount" id="amount" value="{{ $totalPrice }}">
  <input type="hidden" name="id_ad" value="{{ $data->id }}">
  <input type="hidden" name="token" value="{{ Str::random($length = 40) }}">
  <input type="hidden" name="type" id="_type" value="{{ $data->type }}">
  <input type="hidden" name="quantity" id="_quantity" value="{{ $data->quantity }}">
  
  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
            <th class="active">ID</th>
            <th class="active">{{ Lang::get('misc.campaign_name') }}</th>
            <th class="active">{{ Lang::get('misc.ad_type') }}</th>
          </tr>
        </thead>
        <tbody>
        	<tr>
            <th scope="row">{{$data->id}}</th>
            <td>{{e(Str::limit($data->campaign_name,25,'...'))}}</td>
            <td class="text-capitalize"><span id="qty">{{$data->quantity}}</span> - <span id="typ">{{$data->type}}</span>
            @if( isset( $payments->payment_status ) && $payments->payment_status == 'Completed' && $data->balance >= $data->quantity )	
            	- <a href="javascript:void(0);" id="editSpend" style="color: #F40808;">{{Lang::get('users.edit')}}</a>
            	@endif
            	</td>
          </tr>
        </tbody>
      </table>
</div><!-- Responsive -->

        			  
			  <hr />
			 
			   <h4 class="btn-block text-right">
			  	<strong>{{ Lang::get('misc.total') }}: {{$settings->currency_symbol}}<span id="total">{{$totalPrice}}</span></strong>
			  	</h4>
			  	
			  <div class="alert alert-danger btn-sm display-none" id="errors" role="alert"></div>
			  
			   	
			  <div class="form-group text-right margin-top-mv">
			  	
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-success btn-sm" id="paypalPay">
			      	<i class="icon-paypal myicon-right"></i> {{ Lang::get('misc.pay_paypal') }}
			      	</button>
				
				<a href="{{URL::to('my/ads')}}" class="btn btn-inverse btn-sm">
			      	{{Lang::get('users.cancel')}}
			      </a>
			      
			    </div>
			  </div><!-- **** form-group **** -->
			  
		</form><!-- **** form **** -->
		
<form class="form-horizontal form-account formSpend display-none" method="post" id="form-ads" action="javascript:void(0);" role="form" enctype="multipart/form-data">

<hr />

<input type="hidden" name="ad_code" value="{{ $data->code }}">
<input type="hidden" name="ad_id" value="{{ $data->id }}">
<div class="form-group margin-bottom-zero">
			    
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.optimize_for') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select data-per-impression="{{$settings->cost_per_impression}}" data-per-click="{{$settings->cost_per_click}}" data-impression="{{$settings->currency_symbol.$settings->cost_per_impression}} {{Lang::get('misc.per_impression')}}" data-click="{{$settings->currency_symbol.$settings->cost_per_click}} {{Lang::get('misc.per_click')}} " id="type" name="type" class="input-sm btn-block">
					  <option id="type_clicks" value="clicks" id="clicks" data="{{$settings->cost_per_click}}">{{ Lang::get('misc.clicks') }}</option>
					   <option id="type_impressions" value="impressions" id="impressions" data="{{$settings->cost_per_impression}}">{{ Lang::get('misc.impressions') }}</option>
					  </select>
					  <small class="help-block" id="perData">
					  	@if( $data->type == 'clicks' )
					  	<strong>{{$settings->currency_symbol.$settings->cost_per_click}} {{Lang::get('misc.per_click')}}</strong>
					  	@else
					  	<strong>{{$settings->currency_symbol.$settings->cost_per_impression}} {{Lang::get('misc.per_impression')}}</strong>
					  	@endif
					  	</small>
			    </div>
			  </div>
			  
			  <div class="form-group ">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.quantity') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="quantity" name="quantity" class="input-sm btn-block">
					  <option id="quantity_1000" value="1000">1,000</option>
					  <option id="quantity_2000" value="2000">2,000</option>
					  <option id="quantity_3000" value="3000">3,000</option>
					  <option id="quantity_4000" value="4000">4,000</option>
					  <option id="quantity_5000" value="5000">5,000</option>
					  </select>
			    </div>
			  </div>
			  
			   <div class="form-group text-right margin-top-mv">
			  	
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" disabled="disabled" data-send="{{ Lang::get('misc.save_pay') }}" data-wait="{{ Lang::get('misc.send_wait') }}" class="btn btn-success btn-sm" id="update_spend">
			      	{{ Lang::get('users.done') }}
			      	</button>
			      
			    </div>
			  </div><!-- **** form-group **** -->
</form>
			
			</div><!-- **** Remove Panel **** -->	
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->
 </div><!-- /COL MD -->
 
<div class="col-xl-4">
	
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
          
</div><!-- /End col md-4 -->
</div>
</div>
@stop

@section('javascript')
<script type="text/javascript">

$( "#editSpend" ).click(function() {
  $( '.formSpend' ).toggleClass('display-none');
});

	//Type
$('#type_{{$data->type}}').attr({'selected':'selected'});

// Quantity
$('#quantity_{{$data->quantity}}').attr({'selected':'selected'});

var typeDefault     = $('#type option:selected').attr('data');
var quantityDefault = $('#quantity option:selected').val();
$('#total').html( typeDefault * quantityDefault );

// Optimize for:
$(document).on('change', '#type', function(){
	
	var perClick       = $(this).attr('data-click');
    var perImpression  = $(this).attr('data-impression');
    var dataClick      = $(this).attr('data-per-click');
    var dataImpression = $(this).attr('data-per-impression');
    var quantity       = $('#quantity option:selected').val();
    			  
	if ( $(this).val() == 'clicks' ) {
		$('#perData').html('<strong>'+perClick+'</strong>').fadeIn();
		$('#total').html( dataClick * quantity );
		$('#amount').val( dataClick * quantity );
		$('#_type').val( $(this).val() );
		$('#typ').html( $(this).val() );
		$('#item_name').val( '{{ e($data->campaign_name) }} -  ' + quantity + ' ' + $(this).val() );
		
	} else {
		$('#perData').html('<strong>'+perImpression+'</strong>').fadeIn();
		$('#total').html( dataImpression * quantity );
		$('#amount').val( dataImpression * quantity );
		$('#_type').val( $(this).val() );
		$('#typ').html( $(this).val() );
		$('#item_name').val( '{{ e($data->campaign_name) }} -  ' + quantity + ' ' + $(this).val() );
	}
});

// Quantity:
$(document).on('change', '#quantity', function(){
	
    var dataClick      = $('#type').attr('data-per-click');
    var dataImpression = $('#type').attr('data-per-impression');
    var __type         = $('#type option:selected').val();
    var type           = $('#type option:selected').attr('data');
    var _value         = $(this).val();
    
	$('#total').html( type * _value );
	$('#amount').val( type * _value );
	$('#_quantity').val( _value );
	$('#qty').html( _value );
	$('#item_name').val( '{{ e($data->campaign_name) }} -  ' + $(this).val() + ' ' + __type );
	

});

//** Changes Form **//
function changesForm () {
var button = $('#update_spend');
$('form.formSpend input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form.formSpend input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});
    
    $('form.formSpend input, select, textarea, checked').each(function () {
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

$( "#update_spend" ).click(function() {
  $( '.formSpend' ).addClass('display-none');
   changesForm();
});
</script>
@stop

<?php
use App\Models\AdminSettings;
use App\Models\Advertising;

$settings    = AdminSettings::first();
$_ads = Advertising::leftjoin('paypal_payments_ads as paypal','paypal.item_id','=','advertising.id')
->whereRaw('advertising.balance < advertising.quantity')
->where('payment_status','Completed')
->where('status','active')
	 ->orderByRaw('rand( '.time() . ' * ' . time().')')
	 ->take(1)
	 ->select('advertising.*')
	 ->get();

?>

@if( $_ads->count() != 0  )

	@foreach( $_ads as $ad )
	
	<?php 
		
	$url_ad = URL::to('click/ads/').'/'.$ad->id; 
	
	if( $ad->type == 'impressions' ){
		
		// URL TO URL
		$url_ad = $ad->ad_url; 
		
		// Insert Impressions
		Advertising::impressionsAds($ad->id);
		
	} else {
		$url_ad = URL::to('click/ads').'/'.$ad->id; 
	}
	
	
	?>
	
	<!-- Start Panel -->
	<div class="panel panel-default">
		<div class="panel-heading btn-block grid-panel-title">
			<span class="icon-bullhorn myicon-right"></span> {{ Lang::get('misc.advertising') }}
			
			@if( Auth::check() && $settings->allow_ads == 'on' )
			<a href="{{URL::to('ad/new')}}" class="link_ads_create pull-right">
				{{Lang::get('misc.create_ad')}}
			</a>
			@endif
			</div>
	<div class="panel-body">
		<div class="btn-df li-group">
			<a target="_blank" href="{{$url_ad}}">
				<img src="{{ URL::asset('protected/public/ad/').'/'.$ad->ad_image }}" class="img-responsive btn-block" />
			</a>
			<a target="_blank" href="{{$url_ad}}" class="btn-block links-ads">{{ e($ad->ad_title) }}</a>
			<p class="desc-ads">
				{{ e($ad->ad_desc) }}
			</p>
		</div>
		</div><!-- Panel Body -->
	</div><!--./ Panel Default -->
	@endforeach

@else 


<!-- Start Panel -->
<div class="panel panel-default">
	<div class="panel-heading btn-block grid-panel-title">
		<span class="icon-bullhorn myicon-right"></span> {{ Lang::get('misc.advertising') }}
		
		@if( Auth::check() && $settings->allow_ads == 'on' )
		<a href="{{URL::to('ad/new')}}" class="link_ads_create pull-right">
			{{Lang::get('misc.create_ad')}}
		</a>
		@endif
		</div>
<div class="panel-body">
	<div class="btn-df li-group">
		<a href="javascript:void(0);">
			<img src="{{ URL::asset('protected/public/ad/ad.png') }}" class="img-responsive btn-block" />
		</a>
		<a href="javascript:void(0);" class="btn-block links-ads">{{ Lang::get('misc.advertise_here') }}</a>
		<p class="desc-ads">
			{{ Lang::get('misc.description_ad') }}
		</p>
	</div>
	</div><!-- Panel Body -->
</div><!--./ Panel Default -->

@endif

@if( !isset( $noShowAdOfGoogle ) )
	@include('includes.ads_google')
@endif



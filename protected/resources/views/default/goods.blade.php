@extends('layouts.master')

@section('title'){{ Lang::get('misc.goods_for_sale') }} -@stop

@section('content') 
     
     <?php 
     
	 $page = Input::get('page');
	 
     // ** Admin Settings ** //
     $settings = AdminSettings::first();

		 $sql   = Shots::where('url_purchased','!=','')
		 ->where('status',1)
		 ->orderBy('id','DESC')
		 ->paginate( $settings->result_request ); //$settings->result_request

		 
	 //<<---- * Total Search * ------->
	 $totalSearch = $sql->getTotal();

     ?>
     	<!-- Col MD -->
<div class="col-md-8">	
	

<h1 class="title-item none-overflow">

	{{ Lang::get('misc.goods_for_sale') }}
	</h1>
<hr />
     
     @foreach( $sql as $shot )	
     
     <?php
	
	if( Str::slug( $shot->title ) == '' ) {
	
		$slugUrl  = '';
	} else {
		$slugUrl  = '-'.Str::slug( $shot->title );
	}
	
	$slugUrlShot = URL::to('/').'/shots/'.$shot->id.$slugUrl;
	 ?>
     
     	<div class="media media-designer">
    		<span class="pull-left">
    			<a class="image-thumb" title="{{$shot->name}}" href="{{$slugUrlShot}}">
    			<img width="150" class="media-object img-rounded img-thumbnail" src="{{ URL::asset('public/shots_img') .'/'.$shot->image}}" />
    			</a>
    		</span>
    		<div class="media-body media-jobs">
    			<div class="pull-left desc-goods">
    				<h4 class="media-heading">
    				<a class="link-user-profile" title="{{$shot->title}}" href="{{$slugUrlShot}}">{{ e(Str::limit($shot->title, 25,  '...' )) }}</a> 
 
    				
    			</h4>
    			 <!-- List group -->
    			 <ul class="list-group list-designer">
    			 		
    			<?php 
		   		$url =  parse_url($shot->url_purchased);
				$host = $url['host'];
		   		?>
    			 		<li>
    			 			<a href="{{e($shot->url_purchased)}}" target="_blank" class="links-ds">
    			 				<span class="icon-cart myicon-right color-strong"></span>  {{ Lang::get('misc.buy_at') }} {{e(Str::title($host))}} <strong>${{$shot->price_item}}</strong>
    			 			</a>
    			 		</li>
    			 		
    			 		<li>
    			 			<a href="{{ URL::to('@').$shot->user()->username }}" class="links-ds">
    			 				<img width="18" height="18" class="img-circle" src="{{ URL::asset('public/avatar') .'/'.$shot->user()->avatar}}" />  {{ e($shot->user()->name) }} 
    			 			</a>
    			 		</li>
    			 </ul>
    			</div><!-- /End Pull Left -->

    		</div><!-- /End Media Body -->
    		
</div><!-- /End Media -->
    		
    		@endforeach
    		
    		@if( $sql->count() == 0 )
    	<div class="btn-block text-center">
	    	<i class="icon-cart ico-no-result"></i>
	    </div>
    		<h3 class="margin-top-none text-center no-result">
	    	- {{ Lang::get('misc.no_goods') }} -
	    	</h3>
	    	@endif
	    	
	    	
    		@if( $sql->getLastPage() > 1 && $page <= $sql->getLastPage() )
	    	
	    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $sql->links(); ?> 
	        	</div>
	        	
    		
    		@endif
    		
 </div><!-- /COL MD -->
@stop

@section('sidebar')
<div class="col-md-4">

@if( Auth::check() && Auth::user()->type_account == 2 || Auth::check() && Auth::user()->type_account == 3 )
<a class="btn btn-danger btn-lg btn-block shadow-inset col-thumb" href="{{ URL::to('upload') }}">
		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i> {{ Lang::get('misc.upload') }}
		</a>
		@endif

		@include('includes.ads')
          
          
          
</div><!-- /End col md-4 -->

@stop

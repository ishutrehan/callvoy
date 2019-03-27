@extends('layouts.app')

@section('title'){{ Lang::get('users.notifications') }} - @stop

@section('css_style')
<link href="{{ URL::asset('public/css/jquery.fs.picker.min.css') }}" rel="stylesheet">
@stop

@section('content') 
     
     <?php 
     use App\Models\AdminSettings;
     use Illuminate\Support\Str;

     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();

     ?>
<div class="container">
<div class="row">
<div class="col-xl-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('users.notifications') }}
		  
		  @if( $sql->count() != 0 )		
		  	<span class="pull-right pos-relative cog-btn">
		  		<a href="{{ Url::to('notifications/delete') }}" title="{{ Lang::get('misc.delete_all') }}" class="link-post delete-notifications">
					  <i class="glyphicon glyphicon-trash"></i>
				</a>
		  	</span><!--	 pull-right pos-relative -->
		  	@endif
		  	
		  	</span><!-- **btn-block ** -->
		  	
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
		  	
<dl class="margin-zero">
		  		
		  		
<?php

if( $sql->count() !=  0 ) {

	  foreach ( $sql as $key ) {
	  	
		if( Str::slug( $key->title ) == '' ) {

			$slugUrl  = '';
		} else {
			$slugUrl  = '-'.Str::slug( $key->title );
		}
		
		$url_shot = URL::to('shots').'/'.$key->id.$slugUrl;
		
		//<----- URL TO LIST
		if( Str::slug( $key->list_name ) == '' ) {
	
		$_slugUrl  = '';
		} else {
			$_slugUrl  = '-'.Str::slug( $key->list_name );
		}
		
		$url_list = URL::to('@').$key->username.'/lists/'.$key->list_id.$_slugUrl;
		
						  
		switch( $key->type ) {
			case 1:
				$action          = Lang::get('users.followed_you');
				$icoDefault      = '<i class="icon-user3 ico-btn-followed"></i>';
				$title           = null;
				$linkDestination = false;
				break;
			case 2:
				$action          = Lang::get('users.like_you_shot');
				$icoDefault      = '<i class="icon-heart ico-btn-like ico-btn-like"></i>';
				$title           = $key->title;
				$linkDestination = $url_shot;
				break;
			case 3:
				$action          = Lang::get('users.comment_you_shot');
				$icoDefault      = '<i class="icon-bubble"></i>';
				$title           = $key->title;
				$linkDestination = $url_shot;
				break;
			case 4:
				$action          = Lang::get('users.mentions_shot');
				$icoDefault      = '@';
				$title           = $key->title;
				$linkDestination = $url_shot;
				break;
			case 5:
				$action          = Lang::get('users.mentions_in_comment');
				$icoDefault      = '@';
				$title           = $key->title;
				$linkDestination = $url_shot;
				break;
			case 6:
				$action          = Lang::get('users.liked_your_comment');
				$icoDefault      = '<i class="icon-heart ico-btn-like ico-btn-like"></i>';
				$title           = $key->title;
				$linkDestination = $url_shot;
				break;
			case 7:
				$action          = Lang::get('users.add_list');
				$icoDefault      = '<i class="icon-list"></i>';
				$title           = $key->list_name;
				$linkDestination = $url_list;
				break;
				
			case 8:
				$action          = Lang::get('users.add_team');
				$icoDefault      = '<i class="icon-users"></i>';
				$title           = null;
				$linkDestination = false;
				break;
				
			case 9:
				$action          = Lang::get('users.invitation_pro_user');
				$icoDefault      = '<span class="label label-primary btn-pro-xs-active">'.Lang::get('misc.pro').'</span>';
				$title           = null;
				$linkDestination = false;
				break;
				
			case 10:
				$action          = Lang::get('users.add_by_admin');
				$icoDefault      = '<i class="icon-user"></i>';
				$title           = null;
				$linkDestination = false;
				break;
		}

?>
		  		<!-- Start -->
					<div class="media li-group noty-media">
					  <div class="pull-left">
					    <a href="{{ URL::to('@') }}{{ $key->username }}">
					      <img width="40" height="40" class="img-circle myicon-righ media-objectt" alt="User" src="{{ URL::asset('public/avatar').'/'.$key->avatar }}">
					    </a>
					  </div>
					  <div class="media-body">
					  	<div class="pull-right small">
							<span class="timestamp timeAgo" data="{{ date('c', strtotime( $key->created_at )) }}"></span>
							</div>
					    <h5 class="media-heading">
					    	<a href="{{ URL::to('@') }}{{ $key->username }}">
					    		{{ e( $key->name ) }}
					    	</a>
					    </h5>
					    
					    <p class="list-grid-block p-text" style="height: auto;">
				    		{{ $icoDefault }} {{ $action }} 
				    		
				    		@if( $linkDestination != false )
						<a href="{{ $linkDestination }}">
							<strong>{{ e(Str::limit($title, 30,  '...')) }}</strong>
							</a> 
						  
						@endif
				    		
				    		</p>
					  </div>
					</div>
					<!-- End -->
					
		<?php 
	}//foreach 

} // != 0 ?>

          @if( $sql->count() == 0 )
          
          	<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('misc.no_notifications') }} -
	    	</h3>
          
          @endif
					
			</dl>
		  	
		  </div><!-- Panel Body -->

   </div><!-- Panel Default -->
   <?php
   /*
   @if( $sql->getLastPage() > 1 && Input::get('page') <= $sql->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $sql->links(); ?> 
	        	</div>
	        	
    		@endif*/ ?>

 </div>
@stop

@section('sidebar')
<div class="col-xl-4">
	
	@include('includes.user-card')
	
	@include('includes.ads')
          
</div><!-- /End col md-4 -->

@stop
</div>
</div>

@section('javascript')
  
<script type="text/javascript">

 //<<<---------- Delete Account      
  $(".delete-notifications").click(function(e) {
   	e.preventDefault();
   	
   	var element = $(this);
	var url     = element.attr('href');
   	bootbox.confirm("{{ Lang::get('misc.delete_notify_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop
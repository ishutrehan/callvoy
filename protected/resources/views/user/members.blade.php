@extends('layouts.master')

@section('title'){{ Lang::get('misc.team_members') }} - @stop

@section('css_style')
<link href="{{ URL::asset('public/css/jquery.fs.picker.min.css') }}" rel="stylesheet">
@stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // TEAMS Membership Check
	   if( Auth::user()->type_account == 3 ) {
	   	
		$_dateNow   = date('Y-m-d G:i:s');
		   	
		$teamMembershipStatus = DB::table('paypal_payments_teams')
		->where('user_id', Auth::user()->id)
		->where('expire','>',$_dateNow)
		->where('payment_status', '=', 'Completed')
		->orderBy('id', 'desc')
		->first(); 
	   }
	 	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('misc.team_members') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
	
	@if( isset( $teamMembershipStatus ) 
	|| Auth::user()->team_free == 1
	|| $settings->team_free == 'on' )			  
			<form class="form-horizontal form-account" role="form" action="javascript:void(0);">
            <!-- **** form-group **** -->
			 <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.add_members') }}</label>
			    
			    <div class="col-sm-10">
			    	
			 <div class="position-relative">
			      <input type="text" id="btnAdd" class="form-control input-sm searchMember" placeholder="{{ Lang::get('misc.add_members') }}" autocomplete="off" />
			    	
     			<div id="boxLogin" class="boxSearch boxAutoComplete" style="width: 100%; left: 0;">
				     <ul class="toogle_search">
								<li class="searchGlobal" style="margin-bottom: 5px;text-align: center;"></li>
								<span class="load_search"></span>
							</ul>
					</div><!-- BOX -->
				</div><!-- wrap -->

			    	
			    	<small class="help-block margin-bottom-zero">*{{ Lang::get('users.up_members',['limit' => $settings->members_limit ]) }}</small>
			    </div><!-- col-sm-10 -->
			  </div><!-- **** form-group **** -->
			</form><!-- **** form **** -->
		@else 
		
		 <div class="alert alert-warning btn-sm margin-zero" role="alert">
		  	 	{{Lang::get('misc.membership_expired_teams')}} <a href="{{URL::to('renew/membership')}}" class="btn btn-success btn-xs">{{Lang::get('misc.renewed_for_one_year')}}</a>
		  	 </div>
			
			@endif
			
			<hr />

<div class="container-designers"><!-- container designers -->
@foreach( $data as $user )
<!-- Start MediaDesigner -->			
<div class="media media-designer members-team-list">
    		<span class="pull-left">
    			<a class="image-thumb" title="{{$user->name}}" href="{{URL::to('@').$user->username}}">
    			<img width="45" height="45" class="media-object img-circle" src="{{URL::asset('public/avatar').'/'.$user->avatar}}">
    			</a>
    		</span>
    		<div class="media-body">
    			<div class="pull-left">
    				<h4 class="media-heading">
    				<a class="link-user-profile" title="{{$user->name}}" href="{{URL::to('@').$user->username}}">
    					{{$user->name}}</a> 
    			</h4>
    			 <!-- List group -->
    	<ul class="list-group list-designer margin-zero">
    		<li>
    			 	{{'@'.$user->username}}		
    			</li>
    			 </ul>
    			</div><!-- /End Pull Left -->
    			
    			<span class="pull-right">
    				<i class="icon-cancel-circle delete-member" id="deleteBtn" data-id="{{$user->id}}" data-delete="{{Lang::get('misc.delete_member')}}" title="{{Lang::get('misc.delete')}}"></i>
    			</span>
  </div><!-- /End Media Body -->
</div><!-- End MediaDesigner -->
@endforeach
</div><!-- container designers -->
		
		
			<dl class="margin-zero @if( $data->count() != 0 ) display-none @endif" id="noResult">
	          	<h3 class="margin-top-none text-center no-result color-no-result">
		    	- {{Lang::get('misc.no_members')}} -
		    	</h3>
			</dl>
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->

			
 </div>
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('includes.user-card')
	
	@include('includes.sidebar_edit_user')

	@include('includes.ads')
          
          
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
  
<script type="text/javascript">
@if (Session::has('success_renew'))
	 $('.popout').html("{{ Session::get('success_renew')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
</script>
@stop

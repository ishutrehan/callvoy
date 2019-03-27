<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php $settings = AdminSettings::first(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
    <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />

    <title>{{ Lang::get('auth.join') }} - {{ $settings->title }}</title>

    @include('includes.css_general')

  </head>

  <body id="join-section">
     
     @include('includes.navbar')
    
    
<!-- Start CONTAINER -->
<div class="container wrap-ui">
	<!-- ROW -->
	<div class="row">
        
			<div class="col-md-12 col-pb">
				<h3 class="text-center join-title">{{ Lang::get('auth.join') }} - {{ $settings->title }}</h3>
				<h4 class="text-center join-title">{{ Lang::get('auth.already_have_an_account') }} <a href="login" class="btn btn-xs btn-success no-shadow font-default btn-join margin-zero">{{ Lang::get('auth.sign_in_nav') }}</a></h4>
			</div>
			
		<div class="col-md-6">
			
			<div class="panel panel-default">
			  <div class="panel-heading grid-panel-title">
			  	<h3>{{ Lang::get('users.account_normal') }} 
			  		<small class="pull-right price-join">{{ Lang::get('users.free') }}</small>
			  		</h3> 
			  	</div>
			  <div class="panel-body">
			    <ul class="list-join">
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.follow_your_designers') }}</li>
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.contact_designers') }}</li>
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.find_designers') }}</li>
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.create_list') }}</li>
			    	@if( isset( $settings->pro_users_default ) && $settings->pro_users_default == 'off'  )
			    	<li><small>** {{ Lang::get('users.requires_invitation') }}</small></li>
			    	@else
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('admin.upload_your_work') }}</li>
			    	@endif
			    </ul>
			    <a href="{{URL::to('join-normal')}}" class="btn btn-inverse btn-sort border-total">{{ Lang::get('users.create_account') }} <i class="fa fa-plus"></i></a>
			  </div>
			</div>
		</div><!-- /col -->
		
		<div class="col-md-6">
			
			<div class="panel panel-default">
			  <div class="panel-heading grid-panel-title">
			  	<h3>{{ Lang::get('users.team_account') }}
			  		<small class="pull-right price-join">
			  			@if( isset( $settings->team_free ) && $settings->team_free == 'off'  )
			  			<?php $priceExplode = explode( '.', $settings->price_membership_teams) ?>
			  			{{$settings->currency_symbol.$priceExplode[0]}} @if( $priceExplode[1] != '00' ) <sup>{{$priceExplode[1]}}</sup>@endif {{Lang::get('admin.yearly')}}
			  		@else
			  			{{ Lang::get('users.free') }}
			  		@endif
			  		</small>
			  	</h3>
			  	</div>
			  <div class="panel-body">
			    <ul class="list-join">
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.team_members_upload') }}</li>
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.hire_designers') }}</li>
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.projects_and_attachments') }}</li>
			    	<li><i class="fa fa-check-circle iconCheck myicon-right"></i> {{ Lang::get('users.grow_your_audience') }}</li>
			    	<li><small>*{{ Lang::get('users.up_members',['limit' => $settings->members_limit ]) }}</small></li>
			    </ul>
			    <a href="{{URL::to('join-team')}}" class="btn btn-success border-total">{{ Lang::get('users.create_account') }} <i class="fa fa-plus"></i></a>
		
			  </div>
			</div>
		</div><!-- /col -->
	    	
	
	</div> 
</div><!-- /container -->
    
@include('includes.javascript_general')
     
  </body>
</html>
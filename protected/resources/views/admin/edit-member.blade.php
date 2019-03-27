@extends('layouts.master')

@section('title'){{ Lang::get('users.edit_profile') }} - @stop

@section('css_style')
{{ HTML::style('public/css/bootstrap-tokenfield.css') }}
@stop

@section('content')

     <?php

     // ** Admin Settings ** //
     $settings = AdminSettings::first();

	 // ** Data User logged ** //
     $user = User::find($id);

     ?>

<div class="col-md-8">

     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">

		  	<span class="btn-block">

		  		<a href="{{URL::to('panel/admin/members')}}">{{ Lang::get('admin.members') }}</a>

		  		<i class="fa fa-angle-right" style="margin: 0 5px;"></i>

		  		{{ Lang::get('users.edit_profile') }}

		  		<i class="fa fa-angle-right" style="margin: 0 5px;"></i>

		  		{{e($user->name)}}
		  	</span><!-- **btn-block ** -->

		  </div><!-- ** panel-heading ** -->

		  <div class="panel-body">

			<form class="form-horizontal form-account" method="post" role="form" action="{{URL::to('panel/admin/members/edit/').'/'.$id}}">

			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif

            	<input type="hidden" name="id" value="{{$id}}" />

			 <div class="form-group @if( $errors->first('full_name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.full_name_misc') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e( $user->name ) }}" name="full_name" class="form-control input-sm" id="full_name" placeholder="{{ Lang::get('misc.full_name_misc') }}">

			@if( $errors->first("full_name") )
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("full_name")}}</strong>
	     	</div><!-- Error -->
	     	@endif

			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.username_misc') }}
			    		<i class="icon-question" title="{{ Lang::get('misc.username_no_edit') }}"></i>
			    	</label>
			    <div class="col-sm-10">
			      <input id="disabledInput" disabled="disabled" type="text" value="{{ $user->username }}" class="form-control input-sm" placeholder="{{ Lang::get('misc.username_misc') }}">
			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group @if( $errors->first('email') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('auth.email') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $user->email }}" name="email" class="form-control input-sm" id="email" placeholder="{{ Lang::get('auth.email') }}">

			    @if( $errors->first("email") )
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("email")}}</strong>
	     	</div><!-- Error -->
	     	@endif

			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group @if( $errors->first('location') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.location') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e( $user->location ) }}" class="form-control input-sm" name="location" id="location" placeholder="{{ Lang::get('misc.placeholder_location') }}" autocomplete="off">

			     @if( $errors->first("location") )
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("location")}}</strong>
	     	</div><!-- Error -->
	     	@endif

			    </div>
			  </div><!-- **** form-group **** -->


			  <div class="form-group @if( $errors->first('website') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.website_misc') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e( $user->website ) }}" class="form-control input-sm" id="website" name="website" placeholder="{{ Lang::get('misc.website_misc') }}">

			    @if( $errors->first("website") )
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("website")}}</strong>
	     	</div><!-- Error -->
	     	@endif

			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group @if( $errors->first('twitter') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Twitter</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $user->twitter }}" class="form-control input-sm" id="inputTwitter" name="twitter" placeholder="{{ Lang::get('misc.username_on_Twitter') }}">

			    @if( $errors->first("twitter") )
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("twitter")}}</strong>
	     	</div><!-- Error -->
	     	@endif

			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group @if( $errors->first('skills') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.skills') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $user->skills }}" name="skills" class="form-control input-sm" id="skills" placeholder="{{ Lang::get('misc.skills') }}">

			    @if( $errors->first("skills") )
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("skills")}}</strong>
	     	</div><!-- Error -->
	     	@endif

			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group @if( $errors->first('bio') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.bio_misc') }}</label>
			    <div class="col-sm-10">
			      <textarea name="bio" rows="4" id="bio" class="form-control input-sm textarea-textx">{{ e( $user->bio ) }}</textarea>

			    @if( $errors->first("bio") )
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("bio")}}</strong>
	     	</div><!-- Error -->
	     	@endif

			    </div>
			  </div><!-- **** form-group **** -->

			  <h4 class="titleBar title-h4">
			  	{{ Lang::get('admin.role_type_account') }}
			  	</h4>

			  <hr />

			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.status')}}</label>
			    <div class="col-sm-10">

			    	<select id="status" name="status" class="input-sm btn-block">
					  	<option id="status-pending" value="pending">{{Lang::get('admin.pending')}}</option>
					  	<option id="status-active" value="active">{{Lang::get('admin.active')}}</option>
					  	<option id="status-suspended" value="suspended">{{Lang::get('admin.suspended')}}</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->

			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.role')}}</label>
			    <div class="col-sm-10">

			    	<select id="role" name="role" class="input-sm btn-block">
					  	<option id="role-normal" value="normal">{{Lang::get('admin.normal')}}</option>
					  	<option id="role-admin" value="admin">{{Lang::get('admin.admin')}}</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->

			  @if( $user->type_account != 3 )
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.type_account')}}</label>
			    <div class="col-sm-10">

			    	<select id="type_account" name="type_account" class="input-sm btn-block">
					  	<option id="type_account-1" value="1">{{Lang::get('admin.normal')}}</option>
					  	<option id="type_account-2" value="2">{{Lang::get('admin.pro')}}</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->

			  @else
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.type_account')}}</label>
			    <div class="col-sm-10">

			    	<select id="type_account" name="type_account" class="input-sm btn-block">
					  	<option id="type_account-3" value="3">{{Lang::get('admin.team')}}</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  @endif

			  <hr />

			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-info btn-sm btn-sort" id="saveUpdate" disabled="disabled">{{ Lang::get('misc.save_changes') }}</button>

			      <a data-url="{{URL::to('panel/admin/members/delete')}}/{{$user->token}}" href="#" class="delete-account pull-right">
			    	<small>{{ Lang::get('admin.delete_user') }}</small>
			    </a>
			    </div>
			  </div><!-- **** form-group **** -->

			</form><!-- **** form **** -->

		</div><!-- Panel Body -->

   </div><!-- Panel Default -->


 </div>
@stop

@section('sidebar')
<div class="col-md-4">

	<!-- panel-default -->
	<div class="panel panel-default">
			<div class="panel-body padding-top padding-top-zero padding-right-zero padding-left-zero">

				<div style="background: url({{ URL::asset('public/cover').'/'.$user->cover }}) no-repeat center center #0088E2; background-size: cover;" class="cover-wall"></div>

			<div class="media media-visible pd-right">
				  <a href="{{ URL::to('@') }}{{ $user->username }}" class="btn-block photo-card-live myprofile">
				    <img class="border-image-profile img-circle photo-card" alt="Image" src="{{ URL::asset('public/avatar').'/'.$user->avatar }}" width="80" height="80">
				  </a>
				  <div class="media-body">
				    <h4 class="user-name-profile-card btn-block  text-center">
				    	<a class="myprofile" href="{{ URL::to('@') }}{{ $user->username }}">
				    		<span class="none-overflow">{{ e( $user->name ) }}</span>
				    		</a>
					</h4>
				  </div>
				</div>

	    		<ul class="nav list-inline nav-pills btn-block nav-user-profile-wall">
	    			<li><a href="{{ URL::to('@') }}{{ $user->username }}">{{ Lang::get('misc.shots') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalShots( $user->id ) ) }}</small></a></li>
	    			<li><a href="{{ URL::to('@') }}{{ $user->username }}/followers">{{ Lang::get('users.followers') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalFollowers( $user->id ) ) }}</small></a></li>
	    			<li><a href="{{ URL::to('@') }}{{ $user->username }}/following">{{ Lang::get('users.following') }} <small class="btn-block sm-btn-size counter-sm">{{ Helper::formatNumber( User::totalFollowing( $user->id ) ) }}</small></a></li>
	    			</ul>

			</div><!-- Panel Body -->
	</div><!-- panel-default -->

	<ol class="details-shot">
			<li><span class="icon-clock myicon-right color-strong"></span> {{Lang::get('users.registered')}} <span class="pull-right color-strong">{{date('Y-m-d',strtotime($user->date))}}</span></li>
			<li><span class="icon-list myicon-right color-strong"></span> {{Lang::get('users.lists')}} <strong class="pull-right color-strong">{{ Helper::formatNumber( $user->lists()->where('type',1)->count() ) }}</strong></li>
			<li><span class="icon-briefcase myicon-right color-strong"></span> {{Lang::get('users.projects')}} <strong class="pull-right color-strong">{{ Helper::formatNumber( $user->projects()->count() ) }}</strong></li>
			<li><span class="icon-heart myicon-right color-strong"></span> {{Lang::get('users.likes')}} <strong class="pull-right color-strong">{{ Helper::formatNumber( User::totalLikes( $user->id ) ) }}</strong></li>
			<li><span class="fa fa-comment myicon-right color-strong"></span> {{ Lang::get('misc.comments') }} <strong class="pull-right color-strong">{{ number_format( Comments::where( 'user_id',$user->id  )->count() ) }}</strong></li>
		</ol>


</div><!-- /End col md-4 -->

@stop

@section('javascript')

  <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo Config::get('config.googleapikey') ?>&amp;libraries=places"></script>
  {{ HTML::script('public/js/jquery.geocomplete.js') }}
  {{ HTML::script('public/js/bootstrap-tokenfield.js') }}

<script type="text/javascript">

$('#status-{{$user->status}}').attr({'selected':'selected'});
$('#role-{{$user->role}}').attr({'selected':'selected'});
$('#type_account-{{$user->type_account}}').attr({'selected':'selected'});

$('#skills').on('tokenfield:createdtoken', function (e) {
	    var re = /^[a-z0-9][\w\s]+$/i
	    var valid = re.test(e.attrs.value)
	    if (!valid) {
	      $(e.relatedTarget).addClass('invalid').remove()
	    }
	  }).tokenfield({
		limit: 10,
		beautify: false
	});

//** Changes Form **//
function changesForm () {
var button = $('#saveUpdate');
$('form.form-account input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form.form-account input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});

    $('form.form-account input, select, textarea, checked').each(function () {
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


	$("#bio").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

	$(function(){
        $("#location").geocomplete()
        .bind("geocode:result", function(event, result){
          });
        });

         //<<<---------- Delete Account
  $(".delete-account").click(function(e) {

   	e.preventDefault();

   	var element = $(this);
	var url     = element.attr('data-url');

   	bootbox.confirm("{{ Lang::get('admin.delete_user_confirm') }}", function(r) {

   		if( r == true ) {

   			window.location.href = url;

	 }//END IF R TRUE

	  }); //Jconfirm

   });

</script>
@stop

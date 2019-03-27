@extends('layouts.master')

@section('title'){{ Lang::get('admin.general_settings') }} - @stop

@section('css_style')
{{ HTML::style('public/css/bootstrap-tokenfield.css') }}
@stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Data User logged ** //
     $user = Auth::user();
	 
	 //echo Config::get('config.title_site');
	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<span class="btn-block">
		  		{{ Lang::get('admin.general_settings') }}
		  	</span><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
				  
			<form class="form-horizontal form-account" method="post" role="form" action="{{ URL::to('panel/admin') }}">
			 
			 @if (Session::has('notification'))
			<div class="alert alert-success btn-sm" role="alert">
				<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            		{{ Session::get('notification') }}
            		</div>
            	@endif
            	
			 <div class="form-group @if( $errors->first('name_site') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.name_site') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e( $settings->title ) }}" name="name_site" class="form-control input-sm" id="name_site" placeholder="{{ Lang::get('admin.name_site') }}">
			
			@if( $errors->first("name_site") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("name_site")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
		
		<div class="form-group @if( $errors->first('welcome_text') ) has-error @endif" style="display: none;">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.welcome_text') }}</label>
			    <div class="col-sm-10">
			      <input type="hidden" value="{{ $settings->welcome_text }}" name="welcome_text" class="form-control input-sm" id="welcome_text" placeholder="{{ Lang::get('admin.welcome_text') }}">
			    
			    @if( $errors->first("welcome_text") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("welcome_text")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
		<div class="form-group @if( $errors->first('welcome_subtitle') ) has-error @endif" style="display: none;">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.welcome_subtitle') }}</label>
			    <div class="col-sm-10">
			      <input type="hidden" value="{{ $settings->welcome_subtitle }}" name="welcome_subtitle" class="form-control input-sm" id="welcome_subtitle" placeholder="{{ Lang::get('admin.welcome_subtitle') }}">
			    
			    @if( $errors->first("welcome_subtitle") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("welcome_subtitle")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
		<div class="form-group @if( $errors->first('keywords') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.keywords') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->keywords }}" name="keywords" class="form-control input-sm" id="keywords" placeholder="{{ Lang::get('admin.keywords') }}">
			    
			    @if( $errors->first("keywords") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("keywords")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  			  
			  <div class="form-group @if( $errors->first('message_length') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.message_length') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->message_length }}" name="message_length" class="form-control input-sm" id="message_length" placeholder="{{ Lang::get('admin.message_length') }}">
			    
			    @if( $errors->first("message_length") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("message_length")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('comment_length') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.comment_length') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->comment_length }}" name="comment_length" class="form-control input-sm" id="comment_length" placeholder="{{ Lang::get('admin.comment_length') }}">
			    
			    @if( $errors->first("comment_length") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("comment_length")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('shot_length_description') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.shot_length_description') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->shot_length_description }}" name="shot_length_description" class="form-control input-sm" id="shot_length_description" placeholder="{{ Lang::get('admin.shot_length_description') }}">
			    
			    @if( $errors->first("shot_length_description") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("shot_length_description")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			   <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.new_registrations') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="new_registrations" name="new_registrations" class="input-sm btn-block">
					  	<option id="register1" value="1">On</option>
					  	<option id="register0" value="0">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.email_verification') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="email_verification" name="email_verification" class="input-sm btn-block">
					  	<option id="email1" value="1">On</option>
					  	<option id="email0" value="0">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">Captcha</label>
			    <div class="col-sm-10">
			    	
			    	<select id="captcha" name="captcha" class="input-sm btn-block">
					  	<option id="captchaon" value="on">On</option>
					  	<option id="captchaoff" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  <div class="form-group @if( $errors->first('file_support_attach') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.file_support_attach') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $settings->file_support_attach }}" name="file_support_attach" class="form-control input-sm" id="file_support_attach" placeholder="{{ Lang::get('admin.file_support_attach') }}">
			    
			    @if( $errors->first("file_support_attach") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("file_support_attach")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.file_size_allowed') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="file_size_allowed" name="file_size_allowed" class="input-sm btn-block">
					  	<option id="size_1048576" value="1048576">1MB</option>
					  	<option id="size_2097152" value="2097152">2MB</option>
					  	<option id="size_3145728" value="3145728">3MB</option>
					  	<option id="size_5242880" value="5242880">5MB</option>
					  	<option id="size_10485760" value="10485760">10MB</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->

			
			<div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.result_request_shots') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="result_request_shots" name="result_request_shots" class="input-sm btn-block">
					  	<option id="result_request_shots_12" value="12">12</option>
					  	<option id="result_request_shots_24" value="24">24</option>
					  	<option id="result_request_shots_36" value="36">36</option>
					  	<option id="result_request_shots_48" value="48">48</option>
					  	<option id="result_request_shots_60" value="60">60</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			   
			    
			   <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.limit_team_members') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="limit_team_members" name="limit_team_members" class="input-sm btn-block">
					  	<option id="limit_team_members-5" value="5">5</option>
					  	<option id="limit_team_members-10" value="10">10</option>
					  	<option id="limit_team_members-15" value="15">15</option>
					  	<option id="limit_team_members-20" value="20">20</option>
					  	<option id="limit_team_members-30" value="30">30</option>
					  	<option id="limit_team_members-40" value="40">40</option>
					  	<option id="limit_team_members-50" value="50">50</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.invitations_by_email')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="invitations_by_email" name="invitations_by_email" class="input-sm btn-block">
					  	<option id="invitations_by_email-on" value="on">On</option>
					  	<option id="invitations_by_email-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			   <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.allow_attachments')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="allow_attachments" name="allow_attachments" class="input-sm btn-block">
					  	<option id="allow_attachments-on" value="on">On</option>
					  	<option id="allow_attachments-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			   <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.allow_attachments_msg')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="allow_attachments_msg" name="allow_attachments_msg" class="input-sm btn-block">
					  	<option id="allow_attachments_msg-on" value="on">On</option>
					  	<option id="allow_attachments_msg-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.teams_free')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="teams_free" name="teams_free" class="input-sm btn-block">
					  	<option id="teams_free-on" value="on">On</option>
					  	<option id="teams_free-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.allow_ads')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="allow_ads" name="allow_ads" class="input-sm btn-block">
					  	<option id="allow_ads-on" value="on">On</option>
					  	<option id="allow_ads-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.allow_jobs')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="allow_jobs" name="allow_jobs" class="input-sm btn-block">
					  	<option id="allow_jobs-on" value="on">On</option>
					  	<option id="allow_jobs-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.pro_users_default')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="pro_users_default" name="pro_users_default" class="input-sm btn-block">
					  	<option id="pro_users_default-on" value="on">On</option>
					  	<option id="pro_users_default-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{Lang::get('admin.user_no_pro_comment_on')}}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="user_no_pro_comment_on" name="user_no_pro_comment_on" class="input-sm btn-block">
					  	<option id="user_no_pro_comment_on-on" value="on">On</option>
					  	<option id="user_no_pro_comment_on-off" value="off">Off</option>
					</select>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  
			  <div class="form-group @if( $errors->first('description') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('admin.description') }}</label>
			    <div class="col-sm-10">
			      <textarea name="description" rows="4" id="description" class="form-control input-sm textarea-textx">{{ e( $settings->description ) }}</textarea>
			    
			    @if( $errors->first("description") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("description")}}</strong>
	     	</div><!-- Error -->
	     	@endif
			    
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button type="submit" class="btn btn-info btn-sm btn-sort" id="saveUpdate" disabled="disabled">{{ Lang::get('misc.save_changes') }}</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
				  
		</div><!-- Panel Body -->

   </div><!-- Panel Default -->

			
 </div>
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('admin.sidebar_admin')
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')

  {{ HTML::script('public/js/bootstrap-tokenfield.js') }}
  
<script type="text/javascript">

$('#register{{$settings->registration_active}}').attr({'selected':'selected'});
$('#email{{$settings->email_verification}}').attr({'selected':'selected'});
$('#captcha{{$settings->captcha}}').attr({'selected':'selected'});
$('#result_request_shots_{{$settings->result_request}}').attr({'selected':'selected'});
$('#size_{{$settings->file_size_allowed}}').attr({'selected':'selected'});
$('#limit_team_members-{{$settings->members_limit}}').attr({'selected':'selected'});
$('#invitations_by_email-{{$settings->invitations_by_email}}').attr({'selected':'selected'});

$('#allow_attachments-{{$settings->allow_attachments}}').attr({'selected':'selected'});
$('#allow_attachments_msg-{{$settings->allow_attachments_messages}}').attr({'selected':'selected'});


$('#teams_free-{{$settings->team_free}}').attr({'selected':'selected'});
$('#allow_ads-{{$settings->allow_ads}}').attr({'selected':'selected'});
$('#allow_jobs-{{$settings->allow_jobs}}').attr({'selected':'selected'});
$('#pro_users_default-{{$settings->pro_users_default}}').attr({'selected':'selected'});
$('#user_no_pro_comment_on-{{$settings->user_no_pro_comment_on}}').attr({'selected':'selected'});




$('#keywords, #file_support_attach').on('tokenfield:createdtoken', function (event) {
	    
        var re = /^[a-z0-9][\w\s]+$/i
	    var valid = re.test(event.attrs.value)
	    if (!valid) {
	      $(event.relatedTarget).addClass('invalid').remove()
	    }
	    
	/*var existingTokens = $(this).tokenfield('getTokens');
    $.each(existingTokens, function(index, token) {
        if (token.value === event.attrs.value);
            event.preventDefault();
            
            });*/
            
	  }).tokenfield({
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

</script>
@stop

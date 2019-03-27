<!-- Invitations -->
<div class="panel panel-default">
	<span class="panel-heading btn-block grid-panel-title" href="about">
		<span class="icon-users2 myicon-right"></span> {{ Lang::get('misc.invite_friends') }}
		</span>
		
	<div class="panel-body">
		<!-- Form -->
		<form action="{{URL::to('ajax/invite')}}" role="form" method="post" id="sendInvitation">
			<div class="form-group">
		 	<input type="text" class="form-control input-sm" name="email" id="email" placeholder="{{ Lang::get('misc.email_of_your_friend') }}">
		 </div><!-- form-group -->
	
		 <div class="form-group">
		 	<div class="alert alert-danger btn-sm text-left col-thumb" role="alert" id="errors" style="display:none;"></div>
		 	<div class="alert alert-success btn-sm text-left col-thumb" role="alert" id="success_invite" style="display:none;"></div>
		 	<button type="submit" id="invite_friends" class="btn btn-inverse btn-sm btn-block pull-right">{{ Lang::get('misc.send_invitation') }}</button>
		 </div><!-- form-group -->
		</form><!-- Form -->
		
	</div><!-- Panel Body -->
</div><!-- Invitations -->
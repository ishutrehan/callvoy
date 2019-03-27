<?php 
/*----------------------------------------------
 *  SHOW NUMBER NOTIFICATIONS IN BROWSER ( 1 )
 * --------------------------------------------
 */
 if( Auth::check() ) {
 	
	// Notifications	
	$notifications_count = Notifications::where('destination',Auth::user()->id)->where('status',0)->count();
	
	// Messages	
	$messages_count = Messages::where('to_user_id',Auth::user()->id)->where('status','new')->count();
	
	if( $messages_count != 0 &&  $notifications_count != 0 ) {
		$totalNotifications = '('.( $messages_count + $notifications_count ).') ';
		$totalNotify = ( $messages_count + $notifications_count );
	} else if( $messages_count == 0 &&  $notifications_count != 0  ) {
		$totalNotifications = '('.$notifications_count.') ';
		$totalNotify = $notifications_count;
	} else if ( $messages_count != 0 &&  $notifications_count == 0 ) {
		$totalNotifications = '('.$messages_count.') ';
		$totalNotify = $messages_count;
	} else {
		$totalNotifications = null;
		$totalNotify = null;
	}
 } else {
 	$totalNotifications = null;
	$totalNotify = null;
 }
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php $settings = AdminSettings::first(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="@yield('description_custom'){{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
    <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />

    <title>{{$totalNotifications}}@section('title')@show @if( isset( $settings->title ) ){{$settings->title}}@endif</title>

    @include('includes.css_general')

 @if( Auth::check() )
<script type="text/javascript">
//<----- Notifications
function Notifications() {	
	 
	 var _title = '@section("title")@show {{e($settings->title)}}';
	 
	 console.time('cache');
	 
	 $.get(URL_BASE+"/ajax/notifications", function( data ) {	
		if ( data ) {
			//* Messages */
			if( data.messages != 0 ) {
				
				var totalMsg = data.messages;
				
				$('#noti_msg').html(data.messages).fadeIn();
			} else {
				$('#noti_msg').fadeOut().html('');
				
				if(  data.notifications == 0 ) {
					 $('title').html( _title );
				}
			}
			
			//* Notifications */
			if( data.notifications != 0 ) {
				
				var totalNoty = data.notifications;
				$('#noti_connect').html(data.notifications).fadeIn();
			} else {
				$('#noti_connect').fadeOut().html('');
			}
			
			//* Error */
			if( data.error == 1 ) {
				window.location.reload();
			}
			
			var totalGlobal = parseInt( totalMsg ) + parseInt( totalNoty );
			
			if( data.notifications == 0 && data.messages == 0 ) {
				$('.notify').hide();
			}
		
		if( data.notifications != 0 && data.messages != 0 ) {
		    $('title').html( "("+ totalGlobal + ") " + _title );
		  } else if( data.notifications != 0 && data.messages == 0 ) {
		    $('title').html( "("+ data.notifications + ") " + _title );
		  } else if( data.notifications == 0 && data.messages != 0 ) {
		    $('title').html( "("+ data.messages + ") " + _title );
		  } 
		
		}//<-- DATA
	     	
		},'json');
		
		console.timeEnd('cache'); 
}//End Function TimeLine
	
timer = setInterval("Notifications()", 10000);
</script>
@endif


	@yield('css_style')

  </head>

  <body>
  	
  	<!-- SEE FULL IMAGE REAL PIXELS -->
  	<div class="wrap-full-image">
  		<div class="btn-block details-full-image">
  		  <span id="titleShotFull" class="none-overflow"></span> <span class="icon-close" id="closeFull"></span>
  		</div>
  		<div class="container-image-full"></div>
  	</div><!-- SEE FULL IMAGE REAL PIXELS -->
     
     @if( Auth::check() )
     
     @if ( !filter_var( Auth::user()->email, FILTER_VALIDATE_EMAIL ) )
     <!-- ***** Modal Mail ****** -->
	<div class="modal fade" id="myModalMail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h5 class="modal-title text-left" id="myModalLabel">{{ Lang::get('misc.email_valid') }}</h5>
	      </div>
	      <div class="modal-body">
	      	
	     <form action="{{ URL::to('/') }}/ajax/updatemail" method="POST" accept-charset="UTF-8" id="updateEmail" enctype="multipart/form-data">
	       <input class="form-control" value="{{Auth::user()->email}}" name="email" id="email" />
	      </div><!-- modal-body -->
					      
				<div class="modal-footer">
			       <div class="alert alert-danger btn-sm text-left col-thumb" role="alert" id="errors" style="display:none;"></div>
			        	<button type="submit" id="button_update_mail" class="btn btn-info btn-sm btn-sort pull-left">{{ Lang::get('auth.send') }}</button>
					      </div><!-- modal-footer" -->
					     </form>
					    </div>
					  </div>
	</div> <!-- ***** Modal Mail ****** -->
	@endif
					
     	<div class="modal fade" id="listModal" tabindex="-1" role="dialog" aria-hidden="true">
     		<div class="modal-dialog modal-sm">
     			<div class="modal-content"> 
     				<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				        <h4 class="modal-title text-center" id="myModalLabel">
				        	{{ Lang::get('users.your_lists') }}
				        	</h4>
				     </div><!-- Modal header -->
				     
				      <div class="modal-body listWrap">
				      	<div class="form-group form-li display-none" id="listsContainer"></div><!-- form-group -->
    					
    		
    		<div class="btn-block display-none add-lists">
    			
    			<button type="button" data-toggle="modal" data-target="#addListModal" class="btn btn-sm btn-success bt-add-list">
						<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('users.create_list') }}
				</button>
				
				<button type="button" style="display: none;" id="done" data-dismiss="modal" class="btn btn-sm btn-danger bt-add-list">
						{{ Lang::get('users.done') }}
				</button>
				
    		</div><!-- btn-block -->
    					
				      </div><!-- Modal body -->
     				</div><!-- Modal content -->
     			</div><!-- Modal dialog -->
     		</div><!-- Modal -->
     		
     		
     <!-- ***** Modal Create List ****** -->
	<div class="modal fade" id="addListModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title text-left" id="myModalLabel"><strong>{{ Lang::get('users.create_list') }}</strong></h4>
	      </div>
	      
	      <div class="modal-body">
	      	
	     <form class="form-horizontal" id="form_add_list" method="post" role="form" action="">
			 <input type="hidden" name="user_id" value="" id="user_id_data" />
			 <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('users.name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="" name="name" class="form-control input-sm" id="title" placeholder="{{ Lang::get('users.name') }}">
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			
			 <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.type') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="project" name="type" class="input-sm btn-block">
					  <option value="1">{{ Lang::get('misc.public') }}</option>
					  	<option value="0">{{ Lang::get('misc.private') }}</option>
					</select>

			    </div>
			  </div><!-- **** form-group **** -->
			    
			  <div class="form-group">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.description') }} ({{ Lang::get('misc.optional') }})</label>
			    <div class="col-sm-10">
			      <textarea name="description" rows="4" id="_description" class="form-control input-sm textarea-textx"></textarea>
			    
			    <div class="alert alert-danger btn-sm text-left col-thumb" role="alert" id="error-show-msg" style="display:none; margin-top: 10px;"></div>
			    
			    </div>
			    
			  </div><!-- **** form-group **** -->
			  			  
			  <div class="form-group">
			    <div class="col-sm-offset-2 col-sm-10">
			      <button style="padding: 9px 30px;" type="submit" class="btn btn-info btn-sm btn-sort" id="send_list">
			      	{{ Lang::get('users.create') }}
			      	</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
			    
	      </div><!-- modal-body -->
	    </div>
	  </div>
	</div> <!-- ***** Modal ****** -->
     @endif
     
     <div class="popout font-default"></div>
     
     @include('includes.navbar')
    
     @yield('jumbotron')

<!-- Start CONTAINER -->
<div class="container wrap-ui">
	<!-- ROW -->
	<div class="row">
    	     
   @yield('content')
        	
    	@yield('sidebar')
    	
</div><!-- /Row -->
      
    </div> <!-- /container -->
    
    @include('includes.footer')
    
    
    @include('includes.javascript_general')
    
    @yield('javascript')
    
<script type="text/javascript">
@if ( Auth::check() && !filter_var( Auth::user()->email, FILTER_VALIDATE_EMAIL ) )
	$('#myModalMail').modal('show');
	@endif
    
    bootbox.setDefaults({
  /**
   * @optional String
   * @default: en
   * which locale settings to use to translate the three
   * standard button labels: OK, CONFIRM, CANCEL
   */
  locale: "en"
  
});

    $("input[type=radio], input[type=checkbox]").picker();
    // Tooltip
    $('.shotTooltip').tooltip();
 
  @if( Auth::check() )   
    $("#_description").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });
    @endif
    </script>
    
  </body>
</html>
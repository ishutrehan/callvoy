@extends('layouts.master')

<?php 
	
	// ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$trueProfile = true; 

?>

@section('title'){{ e( $project->title ).' '.Lang::get('users.projects') }} - {{ e( $user->name ) }} - @stop

 @include('includes.cover-static')

@section('content') 


@if( Auth::check() && Auth::user()->id == $user->id && Auth::user()->type_account != 1 )
<!-- ***** Modal ****** -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title text-left" id="myModalLabel">{{ Lang::get('users.edit') }}  <strong>| {{ e( $project->title ) }}</strong></h4>
	      </div>
	      
	      <div class="modal-body">
	      	
	     <form class="form-horizontal" id="form-edit-shot" method="post" role="form" action="{{ URL::to('/') }}/edit/project">
			 
			 <input type="hidden" value="{{ $project->id }}" name="id" />
			 
			 <div class="form-group @if( $errors->first('title') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.title') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ $project->title }}" name="title" class="form-control input-sm" id="title" placeholder="{{ Lang::get('misc.title') }}">
	     			
	     		@if( $errors->first("title") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("title")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			  
			  <div class="form-group @if( $errors->first('description') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.description') }} ({{ Lang::get('misc.optional') }})</label>
			    <div class="col-sm-10">
			      <textarea name="description" rows="4" id="description" class="form-control input-sm textarea-textx">{{ $project->about }}</textarea>
	             
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
			      <button disabled="disabled" style="padding: 9px 30px;" type="submit" class="btn btn-info btn-sm btn-sort" id="updateProject">
			      	{{ Lang::get('misc.save_changes') }}
			      	</button>
			    </div>
			  </div><!-- **** form-group **** -->
			  
			</form><!-- **** form **** -->
			    
	      </div><!-- modal-body -->
	    </div>
	  </div>
	</div> <!-- ***** Modal ****** -->
	@endif

<!-- container -->  
    <div class="container">
    <!-- row -->  
    <div class="row">
	      <!-- col-md -->  
	      <div class="col-md-12">
	      	<!-- Start Panel -->
      	<div class="panel panel-default margin-zero panel-user">

    				<div>
    					
    					<ul class="list-grid">
    						
    						<h1 class="text-center none-overflow">{{ e( $project->title ) }}</h1>
    						
    						<h3 class="btn-block text-center" style="color: #888;">{{ Lang::get('misc.by') }} <a class="text-decoration-none" href="{{ URL::to('/') }}/{{ '@'.$user->username }}/projects">{{e( $user->name )}}</a></h3>
    									                   
			                   @if( $project->about != '' )
			                   <h4 class="text-center bio-user none-overflow">{{ e($project->about) }}</h4>
			                   @endif
			                   
							<p class="btn-block text-center">
								{{ $project->shots()->count() }} {{ Lang::choice('misc.shots_plural',$project->shots()->count()) }} 
								
							@if( $project->shots()->count() > 0 )
							// {{ Lang::get('misc.created') }} <span class="timeAgo" data="{{ date('c',strtotime( $project->created_at )) }}" ></span>
							 //	{{ Lang::get('misc.updated') }} <span class="timeAgo" data="{{ date('c',strtotime( $project->shots{0}->created_project )) }}" ></span>
							@endif
							</p>
							
			@if( Auth::check() && Auth::user()->id == $user->id && Auth::user()->type_account != 1 )
							<span class="btn-block text-center">
								
								<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success btn-padding">
									<i class="icon-pencil2 myicon-right myicon-right"></i> {{ Lang::get('users.edit') }}
									</button>
									
	<a href="javascript:void(0);" data-id="{{ $project->id }}" class="btn btn-sm btn-danger delete-shot btn-padding">
   		<i class="glyphicon glyphicon-remove myicon-right"></i> {{ Lang::get('misc.delete') }}
   		</a>
							</span>
							
							@endif
    						
    						<hr />
    					</ul>
    				</div><!-- Panel Body -->
    			</div><!--./ Panel Default -->
	      </div><!-- col-md -->  
	   </div><!-- row -->  
	 </div><!-- container -->
	 
<div class="row">
	 	
	@foreach( $data as $key )
	
	<?php
	
	if( Str::slug( $key->title ) == '' ) {
	
		$slug  = '';
	} else {
		$slug  = '-'.Str::slug( $key->title );
	}
	
	$urlShot = URL::to('/').'/shots/'.$key->id.$slug;
	 ?>
	 
	<div class="col-md-12 border-style">
	 	<div class="col-md-8">
	 		<div class="panel panel-default">
			  <div class="panel-body">
			  	<a href="{{$urlShot}}">
			  		<img src="{{ URL::asset('public/shots_img/large').'/'.$key->large_image }}" class="img-responsive btn-block">
			  	</a>
			    
			  </div>
			</div>
		</div><!-- col-md --> 
			
	 	<div class="col-md-4">
	 		<ol class="details-shot padding-top-zero">
			<li class="li-title">
				<h3>
					<a href="{{$urlShot}}" class="text-decoration-none">
					{{ e( $key->title ) }}
					</a>
				</h3>
				
				
				</li>
			<li>
				<p class="none-overflow">
					@if( $key->description != '' )
						{{ e( $key->description ) }}
					@endif
					
				<span class="btn-block">{{ Lang::get('misc.published') }} <span class="timeAgo" data="{{ date('c',strtotime( $key->date )) }}" ></span> </span>
				
				@if( $key->attachment != '' )
				<span class="btn-block">
					<strong><i class="icon-attachment myicon-right"></i> {{ Lang::get('misc.attachment') }}</strong>
				</span>
						<a target="_blank" href="{{URL::to('public/attachment_shots').'/'.$key->attachment}}">
							{{ $key->attachment }}
						</a>
					@endif
				</p>
				</li>
			</ol>
	 		</div><!-- col-md --> 
	 		
	 	</div><!-- col-md-12 --> 	
	 	
	 	@endforeach
	 
	 </div><!-- row -->
	 
	 <!-- Col MD -->
<div class="col-md-12">
@if( $data->getTotal() != $data->count() )
    	   <hr />
    	   
  <div class="btn-group paginator-style">
		   <?php echo $data->links(); ?> 
		</div>
@endif

	@if( $project->shots()->count() == 0 )
	<div class="btn-block text-center">
	    	<i class="icon-quill ico-no-result"></i>
	    </div>
		<h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('misc.project_no_shot') }} -
	    	</h3>
	@endif
</div><!-- /COL MD -->

@stop

@section('javascript')

<script type="text/javascript">

@if( Auth::check() && Auth::user()->id == $user->id )
//** Changes Form **//
function changesForm () {
var button = $('#updateProject');
$('form#form-edit-shot input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form#form-edit-shot input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});
    
    $('form#form-edit-shot input, select, textarea, checked').each(function () {
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
@endif

@if (Session::has('success_add'))
	 $('.popout').html("{{ Session::get('success_add')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
   @if( Session::has('error_add') )
   	$('#myModal').modal('show');
   @endif

$("#updateProject").on('click',function(){
    	$(this).css({'display': 'none'})
    });
    
	$("#description").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

//<<<---------- Delete Account      
  $(".delete-shot").click(function() {
   	
   	element = $(this);
   	var id  = element.attr("data-id");
   	var info= 'project_id=' + id;
   	
   	bootbox.confirm("{{ Lang::get('misc.delete_project') }}", function(r) {
   		if( r == true ) {
   			
   		$.ajax({
		   type: "POST",
		   url: "{{ URL::to('/') }}/delete/project",
		   dataType: 'json',
		   data: info,
		   success: function( data ) {
		   	
				if(data.success == true ){
					var location = "{{ URL::to('@') }}{{ $user->username }}/projects";
   					window.location.href = location;
				} else {
					bootbox.alert(data.error);
					window.location.reload();
				}

				}//<-- RESULT 
			});
	
	 		}//END IF R TRUE 
	  }); //Jconfirm  
   });
</script>
@stop

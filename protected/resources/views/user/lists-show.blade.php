@extends('layouts.app')

<?php 
	
	// ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$trueProfile = true; 

?>

@section('title'){{ Lang::get('users.list').' '.e($lists->name).' - ' }}{{e( $user->name )}} -@stop

 @include('includes.cover-static')

@section('content') 

@if( Auth::check() && Auth::user()->id == $user->id )
<!-- ***** Modal ****** -->
	<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title text-left" id="myModalLabel"><strong>{{ Lang::get('users.edit') }} | {{ Lang::get('users.list') }} {{e($lists->name)}}</strong></h4>
	      </div>
	      
	      <div class="modal-body">
	      	
	     <form class="form-horizontal" id="form-edit-shot" method="post" role="form" action="{{ URL::to('/') }}/lists/edit">
			 
			  <input type="hidden" value="{{ $lists->id }}" name="id" />
			
			 <div class="form-group @if( $errors->first('name') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('users.name') }}</label>
			    <div class="col-sm-10">
			      <input type="text" value="{{ e( $lists->name ) }}" name="name" class="form-control input-sm" id="title" placeholder="{{ Lang::get('users.name') }}">
	     			
	     		@if( $errors->first("name") )    	
			<div class="alert alert-danger btn-sm errors-account" role="alert">
	            	<button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	            		<strong>{{$errors->first("name")}}</strong>
	     	</div><!-- Error -->
	     	@endif
	     	
			    </div>
			  </div><!-- **** form-group **** -->
			 
			 <div class="form-group @if( $errors->first('project') ) has-error @endif">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('misc.type') }}</label>
			    <div class="col-sm-10">
			    	
			    	<select id="project" name="type" class="input-sm btn-block">
					  <option id="list1" value="1">{{ Lang::get('misc.public') }}</option>
					  	<option id="list0" value="0">{{ Lang::get('misc.private') }}</option>
					</select>

			    </div>
			  </div><!-- **** form-group **** -->
			   
			  <div class="form-group @if( $errors->first('description') ) has-error @endif">
			    <label class="col-sm-2 control-label input-sm">{{ Lang::get('misc.description') }} ({{ Lang::get('misc.optional') }})</label>
			    <div class="col-sm-10">
			      <textarea name="description" rows="4" id="description" class="form-control input-sm textarea-textx">{{ e( $lists->description ) }}</textarea>
	             
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
			      <button disabled="disabled" style="padding: 9px 30px;" type="submit" class="btn btn-info btn-sm btn-sort" id="updateLists">
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
	      <div class="col-xl-12">
	      	<!-- Start Panel -->
      	<div class="panel panel-default margin-zero panel-user">

    				<div>
    					
    					<ul class="list-grid">
    						
    						<h1 class="text-center none-overflow">{{ e( $lists->name ) }}</h1>
    						
			                   @if( $lists->description != '' )
			                   <h4 class="text-center bio-user">{{ e($lists->description) }}</h4>
			                   @endif
			        
			       
			        <ul class="list-inline btn-block text-center">
			        	@foreach( $lists->users()->take(5)->get() as $key )
			        	<li>
			        		<a href="{{URL::to('@')}}{{$key->user()->username}}" title="{{$key->user()->name}}" class="showTooltip" data-toggle="tooltip" data-placement="top">
			        			<img width="30" class="img-circle" height="30" src="{{URL::asset('public/avatar')}}/{{$key->user()->avatar}}" />
			        		</a>
			        		</li>
			        	@endforeach
			        </ul>
			                   
			         <p class="btn-block text-center">
			         	<?php
	
						if( Str::slug( $lists->name ) == '' ) {
						
							$slugUrl  = '';
						} else {
							$slugUrl  = '-'.Str::slug( $lists->name );
						}
						
						$slugUrlLists = URL::to('@').$lists->user()->username.'/lists/'.$lists->id.$slugUrl;
						 ?>
			         	<a href="{{$slugUrlLists}}/members">
			         		{{ $lists->users()->count() }} {{ Lang::choice('users.members',$lists->users()->count()) }}
			         	</a>
								 
							</p>

							
			@if( Auth::check() && Auth::user()->id == $user->id )
							<span class="btn-block text-center">
								
								<button type="button" data-toggle="modal" data-target="#myModal" class="btn btn-sm btn-success btn-padding">
									<i class="icon-pencil2 myicon-right myicon-right"></i> {{ Lang::get('users.edit') }}
									</button>
									
	<a href="javascript:void(0);" data-id="{{ $lists->id }}" class="btn btn-sm btn-danger delete-list btn-padding">
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

<div class="col-xl-12">		 
	 @if( $total != 0 )
	 	@include('includes.shots-lists')
	 @endif
	 </div>
	 
	 
	 @if(  $lists->users()->count() == 0 )
	<div class="col-md-12">	
		<div class="btn-block text-center">
	    	<i class="icon-user ico-no-result"></i>
	    </div>
	    
	 	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.list_no_members') }} -
	    	
	    	@if( Auth::check() && $user->id == Auth::user()->id )
	    		 <div class="btn-block text-center" style="margin-top: 20px;">
	   	<a href="{{URL::to('designers')}}" class="btn btn-sm btn-success">
			<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('misc.find_people') }}
			</a>
	   </div> 
	    	@endif
	    	</h3>	
	    	
	    	
	    	
	    	</div><!-- /COL MD -->
	@endif
	
	
	@if(  $total == 0 && $lists->users()->count() != 0 )
	<div class="col-md-12">	
		<div class="btn-block text-center">
	    	<i class="icon-list ico-no-result"></i>
	    </div>
	    
	 	<h3 class="margin-top-none text-center no-result user-no-result">
	    	- {{ Lang::get('users.list_no_shots') }} -
	    	</h3>	
	    	
	    	</div><!-- /COL MD -->
	@endif
	 


@stop

@section('javascript')
<script type="text/javascript">

@if( Auth::check() && Auth::user()->id == $user->id )

//** Changes Form **//
function changesForm () {
var button = $('#updateLists');
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

	$('#list{{$lists->type}}').attr({'selected':'selected'});
@endif

@if (Session::has('success_add'))
	 $('.popout').html("{{ Session::get('success_add')}}").fadeIn(500).delay(5000).fadeOut();
   @endif
   
   @if( Session::has('error_add') )
   	$('#myModal').modal('show');
   @endif

$("#updateLists").on('click',function(){
    	$(this).css({'display': 'none'})
    });
    
	$("#description").charCount({ allowed: {{ $settings->message_length }}, warning: 10, css: 'counterBio' });

//<<<---------- Delete Account      
  $(".delete-list").click(function() {
   	
   	element = $(this);
   	var id  = element.attr("data-id");
   	var info= 'lists_id=' + id;
   	
   	bootbox.confirm("{{ Lang::get('misc.delete_list') }}", function(r) {
   		if( r == true ) {
   			
   		$.ajax({
		   type: "POST",
		   url: "{{ URL::to('/') }}/lists/delete",
		   dataType: 'json',
		   data: info,
		   success: function( data ) {
		   	
				if(data.success == true ){
					var location = "{{ URL::to('@') }}{{ $user->username }}/lists";
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

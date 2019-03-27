@extends('layouts.master')

@section('title') {{ Lang::get('misc.comments') }} - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // All Comments
	 $_comments = Comments::orderBy('id','desc')->paginate(10);

     ?>
     
<div class="col-md-8">
     	
     	<h3>{{ Lang::get('misc.comments') }} ({{ $_comments->getTotal() }})</h3>
     	<hr />
     	
@foreach( $_comments as $comment)
   <div class="media media-comments position-relative" id="comment{{ $comment->id }}">
    		<span class="pull-left">
    			<a href="{{ URL::to('@') }}{{ $comment->user()->username }}">
    			<img width="50" height="50" class="media-object img-circle" src="{{ URL::asset('public/avatar').'/'.$comment->user()->avatar }}">
    		</a>
    		</span>
    		<div class="media-body media-body-comments">
    			<h4 class="media-heading col-thumb">
    				<a class="text-decoration-none" href="{{ URL::to('@') }}{{ $comment->user()->username }}">{{ $comment->user()->name }}</a>
    					</h4>
    			<p class="comments-p mentions-links">
    				{{ Helper::checkText( $comment->reply ) }}
    			</p>
    			
    			
    			<div class="btn-block">
    				<small class="timeAgo small-comment" data="{{ date('c', strtotime( $comment->date )) }}"></small> 
    			</div>
    			
    			<a href="javascript:void(0);" data-id="{{$comment->id}}" class="btn btn-xs btn-danger comment-delete">
   						<i class="glyphicon glyphicon-remove"></i> {{ Lang::get('misc.delete') }}
   					</a>
    		</div>
   </div><!-- media -->
   @endforeach
   
   @if( $_comments->getLastPage() > 1 && Input::get('page') <= $_comments->getLastPage() )
   <hr />
   <div class="btn-group paginator-style">
	        		<?php echo $_comments->links(); // $_comments->fragment('commentsGrid')->links() ?> 
	        	</div>
	        	@endif
	        	
	     @if( $_comments->count() == 0 )
   
    		<h3 class="margin-top-none text-center no-result row-margin-20">
	    	- {{ Lang::get('misc.no_comments_yet') }} -
	    	</h3>
	    	@endif
   			
 </div>
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('admin.sidebar_admin')
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
  
<script type="text/javascript">

// Comments Delete
$('.comment-delete').click(function(){
			element = $(this);
			element.removeClass('comment-delete');
			
			$.post("{{URL::to('/')}}/comment/deleteadmin", { comment_id: $(this).data('id') }, function(data){
				if(data.success == true ){
					url = '{{URL::to("/")."/panel/admin/comments"}}';
					window.location.reload();
				} else {
					bootbox.alert(data.error);
					window.location.reload();
				}
				
				if( data.session_null ) {
					window.location.reload();
				}
			},'json');
	  
});//<--- End Comments
		
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

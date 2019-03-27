@extends('layouts.master')

@section('title'){{ Lang::get('admin.manage_lists') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	
	$lists = Lists::orderBy('id','desc')->paginate(20);
 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('admin.manage_lists') }} ({{$lists->count()}})
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
@if( $lists->count() != 0 )	
		  				  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
          	<th class="active">ID</th>
          	<th class="active">{{ Lang::get('admin.user') }}</th>
            <th class="active">{{ Lang::get('admin.name') }}</th>
            <th class="active">{{ Lang::get('admin.type') }}</th>
            <th class="active">{{ Lang::get('admin.members') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $lists as $list )
        	
        	<?php
        	
        	switch ($list->type) {
				case 0:
					$type = Lang::get('misc.private');
					break;
				case 1:
					$type = Lang::get('misc.public');
					break;
			}
        	
        	 ?>
          <tr class="hoverTr">
          	<td>{{$list->id}}</td>
          	<td><a href="{{URL::to('@').$list->user()->username}}" target="_blank"><strong>{{e($list->user()->name)}}</strong></a></td>
          	<td><a href="{{URL::to('@').$list->user()->username.'/lists/'.$list->id}}" target="_blank"><strong>{{e($list->name)}}</strong></a></td>
          	<td>{{$type}}</td>
          	<td>{{$list->users()->count()}}</td>
          	<td><a data-url="{{URL::to('panel/admin/lists/delete')}}/{{$list->id}}" class="delete btn btn-danger btn-xs showTooltip padding-btn" data-toogle="tooltip" data-placement="top" href="#" title="{{ Lang::get('misc.delete') }}"><i class="icon-remove2"></i></a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div><!-- Responsive -->

@else 

<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('admin.no_lists') }} -
	    	</h3>

@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    @if( $lists->getLastPage() > 1 && Input::get('page') <= $lists->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $lists->links(); ?> 
	        	</div>
    		@endif

			
 </div><!-- /End col md-* -->
@stop

@section('sidebar')
<div class="col-md-4">
	
	@include('admin.sidebar_admin')
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
  
<script type="text/javascript">

 //<<<---------- Delete Account      
  $(".delete").click(function(e) {
   	e.preventDefault();
   	
   	var element = $(this);
	var url     = element.attr('data-url');
   	bootbox.confirm("{{ Lang::get('misc.delete_list') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop
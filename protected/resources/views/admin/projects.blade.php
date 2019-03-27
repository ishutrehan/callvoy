@extends('layouts.master')

@section('title'){{ Lang::get('admin.manage_projects') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	
	$projects = Projects::orderBy('id','desc')->paginate(20);
 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('admin.manage_projects') }} ({{$projects->count()}})
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
@if( $projects->count() != 0 )	
		  				  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
          	<th class="active">ID</th>
          	<th class="active">{{ Lang::get('admin.user') }}</th>
            <th class="active">{{ Lang::get('admin.name') }}</th>
            <th class="active">{{ Lang::get('misc.shots') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $projects as $project )
        	
          <tr class="hoverTr">
          	<td>{{$project->id}}</td>
          	<td><a href="{{URL::to('@').$project->user()->username}}" target="_blank"><strong>{{e($project->user()->name)}}</strong></a></td>
          	<td><a href="{{URL::to('@').$project->user()->username.'/projects/'.$project->id}}" target="_blank"><strong>{{e($project->title)}}</strong></a></td>
          	<td>{{$project->shots()->count()}}</td>
          	<td><a data-url="{{URL::to('panel/admin/projects/delete')}}/{{$project->id}}" class="delete btn btn-danger btn-xs showTooltip padding-btn" data-toogle="tooltip" data-placement="top" href="#" title="{{ Lang::get('misc.delete') }}"><i class="icon-remove2"></i></a></td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div><!-- Responsive -->

@else 

<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('admin.no_projects') }} -
	    	</h3>

@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    @if( $projects->getLastPage() > 1 && Input::get('page') <= $projects->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $projects->links(); ?> 
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
   	bootbox.confirm("{{ Lang::get('misc.delete_project') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop
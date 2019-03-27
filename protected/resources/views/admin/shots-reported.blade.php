@extends('layouts.master')

@section('title'){{ Lang::get('admin.shots_reported') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$shots = ShotsReported::orderBy('id','desc')->paginate(20);
	 	 	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('admin.shots_reported') }} ({{$shots->getTotal()}}) 
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
@if( $shots->count() != 0 )	
		  				  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
          	<th class="active">ID</th>
            <th class="active">{{ Lang::get('admin.report_by') }}</th>
            <th class="active">{{ Lang::get('admin.reported') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $shots as $shot )

          <tr class="hoverTr">
          	<td>{{$shot->id}}</td>
            <td><a href="{{URL::to('@').$shot->user()->username}}" target="_blank"><strong>{{e(Str::limit($shot->user()->name,25,'...'))}}</strong></a></td>
            <td><a href="{{URL::to('shots')}}/{{$shot->shots()->id}}" target="_blank"><strong>{{e(Str::limit($shot->shots()->title,25,'...'))}} <i class="glyphicon glyphicon-new-window"></i></strong></a></td>
            <td>
            	<ul class="padding-zero margin-zero">
            		<li>
            			<a data-url="{{URL::to('panel/admin/shots-reported/delete')}}/{{$shot->id}}" class="deletePage btn btn-danger btn-xs showTooltip padding-btn" data-toogle="tooltip" data-placement="top" href="#" title="{{ Lang::get('admin.delete_report') }}"><i class="icon-remove2"></i></a>
            		</li>
            	</ul>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div><!-- Responsive -->

@else 

<h3 class="margin-top-none text-center no-result color-no-result">
	    	- {{ Lang::get('admin.no_report') }} -
	    	</h3>

@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    @if( $shots->getLastPage() > 1 && Input::get('page') <= $shots->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $shots->links(); ?> 
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
  $(".deletePage").click(function(e) {
   	e.preventDefault();
   	
   	var element = $(this);
	var url     = element.attr('data-url');
   	bootbox.confirm("{{ Lang::get('admin.delete_report_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop

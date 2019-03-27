@extends('layouts.master')

@section('title'){{ Lang::get('admin.members_reported') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	$members = MembersReported::orderBy('id','desc')->paginate(20);
	 	 	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('admin.members_reported') }} ({{$members->getTotal()}}) 
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
@if( $members->count() != 0 )	
		  				  
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
        	@foreach( $members as $member )

          <tr class="hoverTr">
          	<td>{{$member->id}}</td>
            <td><a href="{{URL::to('@').$member->user()->username}}" target="_blank"><strong>{{e(Str::limit($member->user()->name,25,'...'))}}</strong></a></td>
            <td><a href="{{URL::to('panel/admin/members/edit')}}/{{$member->user_reported()->id}}"><strong>{{e(Str::limit($member->user_reported()->name,25,'...'))}}</strong></a></td>
            <td>
            	<ul class="padding-zero margin-zero">
            		<li>
            			<a data-url="{{URL::to('panel/admin/members-reported/delete')}}/{{$member->id}}" class="deletePage btn btn-danger btn-xs showTooltip padding-btn" data-toogle="tooltip" data-placement="top" href="#" title="{{ Lang::get('admin.delete_report') }}"><i class="icon-remove2"></i></a>
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
   
    @if( $members->getLastPage() > 1 && Input::get('page') <= $members->getLastPage() )
    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $members->links(); ?> 
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

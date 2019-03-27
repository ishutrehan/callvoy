@extends('layouts.master')

@section('title'){{ Lang::get('admin.pages') }} - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 $pages = Pages::orderBy('title','asc')->get();
	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('admin.pages') }}
		  		
		  		@if( $pages->count() != 0 )
			  		<a href="{{ URL::to('/') }}/panel/admin/pages/new" class="btn btn-xs btn-success no-shadow pull-right">
	        		<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('admin.add_page') }}
	        		</a>
	        	@endif
        		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
		  	
	@if( $pages->count() != 0 )			  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
            <th class="active">{{ Lang::get('misc.title') }}</th>
            <th class="active">{{ Lang::get('admin.slug') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $pages as $page )
          <tr class="hoverTr">
            <td>{{e(Str::limit($page->title,25,'...'))}}</td>
            <td>{{e(Str::limit($page->slug,25,'...'))}}</td>           
            <td>
            	<ul class="padding-zero margin-zero">
            		<li>
            			<a href="{{URL::to('panel/admin/pages/edit')}}/{{$page->id}}" title="{{ Lang::get('users.edit') }}" data-toogle="tooltip" data-placement="top" class="btn btn-success btn-xs showTooltip padding-btn"><i class="icon-pencil2"></i></a>
            			<a class="deletePage btn btn-danger btn-xs showTooltip padding-btn" data-toogle="tooltip" data-placement="top" href="{{URL::to('panel/admin/pages/delete')}}/{{$page->id}}" title="{{ Lang::get('misc.delete') }}"><i class="icon-remove2"></i></a>
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
	    	- {{ Lang::get('admin.no_pages') }} -
	    	</h3>
	    	
	    	<div class="btn-block text-center">
	    		<a href="{{ URL::to('/') }}/panel/admin/pages/new" class="btn btn-sm btn-success no-shadow">
        		<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('admin.add_page') }}
        		</a>
	    	</div>
@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->

			
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
	var url     = element.attr('href');
   	bootbox.confirm("{{ Lang::get('admin.delete_page_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop

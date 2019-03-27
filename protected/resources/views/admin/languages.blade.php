@extends('layouts.master')

@section('title'){{ Lang::get('misc.languages') }} - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 $langs = Languages::orderBy('name','asc')->get();
	 
	 $LangTotal = $langs->count();
	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('misc.languages') }}
		  		
			  		<a href="{{ URL::to('/') }}/panel/admin/languages/new" class="btn btn-xs btn-success no-shadow pull-right">
	        		<i class="glyphicon glyphicon-plus myicon-right"></i> {{ Lang::get('misc.add_new') }}
	        		</a>
        		
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
		  <div class="panel-body">
		  	
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
            <th class="active">{{ Lang::get('misc.title') }}</th>
            <th class="active">{{ Lang::get('misc.abbreviation') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $langs as $lang )
          <tr class="hoverTr">
 
            <td>{{e(Str::limit($lang->name,25,'...'))}}</td>
            <td>{{e($lang->abbreviation)}}</td>
            <td>
            	<ul class="padding-zero margin-zero">
            		<li>
            			<a href="{{URL::to('panel/admin/languages/edit')}}/{{$lang->id}}" title="{{ Lang::get('users.edit') }}" data-toogle="tooltip" data-placement="top" class="btn btn-success btn-xs showTooltip padding-btn"><i class="icon-pencil2"></i></a>
            			
            			@if( $LangTotal != 1 )
            			<a class="deletePage btn btn-danger btn-xs showTooltip padding-btn" data-toogle="tooltip" data-placement="top" href="{{URL::to('panel/admin/languages/delete')}}/{{$lang->id}}" title="{{ Lang::get('misc.delete') }}"><i class="icon-remove2"></i></a>
            			@endif
            		</li>
            	</ul>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
</div><!-- Responsive -->

				  
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
   	bootbox.confirm("{{ Lang::get('misc.delete_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop

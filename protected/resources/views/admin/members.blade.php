@extends('layouts.master')

@section('title'){{ Lang::get('admin.members') }}  - @stop

@section('content') 
     
     <?php 
     
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 $search = trim( Input::get('q') );
	 
	 if( $search != '' && strlen( $search ) > 2 ) {
	 	$members = User::where('name', 'LIKE', '%'.$search.'%')
		->where('id','!=',1)
		->where('id','!=',Auth::user()->id)
		->orWhere('username', 'LIKE', '%'.$search.'%')
		->where('id','!=',1)
		->where('id','!=',Auth::user()->id)
	 	->orderBy('id','desc')->paginate(20);
	 } else {
	 	$members = User::where('id','!=',1)->where('id','!=',Auth::user()->id)->orderBy('id','desc')->paginate(20);
	 }
	 	 	 
     ?>
     
<div class="col-md-8">
     	
     	 <!--********* panel panel-default ***************-->
     	<div class="panel panel-default">
		  <div class="panel-heading grid-panel-title">
		  	
		  	<div class="btn-block">
		  		{{ Lang::get('admin.members') }} ({{$members->getTotal()}}) 
		  	</div><!-- **btn-block ** -->
		  
		  </div><!-- ** panel-heading ** -->
		  
<div class="panel-body">
@if( $members->count() != 0 )	
	<form class="form-horizontal form-account" role="form" method="get" action="{{URL::to('panel/admin/members')}}">
            <!-- **** form-group **** -->
			 <div class="form-group">
			    <label for="inputEmail3" class="col-sm-2 control-label input-sm">{{ Lang::get('admin.search_members') }}</label>
			    
			    <div class="col-sm-10">
			      <input type="text" class="form-control input-sm" value="{{Input::get('q')}}" placeholder="{{ Lang::get('admin.search_members') }}" name="q" autocomplete="off" />
			    </div><!-- col-sm-10 -->
			  </div><!-- **** form-group **** -->
			</form><!-- **** form **** -->
		  				  
<div class="table-responsive">
	<table class="table table-bordered">
        <thead>
          <tr>
          	<th class="active">ID</th>
            <th class="active">{{ Lang::get('auth.full_name') }}</th>
            <th class="active">{{ Lang::get('auth.username') }}</th>
            <th class="active">{{ Lang::get('auth.status') }}</th>
            <th class="active">{{ Lang::get('misc.actions') }}</th>
          </tr>
        </thead>
        <tbody>
        	@foreach( $members as $member )

          <tr class="hoverTr">
          	<td>{{$member->id}}</td>
            <td><img src="{{URL::asset('public/avatar').'/'.$member->avatar}}" width="20" height="20" class="img-circle" /> <a href="{{URL::to('@').$member->username}}" target="_blank"><strong>{{e(Str::limit($member->name,25,'...'))}}</strong></a></td>
            <td>{{'@'.e(Str::limit($member->username,25,'...'))}}</td>
            <td>{{e(Str::title($member->status))}}</td>           
            <td>
            	<ul class="padding-zero margin-zero">
            		<li>
            			<a href="{{URL::to('panel/admin/members/edit')}}/{{$member->id}}" title="{{ Lang::get('users.edit') }}" data-toogle="tooltip" data-placement="top" class="btn btn-success btn-xs showTooltip padding-btn"><i class="icon-pencil2"></i></a>
            			<a class="deletePage btn btn-danger btn-xs showTooltip padding-btn" data-url="{{URL::to('panel/admin/members/delete')}}/{{$member->token}}" data-toogle="tooltip" data-placement="top" href="#" title="{{ Lang::get('misc.delete') }}"><i class="icon-remove2"></i></a>
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
	    	- {{ Lang::get('users.no_members_found') }} -
	    	</h3>

@endif
				  
</div><!-- Panel Body -->

   </div><!-- Panel Default -->
   
    @if( $members->getLastPage() > 1 && Input::get('page') <= $members->getLastPage() )
    		
    		@if( $search != '' && strlen( $search ) > 2 )
			<div class="btn-group paginator-style">
			   <?php echo $members->appends( array( 'q' => $search ) )->links(); ?> 
			</div>

		@else
	    		<div class="btn-group paginator-style">
	        		<?php echo $members->links(); ?> 
	        	</div>
	       @endif
	       {{-- Search --}}
	        	
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
   	bootbox.confirm("{{ Lang::get('admin.delete_user_confirm') }}", function(r) {
 
   		if( r == true ) {
   			
   			window.location.href = url;
	
	 }//END IF R TRUE 
	 
	  }); //Jconfirm  
   	
   });
</script>
@stop

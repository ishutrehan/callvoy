@extends('layouts.master')

@section('title'){{ Lang::get('misc.designers') }} -@stop

@section('content') 
     
     <?php 
     
     $page     = Input::get('page');
	 $sort     = Input::get('sort');
	 $hire     = Input::get('hire');
	 $location = Input::get('location');
	 $skills   = Input::get('skills');
	 
     // ** Admin Settings ** //
     $settings = AdminSettings::first();
	 
	 // ** Sort
	 if( $sort == 'latest' ) {
	 	$sortQuery = 'members.id';
	 } else {
	 	$sortQuery = 'COUNT(followers.id)';
	 }
	 	 
	 // ** Sql Query ** //
	 
	 if( isset($hire) && $hire == 'on' ) :
		 
		 $sql   = DB::table('members')
		 ->select(DB::raw('
		members.id,
		members.username,
		members.name,
		members.location,
		members.hire,
		members.avatar,
		members.status,
		members.type_account
		'))
		->leftjoin('followers', 'members.id', '=', DB::raw('followers.following AND followers.status = "1"'))
		->where('members.status', '=', 'active')
		->where( 'members.location','LIKE', '%'.$location.'%' )
		->where( 'members.skills','LIKE', '%'.$skills.'%' )
		->where('members.hire', '=',  1)
		->groupBy('members.id')
		->orderBy(DB::raw($sortQuery), 'DESC')
		->orderBy('members.id', 'ASC')
		->paginate( $settings->result_request ); //$settings->result_request
		
	 else :
		 
		 $sql   = DB::table('members')
		 ->select(DB::raw('
		members.id,
		members.username,
		members.name,
		members.location,
		members.hire,
		members.avatar,
		members.status,
		members.type_account
		'))
		->leftjoin('followers', 'members.id', '=', DB::raw('followers.following AND followers.status = "1"'))
		->where('members.status', '=', 'active')
		->where( 'members.location','LIKE', '%'.$location.'%' )
		->where( 'members.skills','LIKE', '%'.$skills.'%' )
		->groupBy('members.id')
		->orderBy(DB::raw($sortQuery), 'DESC')
		->orderBy('members.id', 'ASC')
		->paginate( $settings->result_request ); //$settings->result_request
		 
	 endif;
	 
	 //<<---- * Total Search * ------->
	 $totalSearch = $sql->getTotal();
	 
     ?>
     	<!-- Col MD -->
<div class="col-md-8">	
	

<h1 class="title-item none-overflow">
@if( isset( $sort) && $totalSearch != 0 && $sql->count() != 0 || isset( $skills) && $totalSearch != 0 && $sql->count() != 0 )	
	<a href="{{ URL::to('/') }}/designers" class="link-small">
        		{{ Lang::get('misc.all') }}  <i class="fa fa-angle-right myicon-right"></i>
        		</a>
        		@endif
	{{ Lang::get('misc.designers') }} 
	@if( isset( $sort) && $totalSearch != 0 && $sql->count() != 0 || isset( $skills) && $totalSearch != 0 && $sql->count() != 0 ) <small class="font-normal"><strong>- {{ number_format( $totalSearch ) }}</strong> {{ Lang::choice('misc.results_for_your_search',$totalSearch) }}</small> @endif</h1> 

<hr />
     
     @foreach( $sql as $designer )	
     
     
     	@include('includes.users-list')
    		
    		@endforeach
    		
    		@if( $sql->count() == 0 )
    	<div class="btn-block text-center">
	    	<i class="icon-users ico-no-result"></i>
	    </div>
    		<h3 class="margin-top-none text-center no-result">
	    	- {{ Lang::get('users.no_members_found') }} -
	    	</h3>
	    	@endif
	    	
	    	
    		@if( $sql->getLastPage() > 1 && $page <= $sql->getLastPage() )
    		
	    		@if( isset($sort) )
	    		<div class="btn-group paginator-style">	
	    			<?php echo $sql->appends( 
			    			array( 
			    			'sort' => $sort,
			    			'hire' => $hire,
			    			'location' => $location,
			    			'skills' => $skills 
							) 
					)->links(); ?> 
	    		</div>
	    		@else
	    		
	    		<div class="btn-group paginator-style">
	        		<?php echo $sql->links(); ?> 
	        	</div>
	        	
	    		@endif
    		
    		@endif
    		
 </div><!-- /COL MD -->
@stop

@section('sidebar')
<div class="col-md-4">

@if( Auth::check() && Auth::user()->type_account == 2 || Auth::check() && Auth::user()->type_account == 3 )
<a class="btn btn-danger btn-lg btn-block shadow-inset col-thumb" href="{{ URL::to('upload') }}">
		<i class="glyphicon glyphicon-cloud-upload myicon-right"></i> {{ Lang::get('misc.upload') }}
		</a>
		@endif
	
	@if( Auth::check() && $settings->invitations_by_email == 'on' )	
		@include('includes.invite-friends')
	@endif
		
		
<div class="panel panel-default">
    				<span class="panel-heading btn-block grid-panel-title">
    					<i class="icon-filter2 myicon-right"></i> {{ Lang::get('misc.sort_designers') }}
    					</span>
    					
    				<div class="panel-body">
    					
    				<!-- Form -->
    				<form role="search" id="formSortDesigners" action="{{ URL::to('designers') }}" method="get">
    					
    					<dl class="margin-zero">
    					
    					<div class="form-group">		
    						
    						<dd>
    							<label class="radio-inline">
    								<input <?php if( isset($sort) && $sort == 'popular' || !isset( $sort ) ): echo 'checked="checked"'; endif ?> id="popular_sort" class="no-show " name="sort" type="radio" value="popular">
    								<span class="input-sm">{{ Lang::get('misc.popular') }}</span>
    							</label>
    							
    							<label class="radio-inline">
    								<input <?php if( isset($sort) && $sort == 'latest'): echo 'checked="checked"'; endif ?> class="no-show" id="latest_sort" name="sort" type="radio" value="latest">
    								<span class="input-sm">{{ Lang::get('misc.latest') }}</span>
    							</label>
    							
    							<label class="checkbox-inline">
    								<input <?php if( isset($hire) && $hire == 'on'): echo 'checked="checked"'; endif ?> class="no-show" name="hire" type="checkbox" value="on">
    								<span class="input-sm">{{ Lang::get('misc.available_for_hire') }}</span>
    							</label>
    							
    						</dd>
    					</div><!-- form-group -->
    					
    					
    					<div class="form-group">		
    						<dt><i class="icon-location myicon-right"></i> {{ Lang::get('misc.location') }}</dt>
    						<dd>
    							<input type="text" class="form-control input-sm" value="{{ e(Input::get('location')) }}" name="location" placeholder="{{ Lang::get('misc.placeholder_location') }}">
    						</dd>
    					</div><!-- form-group -->
    					
    					<div class="form-group">		
    						<dt><i class="icon-wrench  myicon-right"></i> {{ Lang::get('misc.skills') }}</dt>
    						<dd>
    							<input type="text" class="form-control input-sm" value="{{ e(Input::get('skills')) }}" name="skills" placeholder="{{ Lang::get('misc.any') }}">
    						</dd>
    					</div><!-- form-group -->

   
    					</dl><!-- Margin Zero DL -->

    					 <div class="form-group">
    					 	<button type="submit" disabled="disabled" id="searchBtn" value="search" class="btn btn-info btn-sm btn-block btn-sort">
    					 		{{ Lang::get('misc.find') }} <span class="glyphicon glyphicon-arrow-right"></span>
    					 		</button>
    					 </div><!-- form-group -->
    					
    					 
    					</form><!-- Form -->
    					
    				</div><!-- Panel Body -->
    			</div>

		@include('includes.ads')
          
          
          
</div><!-- /End col md-4 -->

@stop

@section('javascript')
<script type="text/javascript">

//** Changes Form **//
function changesForm () {
var button = $('#searchBtn');
$('form#formSortDesigners input, select, textarea, checked').each(function () {
    $(this).data('val', $(this).val());
    $(this).data('checked', $(this).is(':checked'));
});

$('form#formSortDesigners input, select, textarea, checked').bind('keyup change blur', function(){
    var changed  = false;
    var ifChange = false;
    button.css({'opacity':'0.7','cursor':'default'});
    
    $('form#formSortDesigners input, select, textarea, checked').each(function () {
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

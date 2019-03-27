@extends('layouts.master')

@section('title'){{ $title }}@stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home"><i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i></a> {{ $response->title }}
        	</h1>
       </div>
    </div>
@stop

@section('content') 
     
     	<!-- Col MD -->
<div class="col-md-8">	
     	
     <dl>
     	<dd>
     		{{ $response->content }}
     	</dd>
     </dl>	
 </div><!-- /COL MD -->
@stop

@section('sidebar')

	<div class="col-md-4">
    		
    		@include('includes.ads')
          
    </div><!-- /End col-md-4 -->
	
@stop



@extends('layouts.master')

@section('title'){{ Lang::get('misc.api') }} - @stop

@section('jumbotron')
 <div class="jumbotron static-header">
      <div class="container wrap-jumbotron">
        <h1 class="title-item none-overflow">
        	<a href="{{ URL::to('/') }}" class="link-home"><i class="glyphicon glyphicon-home myicon-right"></i> <i class="fa fa-angle-right"></i></a> {{ Lang::get('misc.api') }}
        	</h1>
       </div>
    </div>
@stop

@section('content') 
     
     	<!-- Col MD -->
<div class="col-md-8">	
	
	<div class="col-thumb">
		<h1 class="title_api">{{Lang::get('misc.get_user_data')}}</h1>
		<em>{{Lang::get('misc.example')}}</em>
		
		<div class="col-pre">
						<pre class="margin-zero">$ curl {{URL::to('/')}}/api/username/<strong>USERNAME</strong></pre>
					</div>
										
					<em>{{Lang::get('misc.data_return')}}</em>
					
					<div class="col-pre">
						<pre>{
							"<strong>totalFollowers</strong>":"2",
							"<strong>totalFollowing</strong>":"24",
							"<strong>Likes</strong>":"11",
							"<strong>totalShots</strong>":"29",
							"<strong>Lists</strong>":"0",
							"<strong>Listed</strong>":"2",
							"<strong>Projects</strong>":"3",
							"<strong>id</strong>":"1",
							"<strong>username</strong>":"MiguelVasquez",
							"<strong>name</strong>":"Miguel Vasquez",
							"<strong>location</strong>":"Aragua",
							"<strong>twitter</strong>":"https://twitter.com/MiguelVasquez08",
							"<strong>website</strong>":"http://miguelvasquez.net",
							"<strong>bio</strong>":"#WebDesign #Developer",
							"<strong>skills</strong>":"psd,photoshop,php,html5,css3",
							"<strong>avatar</strong>":"{{URL::to('/')}}/public/avatar/miguelvasquez_19zy00.png",
							"<strong>type_account</strong>":"2",
							"<strong>cover_image</strong>":"{{URL::to('/')}}/public/cover_miguelvasquez_1j07gs.jpg",
							}</pre>
					</div>
					
		<h1 class="title_api">{{Lang::get('misc.get_last_shots')}}</h1>
		<em>{{Lang::get('misc.example')}}</em>
		
		<div class="col-pre">
						<pre class="margin-zero">$ curl {{URL::to('/')}}/api/username/<strong>USERNAME</strong>/shots</pre>
					</div>
				<em>{{Lang::get('misc.data_return')}}</em>	
					<div class="col-pre">
						<pre>{
							"<strong>{{Lang::get('misc.shots')}}</strong>":
							[{
								"<strong>id</strong>":"1",
								"<strong>id_project</strong>":"0",
								"<strong>created_project</strong>":"0000-00-00 00:00:00",
								"<strong>image</strong>": "{{URL::to('/')}}/public/shots_img/3j8ivw84ibn.jpeg",
								"<strong>large_image</strong>": "{{URL::to('/')}}/public/shots_img/large/asa3j8ivw84ibn.jpeg",
								"<strong>token_id</strong>":"a8e015ec6de45c88ba0412bc9f6e2feb272b042e",
								"<strong>description</strong>": "Social Microblogging PRO Twitter is a script style, designed to share news, events, or simply what you want, through publications of 140 characters.",
								"<strong>title</strong>": "Social Microblogging PRO",
								"<strong>user_id</strong>": "1",
								"<strong>status</strong>": "1",
								"<strong>tags</strong>": "social,microblogging",
								"<strong>date</strong>": "2013-06-14 13:31:04",
								"<strong>attachment</strong>": "{{URL::to('/')}}/public/attachment_shots/oasdoaij46n.png",
								"<strong>url_purchased</strong>": "",
								"<strong>price_item</strong>": "",
							}
							]}</pre>
					</div>
	</div><!-- col thumb -->
    
 </div><!-- /COL MD -->
@stop

@section('sidebar')

	<div class="col-md-4">
    		
    		@include('includes.ads')
          
    </div><!-- /End col-md-4 -->
	
@stop



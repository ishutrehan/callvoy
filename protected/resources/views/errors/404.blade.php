<?php  
use App\Models\AdminSettings;
?>
<!DOCTYPE html>
<html lang="en">
  <head>
  	<?php $settings = AdminSettings::first(); ?>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="{{ $settings->description }}">
    <meta name="keywords" content="{{ $settings->keywords }}" />
     <link rel="shortcut icon" href="{{ URL::asset('public/img/favicon.ico') }}" />

    <title>{{ Lang::get('error.error_404_description') }} - @section('titulo') 
    	@show  
    	@if( isset( $settings->title ) ) {{ $settings->title }}
    	@endif </title>

    @include('includes.css_general')

  </head>
  
  <body id="bg-error">
    <!-- container -->
    <div class="container wrap-error">
    <!-- row -->
    <div class="row col-pb">

      <div class="col-md-12 text-center">
      	<h1 class="title-error">{{ Lang::get('error.error_404') }}</h1>
      	<p class="subtitle-error">{{ Lang::get('error.error_404_description') }}</p>
      	<a class="btn btn-danger" href="{{ URL::to('/') }}"><i class="icon-home myicon-right"></i> {{ Lang::get('error.go_home') }}</a>
     </div><!--/col-md-* -->

    	
  </div><!--************ Row ********************-->

      
    </div><!--******************** Container ******************-->


    @include('includes.javascript_general')
    
  </body>
  </html>
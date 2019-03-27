<?php
use App\Models\AdminSettings;


// Google Adsense	
$settings = AdminSettings::first();
	?>
@if( $settings->google_adsense != '' )
<!-- Start Panel -->
<div class="panel panel-default">
	<div class="panel-heading btn-block grid-panel-title">
		<span class="icon-bullhorn myicon-right"></span> {{ Lang::get('misc.advertising') }}
		</div>
		
<div class="panel-body">
	<div class="btn-df li-group">
	
	{{$settings->google_adsense}}	

	</div><!-- btn-df li-group -->
	</div><!-- Panel Body -->
</div><!--./ Panel Default -->
@endif
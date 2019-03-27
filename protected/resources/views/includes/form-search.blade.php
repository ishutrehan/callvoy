<form class="navbar-form navbar-left" role="search" autocomplete="off" action="{{ URL::to('/') }}/search" method="get">
		     	<div class="form-group">
		     		<input type="text" name="q" class="form-control" id="btnItems" placeholder="{{ Lang::get('misc.search') }}">
		     		<button type="submit" id="buttonSearch"><i class="fa fa-search"></i></button>
		     	</div><!--/.form-group -->
		     </form><!--./navbar-form -->
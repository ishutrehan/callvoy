@extends('layouts.master')

@section('content') 
     	
<!-- Col MD -->
<div class="col-md-12">	

		<div class="no-following-yet">
			<h2 class="margin-top-none text-center">- {{ Input::get('confirmation_code') }} {{ Lang::get('users.code_not_valid') }} -</h2>
		</div>
	
</div><!-- /COL MD -->
@stop



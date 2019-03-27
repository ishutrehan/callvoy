<?php
//header('Content-Type: text/plain; charset=utf-8;');

$shotsUser = Shots::where('members.username',$username)
->leftjoin('members','shots.user_id','=','members.id')
->select('shots.*')
->take(15)->get();
		
		$count = count( $shotsUser );

		if( $count != 0 ) {
		
		foreach ($shotsUser as $key) {
			$key->image       = URL::to('public/shots_image').'/'.$key->image;
			$key->large_image = URL::to('public/shots_image/large').'/'.$key->large_image;
			if( $key->attachment != '' ) {
				$key->attachment = URL::to('public/attachment').'/'.$key->attachment;
			} else {
				$key->attachment = '';
			}
		}	
			
			echo json_encode($shotsUser);
			
		} else {
			header ('HTTP/1.0 404 Not Found');
			echo json_encode(array('error' => '404', 'output' => Lang::get('misc.no_results_found')));
			exit;
		}

?>
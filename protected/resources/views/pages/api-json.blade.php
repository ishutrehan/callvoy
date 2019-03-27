<?php
//header('Content-Type: text/plain; charset=utf-8;');

$userInfo = DB::table('members')
		->select(DB::raw('
		COUNT(DISTINCT likes.shots_id) likes,
		COUNT(DISTINCT followers.id) totalFollowers,
		COUNT(DISTINCT F.id) totalFollowing,
		COUNT(DISTINCT shots.id) totalShots,
		COUNT(DISTINCT lists.id) lists,
		COUNT(DISTINCT lists_users.id) listed,
		COUNT(DISTINCT projects.id) projects,
		members.id,
		members.username,
		members.name,
		members.bio,
		members.location,
		members.avatar,
		members.cover,
		members.status,
		members.type_account,
		members.twitter,
		members.skills
		'))
		->leftjoin('shots', 'shots.user_id', '=', 'members.id')
		->leftjoin('lists', 'lists.user_id', '=', 'members.id')
		->leftjoin('lists_users', 'lists_users.user_id', '=', 'members.id')
		->leftjoin('projects', 'projects.user_id', '=', 'members.id')
		->leftjoin('likes', 'members.id', '=', DB::raw('likes.user_id AND likes.status = "1"'))
		->leftjoin('followers', 'members.id', '=', DB::raw('followers.following AND followers.status = "1"'))
		->leftjoin('followers as F', 'members.id', '=', DB::raw('F.follower AND F.status = "1"'))
		->where('members.username', $username )
		->where('shots.status', 1 )
		->where('members.status', 'active' )
		->groupBy('members.id')
		->first();
		
		
		if( !empty( $userInfo ) ) {
			
			$userInfo->avatar = URL::to('public/avatar').'/'.$userInfo->avatar;
			$userInfo->cover = URL::to('public/cover').'/'.$userInfo->cover;
		
			echo json_encode($userInfo);
		} else {
			header ('HTTP/1.0 404 Not Found');
			echo json_encode(array('error' => '404', 'output' => Lang::get('misc.user_no_exit')));
			exit;
		}

?>
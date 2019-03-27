<?php
namespace App\Http\BaseController;
class AdminController extends BaseController {
	
	public function admin() {
		
		
		if( Auth::check() && Auth::user()->role != 'normal' ) {
			return View::make('admin.settings');
		} else {
			 App::abort('404');
		}
		
	}//<--- END METHOD
	
	public function settings() {
	
	if( Auth::check() && Auth::user()->role != 'normal' ) {
					 
		$inputs = Input::All();
			
		$rules = array(
			'name_site'                 =>      'required',
			'welcome_text'              =>      'required',
			'welcome_subtitle'          =>      'required',
			'message_length'            =>      'required|numeric',
			'comment_length'            =>      'required|numeric',
			'shot_length_description'   =>      'required|numeric',
			'description'               =>      'required',
			'file_support_attach'       =>      'required',
			'result_request_shots'      =>      'required|numeric',
	    );

			$validation = Validator::make( $inputs, $rules );
			
			if($validation->fails()) {
	            return Redirect::to('panel/admin')->withInput()->withErrors( $validation );
	        } else {
	        	
				$inputs['keywords']            = implode(',',array_unique(explode(',', $inputs['keywords'])));
				$inputs['file_support_attach'] = implode(',',array_unique(explode(',', $inputs['file_support_attach'])));
	        	
				$settings                          = AdminSettings::find(1);
				$settings->title                   = trim($inputs['name_site']);
				$settings->welcome_text            = trim($inputs['welcome_text']);
				$settings->welcome_subtitle        = trim($inputs['welcome_subtitle']);
				$settings->keywords                = trim($inputs['keywords']);
				$settings->message_length          = $inputs['message_length'];
				$settings->comment_length          = $inputs['comment_length'];
				$settings->shot_length_description = $inputs['shot_length_description'];
				$settings->registration_active     = $inputs['new_registrations'];
				$settings->email_verification      = $inputs['email_verification'];
				$settings->captcha                 = $inputs['captcha'];
				$settings->file_support_attach     = trim($inputs['file_support_attach']);
				$settings->file_size_allowed       = $inputs['file_size_allowed'];
				$settings->result_request          = $inputs['result_request_shots'];
				$settings->description             = trim($inputs['description']);
				$settings->members_limit           = $inputs['limit_team_members'];
				$settings->invitations_by_email    = $inputs['invitations_by_email'];
				$settings->allow_attachments       = $inputs['allow_attachments'];
				$settings->allow_attachments_messages = $inputs['allow_attachments_msg'];
				$settings->team_free               = $inputs['teams_free'];
				$settings->allow_ads               = $inputs['allow_ads'];
				$settings->allow_jobs              = $inputs['allow_jobs'];
				$settings->pro_users_default       = $inputs['pro_users_default'];
				$settings->user_no_pro_comment_on  = $inputs['user_no_pro_comment_on'];
				$settings->update();
				
				Session::flash('notification',Lang::get('admin.success_update'));
	        	return Redirect::to('panel/admin');
	        }
	       } else {
	       	return Redirect::to('/');
	       }
		}//<--- End Method
		
	public function add_page() {
	
	if( Auth::check() && Auth::user()->role != 'normal' ) {
					 
		$inputs = Input::All();
		
		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});
			
		$rules = array(
		
			'title'      =>      'required',
			'slug'       =>      'required|ascii_only|alpha_dash|unique:pages',
			'content'    =>      'required',
	    );
		
		$messages = array (
            "required"   => Lang::get('validation.required'),
            "ascii_only" => Lang::get('validation.alpha_dash'),
            "alpha_dash" => Lang::get('validation.alpha_dash'),
			"unique"     => Lang::get('validation.unique'),
        );
		

			$validation = Validator::make( $inputs, $rules, $messages );
			
			if($validation->fails()) {
	            return Redirect::to('panel/admin/pages/new')->withInput()->withErrors( $validation );
	        } else {
	        	
				$page          = new Pages;
				$page->title   = trim($inputs['title']);
				$page->slug    = trim(strtolower($inputs['slug']));;
				$page->content = trim($inputs['content']);
				$page->save();
				
				Session::flash('notification',Lang::get('admin.success_page_create'));
	        	return Redirect::to('panel/admin/pages/new');
	        }
	       } else {
	       	return Redirect::to('/');
	       }
		}//<--- End Method
		
		public function edit_page() {
	
	if( Auth::check() && Auth::user()->role != 'normal' ) {
		
		$inputs = Input::All();
		
		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});
		
		$page = Pages::find( $inputs['id'] );
		
		if( !$page ) {
			 return Redirect::to('panel/admin/pages');
		}
			
		$rules = array(
			'title'      =>      'required',
			'slug'       =>      'required|ascii_only|alpha_dash|unique:pages,slug,'.$inputs['id'],
			'content'    =>      'required',
	    );
		
		$messages = array (
            "required"   => Lang::get('validation.required'),
            "ascii_only" => Lang::get('validation.alpha_dash'),
            "alpha_dash" => Lang::get('validation.alpha_dash'),
			"unique"     => Lang::get('validation.unique'),
        );

			$validation = Validator::make( $inputs, $rules, $messages );
			
			if($validation->fails()) {
	            return Redirect::back()->withInput()->withErrors($validation);
	        } else {
	        	
				$page->title   = trim($inputs['title']);
				$page->slug    = trim(strtolower($inputs['slug']));;
				$page->content = trim($inputs['content']);
				$page->save();
				
				Session::flash('notification',Lang::get('admin.success_update'));
	        	return Redirect::back();
	        }
	       } else {
	       	return Redirect::to('/');
	       }
		}//<--- End Method
		
		public function delete_page($id){
	  
	  if( Auth::check() && Auth::user()->role != 'normal' ) {
		
		$page = Pages::find( $id );
		  
		  if( !isset( $page ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {

			// DELETE PAGE
			$page->delete();
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method
	
	public function edit_member($id){
		
	$settings = AdminSettings::first();
	 
	$inputs = Input::All();
		
	$user_id = User::find($id);
	
	if( !isset( $user_id->id ) ){
		return Redirect::to('/');
		exit;
	}
	
	$rules = array(
		'full_name' => 'required|min:3|max:25',
        'email'     => 'required|email|unique:members,email,'.$user_id->id,
        'location'  => 'max:50',
        'website'   => 'url',
        'twitter'   => 'alpha_dash',
        'bio'       => 'max:'.$settings->message_length.'',
    );
	
	$messages = array (
            "required"   => Lang::get('validation.required'),
            "max"        => Lang::get('validation.max.string'),
            "min"        => Lang::get('validation.min.string'),
            "alpha_dash" => Lang::get('validation.alpha_dash'),
			"unique"     => Lang::get('validation.unique'),
			"email"      => Lang::get('validation.email')
        );
	
		$validation = Validator::make( $inputs, $rules, $messages );
		
		if($validation->fails()) {
            return Redirect::back()->withInput()->withErrors($validation);
        } else {
        	
			//<----- * Notifications PRO USER * -------->
			$notifications_pro = Notifications::where('destination',$user_id->id)
			->where('type',9)
			->first();
			
			// Projects
			$projects = Projects::where('user_id',$user_id->id)->get();
			
			if( $inputs['type_account'] == 2 && !isset( $notifications_pro ) && $inputs['status'] == 'active' ){
				
				Notifications::send( $user_id->id, Auth::user()->id, 9, Auth::user()->id );
				
			} else if( $inputs['type_account'] == 1 && isset( $notifications_pro ) ){
				$notifications_pro->trash = 1;
				$notifications_pro->save();
			}
			
			// Disable all user projects if suspended
			if( $inputs['type_account'] == 1 && isset( $projects ) ){
				
				foreach ($projects as $project) {
					$project->status = 0;
					$project->save();
				}
			}//
			
			// Activate all user projects if Activated again
			if( $inputs['type_account'] == 2 && isset( $projects ) ){
				
				foreach ($projects as $project) {
					$project->status = 1;
					$project->save();
				}
			}//
			
			//<----- * Role USER * -------->
			$role_user = Notifications::where('destination',$user_id->id)
			->where('type',10)
			->first();
			
			if( $inputs['role'] == 'admin' && !isset( $role_user ) ){
				
				Notifications::send( $user_id->id, Auth::user()->id, 10, Auth::user()->id );
				
			} else if( $inputs['role'] == 'normal' && isset( $role_user ) ){
				$role_user->trash = 1;
				$role_user->save();
			}
			//<----- * Role USER * -------->
			
			
			// Disable all user projects if suspended
			if( $inputs['status'] != 'active' && isset( $projects ) ){
				foreach ($projects as $project) {
					$project->status = 0;
					$project->save();
				}
			}// IF
			
			// Activate all user projects if Activated again
			if( $inputs['status'] == 'active' && isset( $projects ) ){
				
				foreach ($projects as $project) {
					$project->status = 1;
					$project->save();
				}
			}//
			
			// Shots
			$_shots = Shots::where('user_id',$user_id->id)->get();
			
			// Disable all Shots if suspended
			if( $inputs['status'] != 'active' && isset( $_shots ) ){
				
				foreach ($_shots as $_shot) {
					$_shot->status = 0;
					$_shot->save();
				}
			}//
			
			// Activate all Shots
			if( $inputs['status'] == 'active' && isset( $_shots ) ){
				
				foreach ($_shots as $_shot) {
					$_shot->status = 1;
					$_shot->save();
				}
			}//
        	
			$bio = str_replace( array( chr( 10 ), chr( 13 ) ), '' , $inputs["bio"] );
			
			$skills = Str::slug( $inputs["skills"], $separator = ',' );
			$skills = implode(',',array_unique(explode(',', $skills)));
			
			$user               = User::find( $user_id->id );
            $user->name         = trim( $inputs["full_name"] );
			$user->bio          = trim( $bio );
			$user->location     = trim( $inputs["location"] );
            $user->email        = trim( strtolower( $inputs["email"] ) );
			$user->website      = trim( $inputs["website"] );
			$user->skills       = $skills;
			$user->twitter      = trim( $inputs["twitter"] );
			$user->status       = $inputs['status'];
			$user->type_account = $inputs['type_account'];
			$user->role         = $inputs['role'];
            $user->update();
			Session::flash('notification',Lang::get('admin.success_update'));
        	return Redirect::back();
        }
		
	}//<--- End Method
	
	public function delete_user($token){
		
		if( Auth::check() && Auth::user()->role != 'normal' ){
			
			$userFind = User::where('token',$token)->first();
			
			$id = $userFind->id;
						
			// Member of a Team
			$team_member = TeamMembers::where('user_id',$id)->first();
			if( isset( $team_member ) ){
				$team_member->delete();
			}
			
			// If a Team Delete your account
			$teams = TeamMembers::where('team_id',$id)->get();
			
			foreach($teams as $team){
				$team->delete();
			}
			
			// Select all post of Team ID for members
			$shotsForTeams = Shots::where('team_id',$id)->get();
			
			foreach($shotsForTeams as $shotsForTeam){
				$shotsForTeam->team_id = 0;
				$shotsForTeam->save();
			}
			
			// UPDATE All Members of Team
			$membersTeams = User::where('team_id',$id)->get();
			
			foreach($membersTeams as $membersTeam){
				$membersTeam->team_id = 0;
				$membersTeam->save();
			}
			
			$_dateNow   = date('Y-m-d G:i:s');
			
			// Expire all Payments
			$paypal_payments_teams = DB::table('paypal_payments_teams')
			->where('user_id',$id )
			->update(array('expire' => $_dateNow));
			
			
			// Comments Delete
			$comments = Comments::where('user_id', '=', $id)->get();
			
			foreach($comments as $comment){
				$comment->delete();
			}
			
			// Comments Likes Delete
			$comment_likes = CommentsLikes::where('user_id', '=', $id)->get();
			
			foreach($comment_likes as $comment_like){
				$comment_like->delete();
			}
			
			// Shots of User
			$shots_users = Shots::where('user_id', '=', $id)->get();
			
			// Comments in the shots
			foreach($shots_users as $shots_user){
				$_comments = Comments::where('shots_id', '=', $shots_user->id)->get();
				
				foreach ($_comments as $_comment ) {
					$_comment->delete();
				}
			}//End
			
			// Shots Reported
			foreach($shots_users as $shots_user){
				
				$shots_reporteds = ShotsReported::where('shots_id', '=', $shots_user->id)->get();
				
				foreach ($shots_reporteds as $shots_reported ) {
					$shots_reported->delete();
				}
			}//End
			
			// User Shots Reported
			$shots_reported_users = ShotsReported::where('user_id', '=', $id)->get();
			
			foreach ($shots_reported_users as $shots_reported_user ) {
					$shots_reported_user->delete();
				}// End User Shots Reported
			
			// Members Reported
			$reports = MembersReported::where('user_id', '=', $id)
			->orWhere('id_reported', '=', $id)
			->get();
			
			foreach($reports as $report){
				$report->delete();
			}// End Members Reported
			
			// Delete Conversations
			$conversations = DB::table('conversations')
			->where('user_1',$id )
			->orWhere('user_2',$id)
			->delete();
				
			// Unfollow
			Followers::where( 'follower', $id )
				->orwhere('following',$id)
				->update(array('status' => 0));	
			
			// Likes	
			$shots_likes = Like::where('user_id', '=', $id)->get();
			foreach($shots_likes as $shots_like){
				$shots_like->delete();
			}
			
			// Likes in the shots
			$likes_shots = Shots::where('user_id', '=', $id)->get();
			
			foreach($likes_shots as $likes_shot){
				$_shots_likes = Like::where('shots_id', '=', $likes_shot->id)->get();
				
				foreach ($_shots_likes as $_shots_like ) {
					$_shots_like->delete();
				}
			}
			
			// Lists	
			$lists = Lists::where('user_id', '=', $id)->get();
			
			foreach($lists as $list){
				$list->delete();
			}
			
			// Lists Users
			$listsUsers = ListsUsers::where('user_id', '=', $id)->get();
			
			foreach($listsUsers as $listsUser){
				$listsUser->delete();
			}
			
			// Messages	
			$messages = Messages::where('from_user_id', '=', $id)->orWhere('to_user_id', '=', $id)->get();
			
			foreach($messages as $message){
				
				$fileAttach    = 'public/attachment_messages/'.$message->attach_file;
				
				//<<<-- Delete Attach -->>>/
					if ( File::exists($fileAttach) ) {
						File::delete($fileAttach);	
					}//<--- IF FILE EXISTS
					
				$message->delete();
			}
			
			// Delete Notification
			$notifications = Notifications::where('author',$id)
			->orWhere('destination', $id)
			->get(); 
			
			foreach($notifications as $notification){
				$notification->delete();
			}
			
			// Projects	
			$projects = Projects::where('user_id', '=', $id)->get();
			
			foreach($projects as $project){
				$project->delete();
			}
			
			// Shots	
			$shots = Shots::where('user_id', '=', $id)->get();
			
			foreach($shots as $shot){
				
				$fileShot      = 'public/shots_img/'.$shot->image;
				$fileShotLarge = 'public/shots_img/large/'.$shot->large_image;
				$fileShotOriginal = 'public/shots_img/original/'.$shot->original_image;
				$fileAttach    = 'public/attachment_shots/'.$shot->attachment;
				
				//<<<-- Delete image -->>>/
				if ( File::exists($fileShot) ) {
					File::delete($fileShot);	
				}//<--- IF FILE EXISTS
				
				//<<<-- Delete image -->>>/
				if ( File::exists($fileShotLarge) ) {
					File::delete($fileShotLarge);	
				}//<--- IF FILE EXISTS
				
				//<<<-- Delete image -->>>/
			if ( File::exists($fileShotOriginal) ) {
				File::delete($fileShotOriginal);	
			}//<--- IF FILE EXISTS
				
				//<<<-- Delete Attach -->>>/
				if ( File::exists($fileAttach) ) {
					File::delete($fileAttach);	
				}//<--- IF FILE EXISTS
			
				$shot->delete();
			}

			
			//<<<-- Delete Avatar -->>>/
			$fileAvatar    = 'public/avatar/'.$userFind->avatar;
			
				if ( File::exists($fileAvatar) && $userFind->avatar != 'default.jpg' ) {
					File::delete($fileAvatar);	
				}//<--- IF FILE EXISTS
				
			//<<<-- Delete Cover -->>>/
			$fileCover    = 'public/cover/'.$userFind->cover;
			
				if ( File::exists($fileCover) && $userFind->cover != 'cover.jpg' ) {
					File::delete($fileCover);	
				}//<--- IF FILE EXISTS
				

			User::find($id)->delete();
						
			return Redirect::to('panel/admin/members');
			
		}//Auth
		 else {
			return Redirect::to('/');
		}
	}//<--- End Method
	
	public function delete_report_member($id){
		
		if( Auth::check() && Auth::user()->role != 'normal' ) {
		
		$report = MembersReported::find( $id );
		  
		  if( !isset( $report ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {

			// DELETE PAGE
			$report->delete();
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method
	
	public function delete_report_shots($id){
		
		if( Auth::check() && Auth::user()->role != 'normal' ) {
		
		$report = ShotsReported::find( $id );
		  
		  if( !isset( $report ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {

			// DELETE PAGE
			$report->delete();
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method
	
	public function payments_settings() {
	
	if( Auth::check() && Auth::user()->role != 'normal' ) {
					 
		$inputs = Input::All();
			
		$rules = array(
			'mail_business'             =>      'required|email',
			'email_notifications'       =>      'required|email',
			'price_jobs'                =>      'required|numeric',
			'cost_per_impression'       =>      'required|numeric',
			'cost_per_click'            =>      'required|numeric',
			'cost_per_click'            =>      'required|numeric',
			'price_membership_teams'    =>      'required|numeric',
	    );

			$validation = Validator::make( $inputs, $rules );
			
			if($validation->fails()) {
	            return Redirect::to('panel/admin/payments-settings')->withInput()->withErrors( $validation );
	        } else {
	        	
		switch( $inputs['currency_code'] ) {
			case 'USD':
				$currency_symbol  = '$';
				break;
			case 'EUR':
				$currency_symbol  = '€';
				break;
			case 'GBP':
				$currency_symbol  = '£';
				break;
			case 'AUD':
				$currency_symbol  = '$';
				break;
			case 'JPY':
				$currency_symbol  = '¥';
				break;
				
			case 'BRL':
				$currency_symbol  = 'R$';
				break;
			case 'MXN':
				$currency_symbol  = '$';
				break;
			case 'SEK':
				$currency_symbol  = 'Kr';
				break;
			case 'CHF':
				$currency_symbol  = 'CHF';
				break;
			case 'SGD':
				$currency_symbol  = '$';
				break;
			case 'DKK':
				$currency_symbol  = 'Kr';
				break;
			case 'RUB':
				$currency_symbol  = 'руб';
				break;
		}
	        	
				$settings                          = AdminSettings::find(1);
				$settings->paypal_sandbox          = trim($inputs['paypal_sandbox']);
				$settings->mail_business           = trim($inputs['mail_business']);
				$settings->email_notifications     = trim($inputs['email_notifications']);
				$settings->duration_jobs           = trim($inputs['duration_jobs']);
				$settings->price_jobs              = trim($inputs['price_jobs']);
				$settings->currency_code           = trim($inputs['currency_code']);
				$settings->currency_symbol         = $currency_symbol;
				$settings->cost_per_impression     = trim($inputs['cost_per_impression']);
				$settings->cost_per_click          = trim($inputs['cost_per_click']);
				$settings->price_membership_teams  = trim($inputs['price_membership_teams']);
				$settings->update();
				
				Session::flash('notification',Lang::get('admin.success_update'));
	        	return Redirect::to('panel/admin/payments-settings');
	        }
	       } else {
	       	return Redirect::to('/');
	       }
		}//<--- End Method
		
	public function social_login() {
	
		if( Auth::check() && Auth::user()->role != 'normal' ) {
						 
			$inputs = Input::All();
		        	
			$settings                          = AdminSettings::find(1);
			$settings->twitter_login           =  $inputs['twitter_login'];
			$settings->twiter_appid            = trim($inputs['twiter_appid']);
			$settings->twitter_secret          = trim($inputs['twitter_secret']);
			$settings->update();
			
			Session::flash('notification',Lang::get('admin.success_update'));
			return Redirect::to('panel/admin/social-login');
		       } else {
		       	return Redirect::to('/');
		     }
	}//<--- End Method
	
	public function profiles_social() {
	
		if( Auth::check() && Auth::user()->role != 'normal' ) {
						 
			$inputs = Input::All();
		        	
			$settings                  = AdminSettings::find(1);
			$settings->twitter         =  $inputs['twitter'];
			$settings->facebook        = trim($inputs['facebook']);
			$settings->instagram       = trim($inputs['instagram']);
			$settings->googleplus      = trim($inputs['googleplus']);
			$settings->linkedin        = trim($inputs['linkedin']);
			$settings->update();
			
			Session::flash('notification',Lang::get('admin.success_update'));
			return Redirect::to('panel/admin/profiles-social');
		       } else {
		       	return Redirect::to('/');
		     }
	}//<--- End Method
	
	public function delete_lists($id){
		
		if( Auth::check() && Auth::user()->role != 'normal' ) {
		
		$list      = Lists::find( $id );
		$listUsers = ListsUsers::where( 'lists_id', $id )->get();
		$notify    = Notifications::where( 'author', $list->user_id )->where('target',$id)->where('type',7)->get();
		  
		  if( !isset( $list ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {

			// DELETE listUsers
			if( !empty( $listUsers ) ) {
				foreach ($listUsers as $listUser) {
					$listUser->delete();
				}
			}
			
			// DELETE notify
			if( !empty( $notify ) ) {
				foreach ($notify as $noty) {
					$noty->delete();
				}
			}
			
			$list->delete();
			
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method
	
	public function delete_projects($id){
		
		if( Auth::check() && Auth::user()->role != 'normal' ) {
		
		$projects = Projects::find( $id );
		$shots    = Shots::where('id_project',$id)->get();
		  
		  if( !isset( $projects ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {
				
			if( !empty( $shots ) ) {
				// Update Shots
				foreach ($shots as $shot) {
					$shot->id_project = 0;
					$shot->created_project = '0000-00-00 00:00:00';
					$shot->save();
				}
			}
			
			$projects->delete();
			
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method
	
	public function edit_job(){
	  
	  if( Auth::check() && Auth::user()->role != 'normal' ) {
	  	
		$settings = AdminSettings::first();
		$inputs = Input::All();
		  
		$job = Jobs::where('token',$inputs['token'])->first();
		  
		  if( !isset( $job ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {

		// Setup the validator
		$rules = array(
			'organization_name'      => 'required|min:3|max:30',
			'job_title'              => 'required|min:3|max:30',
			'url_to_job_description' => 'required|url',
			'location'               => 'required|min:2|max:40',
			
			);

		$validator = Validator::make($inputs, $rules);
		
		if( $validator->fails() ) {
            return Redirect::back()->withInput()->withErrors( $validator );
		} else {
			
			// UPDATE JOB
			$job->organization_name  = trim($inputs['organization_name']);
			$job->workstation        = trim($inputs['job_title']);
			$job->url_job            = trim($inputs['url_to_job_description']);
			$job->location           = trim($inputs['location']);
			$job->save();
			
			Session::flash('notification',Lang::get('misc.success_update_job'));
			return Redirect::back();
		}
		  }//else !Job
	  }
	}//<--- End Method
	
	public function delete_job($token){
	  
	  if( Auth::check() && Auth::user()->role != 'normal' ) {

		$settings = AdminSettings::first();
		  
		$job = Jobs::where('token',$token)->first();
		  
		  if( !isset( $job ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {
			// DELETE JOB
			$job->delete();
			return Redirect::to('panel/admin/jobs');
		  }//else !Job
	  }
	}//<--- End Method
	
	public function delete_ads($code){

	  if( Auth::check() && Auth::user()->role != 'normal' ) {
	  			  
		$ad = Advertising::where('code',$code)->first();
		  
		  $ad_clicks      = DB::table('ad_clicks')->where('ad_id',$ad->id)->get();
		  $ad_impressions = DB::table('ad_impressions')->where('ad_id',$ad->id)->get();
		  
		  if( !isset( $ad ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {
		  	
			$fileAd    = 'public/ad/'.$ad->ad_image;
			
			//<<<-- Delete Attach -->>>/
			if ( File::exists($fileAd) ) {
				File::delete($fileAd);	
			}//<--- IF FILE EXISTS
			
			// DELETE AD
			$ad->delete();
			
			// Ads Clicks
			if( isset( $ad_clicks ) ){
				DB::table('ad_clicks')->where('ad_id',$ad->id)->delete();
			}
			
			// Ads Impressions
			if( isset( $ad_impressions ) ){
				DB::table('ad_impressions')->where('ad_id',$ad->id)->delete();
			}
			
			return Redirect::to('panel/admin/ads');
		  }//else !Job
	  }
	}//<--- End Method
	
	public function google_adsense() {
	
		if( Auth::check() && Auth::user()->role != 'normal' ) {
						 
			$inputs = Input::All();
		        	
			$settings                  = AdminSettings::find(1);
			$settings->google_adsense  =  $inputs['code'];
			$settings->update();
			
			Session::flash('notification',Lang::get('admin.success_update'));
			return Redirect::to('panel/admin/google-adsense');
		       } else {
		       	return Redirect::to('/');
		     }
	}//<--- End Method
	
}
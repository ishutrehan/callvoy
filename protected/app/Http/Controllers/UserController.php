<?php


namespace App\Http\Controllers;
use App\Models\User;
use App\Models\AdminSettings;
use App\Models\Notifications;
use App\Models\Messages;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Validator;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Str;
use Hash;

use View;
use Redirect;
use DB;
use Session;
class UserController extends BaseController {

	public function __construct(){

	}

	public function profile($slug) {
		
		$settings = AdminSettings::first();
		
		$user     = User::where( 'username','=', $slug )->first();
		
		$page = Input::get('page');
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$title    = e( $user->name ).' - ';
		
		$data = Shots::where('status',1)
		->where('user_id', '=', $user->id)
		->orWhere('team_id', $user->id )
		->where('status', 1 )
		->groupBy('id')
		->orderBy('id', 'desc' )
		->paginate( $settings->result_request );
		
		//$data = Shots::getAllShotsUser( $user->id );
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		$total = $data->getTotal();
		
		//<<<-- * Redirect the user real name * -->>>
		$uri = Request::path();
		$uriCanonical = '@'.$user->username;
		
		if( $uri != $uriCanonical ) {
			return Redirect::to($uriCanonical);
		}
		
 		return View::make('user.profile', compact( 'user','title', 'data', 'total' ));
	}//<--- End Method
	
	public function likes($slug) {
		
		$settings = AdminSettings::first();
		
		$user     = User::where( 'username','=', $slug )->first();
		
		$page = Input::get('page');
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$title    = 'Likes - '.e( $user->name ).' - ';
		
		$data = Shots::leftjoin('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
		->where('shots.status', 1 )
		->where('likes.user_id', '=', $user->id)
		->groupBy('id')
		->orderBy('likes.id', 'desc' )
		->select('shots.*')
		->paginate( $settings->result_request );
		//$data = Shots::getLikesShots( $user->id );
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		$total = $data->getTotal();
		
		//<<<-- * Redirect the user real name * -->>>
		$uri = Request::path();
		$uriCanonical = '@'.$user->username.'/likes';
		
		if( $uri != $uriCanonical ) {
			return Redirect::to($uriCanonical);
		}
		
 		return View::make('user.likes', compact( 'user','title', 'data', 'total' ));
	}//<--- End Method
	
	public function followers($slug) {
		
		$user = User::where( 'username','=', $slug )->first();
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$title    = 'Followers - '.e( $user->name ).' - ';
		
		//<<<-- * Redirect the user real name * -->>>
		$uri = Request::path();
		$uriCanonical = '@'.$user->username.'/followers';
		
		if( $uri != $uriCanonical ) {
			return Redirect::to($uriCanonical);
		}
		
 		return View::make('user.followers', compact( 'user','title' ));
	}//<--- End Method
	
	public function following($slug) {
		
		$user = User::where( 'username','=', $slug )->first();
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$title    = 'Following - '.e( $user->name ).' - ';
		
		//<<<-- * Redirect the user real name * -->>>
		$uri = Request::path();
		$uriCanonical = '@'.$user->username.'/following';
		
		if( $uri != $uriCanonical ) {
			return Redirect::to($uriCanonical);
		}
		
 		return View::make('user.following', compact( 'user','title' ));
	}//<--- End Method
	
	public function getNotifications() {
		
		if( Auth::check() ) {
			
			$settings = AdminSettings::first();
			
		$sql = DB::table('notifications')
			 ->select(DB::raw('
			notifications.id id_noty,
			notifications.type,
			notifications.created_at,
			members.id,
			members.username,
			members.name,
			members.avatar,
			shots.id,
			shots.title,
			lists.id as list_id,
			lists.name as list_name
			'))
			->leftjoin('members', 'members.id', '=', DB::raw('notifications.author'))
			->leftjoin('shots', 'shots.id', '=', DB::raw('notifications.target AND shots.status = "1"'))
			->leftjoin('lists', 'lists.id', '=', DB::raw('notifications.target'))
			->leftjoin('comments', 'comments.shots_id', '=', DB::raw('notifications.target 
			AND comments.user_id = members.id
			AND comments.shots_id = shots.id
			AND comments.status = "1"
			'))
			->where('notifications.destination', '=',  Auth::user()->id )
			->where('notifications.author', '!=',  Auth::user()->id )
			->where('notifications.trash', '=',  '0' )
			->where('members.status', '=',  'active' )
			->groupBy('notifications.id')
			->orderBy('notifications.id', 'DESC')
			->paginate( $settings->result_request );
			
			// Mark seen Notification
			Notifications::where('destination', Auth::user()->id)
			->update(array('status' => 1 ));
			
			return View::make('user.notifications')->with('sql',$sql);
			
			
		} else {
			return Redirect::to('/');
		}
		
	}//<--- End Method
	
	public function getJoin() {
		
		 // ** Admin Settings ** //
     	$settings = AdminSettings::first();
	 
		if( !Auth::check() && $settings->registration_active == '1' ) {
			return View::make('auth.join');
		} else {
			return Redirect::to('/');
		}
		
	}//<--- End Method
	
	public function getJoinNormal() {
		
		// ** Admin Settings ** //
     	$settings = AdminSettings::first();
		
		if( !Auth::check() && $settings->registration_active == '1' ) {
			return View::make('auth.join-normal');
		} else {
			return Redirect::to('/');
		}
		
	}//<--- End Method
	
	public function join() {
	
	$settings = AdminSettings::first();
	
	$inputs = Input::All();
	
	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    return !preg_match('/[^x00-x7F\-]/i', $value);
	});
		
	$rules = array(
		'full_name' => 'required|min:3|max:25',
		'username'  => 'required|min:3|max:15|ascii_only|alpha_dash|unique:members',
        'email'     => 'required|email|unique:members',
        'password'  => 'required|min:6'
    );
	

	
	$messages = array (
            "required"   => Lang::get('validation.required'),
            "max"        => Lang::get('validation.max.string'),
            "min"        => Lang::get('validation.min.string'),
            "ascii_only" => Lang::get('validation.alpha_dash'),
            "alpha_dash" => Lang::get('validation.alpha_dash'),
			"unique"     => Lang::get('validation.unique'),
			"email"      => Lang::get('validation.email')
        );
		
		// Verify Settings Admin
		
		if( $settings->email_verification == 1 ) {
			$confirmation_code = Str::random( 100 );
			$status = 'pending';
		} else {
			$confirmation_code = '';
			$status            = 'active';
		}
	
		$validation = Validator::make( $inputs, $rules, $messages );
		
		if($validation->fails()) {
            return Redirect::to('join-normal')->withInput()->withErrors( $validation );
        } else {
        	
			$token = Str::random(75);
			
			if( isset( $settings->pro_users_default ) && $settings->pro_users_default == 'on'  ){
				$typeAccount = 2;
			} else {
				$typeAccount = 1;
			}
			
			$user                  = new User;
            $user->name            = trim( $inputs["full_name"] );
            $user->username        = trim( $inputs["username"] );
            $user->email           = trim( strtolower( $inputs["email"] ) );
			$user->password        = Hash::make( $inputs["password"] );
            $user->date            = date( 'Y-m-d G:i:s', time() );
			$user->avatar          = 'default.jpg';
			$user->cover           = 'cover.jpg';
			$user->status          = $status;
			$user->type_account    = $typeAccount;
			$user->activation_code = $confirmation_code;
			$user->token           = $token;
            $user->save();
			
			if ( $settings->email_verification == 1 ){
				
				$userName  = Input::get('username');
				$emailUser = Input::get('email');
						
				Mail::send('emails.verify', array( 'confirmation_code' => $confirmation_code ), 
				function($message) use($emailUser,$userName) {
		            $message->to($emailUser, $userName)
		                ->subject( Lang::get('users.title_email_verify') );
		        });
				
			    	Session::flash('notification',Lang::get('auth.check_account'));
		        	return Redirect::to('join-normal');
		    	
		    } else {
		    	
				$login_true = array(
		        'email'    => trim( strtolower( $inputs["email"] ) ),
		        'password' => $inputs["password"]
				);
		
		    	Auth::attempt( $login_true );
				
				Session::flash('welcome',Lang::get('misc.wecolme_users',['name' => Auth::user()->name, 'site_name' => $settings->title ]));
				
		        return Redirect::to('/');
		    }
        }
		
	}//<--- End Method
	
	public function getJoinTeam() {
		
		// ** Admin Settings ** //
     	$settings = AdminSettings::first();
		
		if( !Auth::check() && $settings->registration_active == '1' ) {
			return View::make('auth.join-team');
		} else {
			return Redirect::to('/');
		}
	}//<--- End Method
	
	public function joinTeam() {
	
	$settings = AdminSettings::first();
	
	$inputs = Input::All();
	
	Validator::extend('ascii_only', function($attribute, $value, $parameters){
    return !preg_match('/[^x00-x7F\-]/i', $value);
	});
		
	$rules = array(
		'team_name' => 'required|min:3|max:25',
		'username'  => 'required|min:3|max:15|ascii_only|alpha_dash|unique:members',
        'email'     => 'required|email|unique:members',
        'password'  => 'required|min:6'
    );
	
	$messages = array (
            "required"   => Lang::get('validation.required'),
            "max"        => Lang::get('validation.max.string'),
            "min"        => Lang::get('validation.min.string'),
            "ascii_only" => Lang::get('validation.alpha_dash'),
            "alpha_dash" => Lang::get('validation.alpha_dash'),
			"unique"     => Lang::get('validation.unique'),
			"email"      => Lang::get('validation.email')
        );
		
	
		$validation = Validator::make( $inputs, $rules, $messages );
		
		if($validation->fails()) {
            return Redirect::to('join-team')->withInput()->withErrors( $validation );
        } else {
        	 
			 if( isset( $settings->team_free ) && $settings->team_free == 'off'  ){
			 	//return Redirect::to('payment/team');
        	 	return View::make('auth.payment-team');
			 } else {
			 	
				// Verify Settings Admin
		
				if( $settings->email_verification == 1 ) {
					$confirmation_code = Str::random( 100 );
					$status = 'pending';
				} else {
					$confirmation_code = '';
					$status            = 'active';
				}

				$token = Str::random(75);
		
				$user                  = new User;
			    $user->name            = trim( $inputs["team_name"] );
			    $user->username        = trim( $inputs["username"] );
			    $user->email           = trim( strtolower( $inputs["email"] ) );
				$user->password        = Hash::make( $inputs["password"] );
			    $user->date            = date( 'Y-m-d G:i:s', time() );
				$user->avatar          = 'default.jpg';
				$user->cover           = 'cover.jpg';
				$user->status          = $status;
				$user->type_account    = 3;
				$user->activation_code = $confirmation_code;
				$user->token           = $token;
				$user->team_free       = 1;
			    $user->save();
				
				$idUser = $user->id;
				
				if ( $settings->email_verification == 1 ){
				
				$userName = Input::get('username');
				$emailUser = Input::get('email');
						
				Mail::send('emails.verify', array( 'confirmation_code' => $confirmation_code ), 
				function($message) use($emailUser,$userName) {
		            $message->to($emailUser, $userName)
		                ->subject( Lang::get('users.title_email_verify') );
		        });
				
			    	Session::flash('notification',Lang::get('auth.check_account'));
		        	return Redirect::to('join-team');
		    	
		    } else {
		    	
				Auth::loginUsingId($idUser);
						
				Session::flash('welcome',Lang::get('misc.wecolme_users',['name' => Auth::user()->name, 'site_name' => $settings->title ]));
				
		        return Redirect::to('/');
		    }
			 	
			 }
			
        	 
        }
		
	}//<--- End Method
	
	public function getLogin() {
		
		if( !Auth::check() ) {
			return View::make('auth.login');
		} else {
			return Redirect::to('/');
		}
		
	}//<--- End Method
	
	public function login() {
	
	$rememberInput = Input::get('keep_login');
	
	// get login
	    $email_login = array(
	        'email'    => trim( Input::get('username_email') ),
	        'password' => Input::get('password')
	    );

	    $username_login = array(
	        'username' => trim( Input::get('username_email') ),
	        'password' => Input::get('password')
	    );
		
		// Maintain user logged
		if( isset( $rememberInput ) && $rememberInput == 1 ) {
			$remember = true;
		} else {
			$remember = false;
		}

	if ( Auth::attempt( $email_login, $remember ) || Auth::attempt( $username_login, $remember ) ){

	    	return Redirect::to('/');
	    	
	    } else {
	        return Redirect::to('login')->withInput()->with(array('notification' => Lang::get('validation.error_logging'), 'rememberInput' => $remember));
	    }
	}//<--- End Method
	
	public function recover_pass() {
		
		if( !Auth::check() ) {
			return View::make('auth.password_recover');
		} else {
			return Redirect::to('/');
		}
	}//<--- End Method

	//<<--- RESET REQUEST

	public function recover_request() {
		
	  switch ($response = Password::remind( Input::only('email'), function($message){
	  	$message->subject(Lang::get('auth.password_recover'));
	  } ) ) {
			case Password::INVALID_USER:
				return Redirect::back()->with('error', Lang::get($response));
			case Password::REMINDER_SENT:

				return Redirect::back()->with('success', Lang::get($response));
		}
	}//<--- End Method

	public function password_reset_token($token = null) {
		
		if (is_null($token)) {
				App::abort(404);
			}
		if( !Auth::check() ) {	
		  return View::make('auth.password_reset')->with('token', $token);
		} else {
			return Redirect::to('/');
		}
	  
	}//<--- End Method
	
	public function password_reset_post() {
		
	   $credentials = Input::only(
			'email', 'password', 'password_confirmation', 'token'
		);
		$response = Password::reset($credentials, function($user, $password) {
			$user->password = Hash::make($password);
			$user->save();
		});
		
		switch ($response)
		{
			case Password::INVALID_PASSWORD:
			case Password::INVALID_TOKEN:
			case Password::INVALID_USER:
				return Redirect::back()->withInput()->with('error', Lang::get($response));
			case Password::PASSWORD_RESET:
				return Redirect::to('login')->with(array('password_reset_success' => Lang::get('auth.password_reset')));
		}
	}//<--- End Method
	
	public function getAccount() {
	
		if( Auth::check() ) {
			return View::make('user.account');
		} else {
			return Redirect::to('/');
		}	
	}//<--- End Method
	
	public function update() {
	
		$settings = AdminSettings::first();
		 
		$inputs = Input::All();
	
		
		$inputHire        = Input::get('hire');
		$inputEmailMsg    = Input::get('email_notification_msg');
		$inputEmailFollow = Input::get('email_notification_follow');
		
		// Hire
		if( !isset( $inputHire ) ) {
			$inputHire = '0';
		} else {
			$inputHire = 1;
		}
		
		// Email notification msg
		if( !isset( $inputEmailMsg ) ) {
			$inputEmailMsg = '0';
		} else {
			$inputEmailMsg = 1;
		}
		
		// Email notification follow
		if( !isset( $inputEmailFollow ) ) {
			$inputEmailFollow = '0';
		} else {
			$inputEmailFollow = 1;
		}
		
		$id_session = Auth::user()->id;

			
		$rules = array(
			'full_name' => 'required|min:3|max:25',
	        'email'     => 'required|email|unique:members,email,'.$id_session,
	     /*   'location'  => 'max:50',
	        'website'   => 'url',
	        'twitter'   => 'alpha_dash',
	        'bio'       => 'max:'.$settings->message_length.'',*/
	        'intro_video'  => 'mimes:mp4,mov,ogg,qt'
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
	            return Redirect::to('account')->withInput()->withErrors( $validation );
	        } else {
	    		$user           = User::find( $id_session );
	    	
	    		
	                
	            /*custom code*/	
	            // upload intro video
	        	if(!empty(Input::file('intro_video'))) {

	        		$fileAttach = 'public/intro_video/'.$inputs['old_video'];

		        	$intro_video = Input::file('intro_video');
		        	$ext = $intro_video->getClientOriginalExtension();
		        	$video_name = time().'.'.$ext;

		        	//<<<-- Delete Attach -->>>/
					if ( File::exists($fileAttach) ) {
						File::delete($fileAttach);	
					}//<-

		    	   	// $intro_video->move(base_path() . '/public/intro_video/', $video_name);
		    	   	$intro_video->move(base_path() . '/public/intro_video/', $video_name);
		    	   	$user->intro_video = $video_name;
		    	   
	        	}
	    	  	/*custom code*/	

				// $bio = str_replace( array( chr( 10 ), chr( 13 ) ), '' , $inputs["bio"] );

				
				// $skills = Str::slug( $inputs["skills"], $separator = ',' );
				// $skills = implode(',',array_unique(explode(',', $skills)));
				
				
				
				
	            $user->name     = trim( $inputs["full_name"] );
				$user->bio      = '';
				$user->location ='';
				$user->hire     = '';
	            $user->email    = trim( strtolower( $inputs["email"] ) );
				$user->website  = '';
				$user->skills   = '';
				$user->twitter  = '';
				$user->email_notification_follow = $inputEmailFollow;
				$user->email_notification_msg = $inputEmailMsg;
			

	            $user->update();
				Session::flash('notification',Lang::get('auth.success_update'));
	        	return Redirect::to('account');
	        }
		
	}//<--- End Method

	public function getPassword() {
	
		if( Auth::check() ) {
			return View::make('user.password');
		} else {
			return Redirect::to('/');
		}	
	}//<--- End Method
	
	public function updatePassword() {
	
	$settings = AdminSettings::first();
	 
	$inputs = Input::All();

	$id_session = Auth::user()->id;
		
	$rules = array(
		'old_password' => 'required|min:6',
        'password'     => 'required|min:6',        
    );
	
	$messages = array (
            "required"   => Lang::get('validation.required'),
            "confirmed"  => Lang::get('validation.confirmed'),
            "min"        => Lang::get('validation.min.string'),
        );
		
		
		$validation = Validator::make( $inputs, $rules, $messages );
		
		if( !Auth::validate(array('id' => Auth::user()->id, 'password' => Input::get('old_password'))) ) {
        	return Redirect::to('account/password')->with( array( 'incorrect_pass' => Lang::get('misc.password_incorrect') ) );
        } else if($validation->fails()) {
            return Redirect::to('account/password')->withInput()->withErrors( $validation );
        } else {
			
			$user            = User::find( $id_session );
            $user->password  = Hash::make($inputs[ "password"] );
            $user->update();
			Session::flash('notification',Lang::get('auth.success_update_password'));
        	return Redirect::to('account/password');
        }	
	}//<--- End Method
	
	public function avatar_cover() {
		if( Auth::check() ) {
			return View::make('user.avatar-cover');
		} else {
			return Redirect::to('/');
		}	
	}//<---- End Method

	public function report(){
	  
	  if( Auth::check() ){
	 	
		if(Request::ajax()) {
				
		$id = Input::get('user_id');
			
		$report = MembersReported::where('user_id', '=', Auth::user()->id)
		->where('id_reported', '=', $id)
		->first();

		if( isset($report->id ) ){
			 
			$report->delete();
			
		} else {
			$report = new MembersReported;
			$report->user_id = Auth::user()->id;
			$report->id_reported = $id;
			$report->save();
			
			return Response::json( array ( 'success' => true ) );
		}
		}// Ajax
	 }//Auth
	}// End Method
	
	public function myJobs(){
		 if( Auth::check() ){
		 	return View::make('user.my-jobs');
		 }//Auth
		 else {
			return Redirect::to('/');
		}
	}// End Method
	
	public function myAds(){
		 if( Auth::check() ){
		 	return View::make('user.my-ads');
		 }//Auth
		 else {
			return Redirect::to('/');
		}
	}// End Method
	
	public function blocked(){
	  
	  if( Auth::check() ){
	 	
		if(Request::ajax()) {
				
		$id = Input::get('user_id');
			
		$report = DB::table('block_user')
		->where('user_id', '=', Auth::user()->id)
		->where('user_blocked', '=', $id)
		->first();

		if( !isset($report->id ) ){
			
			$blocked = DB::table('block_user')->insert(
			array(
			'user_id' => Auth::user()->id, 
			'user_blocked' => $id) 
			);
			
			// Teams Members
			$teamsMembers = TeamMembers::where('team_id',$id)
			->where('user_id',Auth::user()->id)
			->orWhere('team_id',Auth::user()->id)
			->where('user_id',$id)
			->get();
			
			$teamsMembers->delete();
			
			// Users TeaM ID
			$userTeamID = User::where('team_id',Auth::user()->id)->get();
			
			$userTeamID->team_id = 0;
			$userTeamID->save();
			
			// Users TeaM ID 2
			$_userTeamID = User::where('team_id',$id)->where('id',Auth::user()->id)->get();
			
			$_userTeamID->team_id = 0;
			$_userTeamID->save();
			
			// Shots Teams ID
			$shotsTeams = Shots::where('team_id',Auth::user()->id)->get();
			
			foreach($shotsTeams as $shotsTeam){
				$shotsTeam->team_id = 0;
				$shotsTeam->save();
			}
			
			// Shots Teams ID USER
			$shotsTeamsUsers = Shots::where('team_id',$id)->where('user_id',Auth::user()->id)->get();
			
			foreach($shotsTeamsUsers as $shotsTeamsUser){
				$shotsTeamsUser->team_id = 0;
				$shotsTeamsUser->save();
			}
			
			// Shots
			$shots = Shots::where('user_id',Auth::user()->id)->get();
			
			// Delete Likes the User Blocked
			foreach($shots as $shot){
				
				$shots_likes = Like::where('likes.user_id', '=', $id)
				->where('shots_id',$shot->id)
				->get();
				
				foreach($shots_likes as $shots_like){
					$shots_like->delete();
				}
			}
			
			// Comments Delete
			foreach($shots as $shot){
				$comments = Comments::where('comments.user_id', '=', $id)
					->where('shots_id',$shot->id)
					->get();
				
				foreach($comments as $comment){
					$comment->delete();
				}
			}
			
			// Unfollow
			Followers::where( 'follower', Auth::user()->id )
				->where( 'following', $id )
				->orwhere('following',Auth::user()->id)
				->where( 'follower', $id )
				->update(array('status' => 0));
				
				// Delete Conversations
				
				DB::table('conversations')
				->where('user_1',Auth::user()->id )
				->where('user_2',$id )
				->orWhere('user_1',$id)
				->where('user_2',Auth::user()->id )
				->delete();
				
				// Messages Delete
			$messages = Messages::where('from_user_id', '=', $id)
				->where('to_user_id',Auth::user()->id)
				->orWhere('to_user_id', '=', $id)
				->where('from_user_id',Auth::user()->id)
				->get();
			
			foreach($messages as $message){
				
				$fileAttach    = 'public/attachment_messages/'.$message->attach_file;
				
				$message->delete();
				
				//<<<-- Delete Attach -->>>/
				if ( File::exists($fileAttach) ) {
					File::delete($fileAttach);	
				}//<--- IF FILE EXISTS
			}
			
			// Delete Notification
			$notifications = Notifications::where('author',Auth::user()->id)
			->where('destination', $id)
			->orWhere('author', $id)
			->where('destination', Auth::user()->id)
			->get(); 
			
			foreach($notifications as $notification){
				$notification->delete();
			}
			
			//<----- * Lists of Auth * ----->
			$_lists = Lists::where('user_id',Auth::user()->id)->get();
			
			foreach($_lists as $_list){
				
				$lists = ListsUsers::where('user_id',$id)
				->where('lists_id',$_list->id)
				->get();
				
				foreach($lists as $list){
					$list->delete();
				}
			}//<----- * Lists of Auth * ----->
			
			//<----- * Lists of Auth * ----->
			$_lists2 = Lists::where('user_id',$id)->get();
			
			foreach($_lists2 as $_list2){
				
				$lists2 = ListsUsers::where('user_id',Auth::user()->id)
				->where('lists_id',$_list2->id)
				->get();
				
				foreach($lists2 as $list2){
					$list2->delete();
				}
			}//<----- * Lists of Auth * ----->
			
			return Response::json( array ( 'success' => true ) );
			
			}
		}// Ajax
	 }//Auth
		 else {
			return Response::json( array ( 'session_null' => true ) );
		}
	}// End Method
	
	public function unblock(){
		if( Auth::check() ){
	 	
		if(Request::ajax()) {
			
			$user_id = Input::get('user_id');
			
			DB::table('block_user')
				->where('user_id',Auth::user()->id )
				->where('user_blocked',$user_id )
				->delete();
				
				return Response::json( array ( 'success' => true ) );
		   }// Ajax
		}//Auth
		 else {
			return Response::json( array ( 'session_null' => true ) );
		}
	}// End Method
	
	public function delete(){
		
		if( Auth::check() ){
			
			$id = Auth::user()->id;
			
			// Member of a Team
			$team_member = TeamMembers::where('user_id',$id)->first();
			$team_member->delete();
			
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
			$fileAvatar    = 'public/avatar/'.Auth::user()->avatar;
			
				if ( File::exists($fileAvatar) && Auth::user()->avatar != 'default.jpg' ) {
					File::delete($fileAvatar);	
				}//<--- IF FILE EXISTS
				
			//<<<-- Delete Cover -->>>/
			$fileCover    = 'public/cover/'.Auth::user()->cover;
			
				if ( File::exists($fileCover) && Auth::user()->cover != 'cover.jpg' ) {
					File::delete($fileCover);	
				}//<--- IF FILE EXISTS

			User::find($id)->delete();
			
			Session::flush();
			Auth::logout();
						
			return Redirect::to('/');
			
		}//Auth
		 else {
			return Redirect::to('/');
		}
	}// End Method
	
	public function oauthTwitter(){
		
		if( !Auth::check() ){
			
			$settings    = AdminSettings::first();
			
			$twitteroauth = new TwitterOAuth( $settings->twiter_appid, $settings->twitter_secret);
			
			$request_token = $twitteroauth->getRequestToken(URL::to('/').'/oauth/twitter/data');
			
			Session::put('oauth_token', $request_token['oauth_token']);
			Session::put('oauth_token_secret', $request_token['oauth_token_secret']);
			
			//$_SESSION['oauth_token']        = $request_token['oauth_token'];
			//$_SESSION['oauth_token_secret'] = $request_token['oauth_token_secret'];
			
			// If everything goes well..
			if ( $twitteroauth->http_code == 200) {
				
			    // Let's generate the URL and redirect
			    $url = $twitteroauth->getAuthorizeURL($request_token['oauth_token']);
				return Redirect::to($url);
				
			} else {
			    // It's a bad idea to kill the script, but we've got to know when there's an error.
			    die(Lang::get('misc.error'));
			}
			
		} else {
			return Redirect::to('/');
		}
	}
	
	public function get_data_twitter() {
		
		$settings = AdminSettings::first();
		
		$oauth_verifier     = Input::get('oauth_verifier');
		$oauth_token        = Session::get('oauth_token');
        $oauth_token_secret = Session::get('oauth_token_secret');
		
		if ( !empty( $oauth_verifier ) 
			&& !empty($oauth_token ) 
			&& !empty($oauth_token_secret )
		) {
			
		    // We've got everything we need
		    $twitteroauth = new TwitterOAuth( $settings->twiter_appid, $settings->twitter_secret, $oauth_token, $oauth_token_secret );
		
		// Let's request the access token
		    $access_token = $twitteroauth->getAccessToken($oauth_verifier);
		// Save it in a session var
		   Session::put('access_token', $access_token);
		   // $_SESSION['access_token'] = $access_token;
		// Let's get the user's info
		    $user_info = $twitteroauth->get('account/verify_credentials');
			
		    if ( isset( $user_info->error ) ) {
		    	
				 // Something's wrong, go back to square 1  
				return Redirect::to('oauth/twitter');
		       
		    } else {
				$twitter_otoken        = $oauth_token;
				$twitter_otoken_secret = $oauth_token_secret;
				$email                 = '';
		        $uid                   = $user_info->id;
		        $username              = $user_info->screen_name;
				$name                  = $user_info->name;
				$location              = $user_info->location;
		        
		    
			$userdata = User::where('oauth_uid',$uid)
			->where('oauth_provider','twitter')
			->where('status','active')
			->first();
			
			if( isset( $userdata ) ) {
				# User is already registered
			} else {
				
				$usernameExists = User::where('username',$username)->first();
				
				if( isset( $usernameExists ) ) {
					$_username = $username.$uid;
				} else {
					$_username = $username;
				}
				
				$token = Str::random(75);
				
				if( isset( $settings->pro_users_default ) && $settings->pro_users_default == 'on'  ){
				$typeAccount = 2;
			} else {
				$typeAccount = 1;
			}
				
				$user                  = new User;
	            $user->name            = trim( $name );
	            $user->username        = trim( $_username );
	            $user->email           = $username.'@';
				$user->password        = '';
	            $user->date            = date( 'Y-m-d G:i:s', time() );
				$user->avatar          = 'default.jpg';
				$user->cover           = 'cover.jpg';
				$user->status          = 'active';
				$user->type_account    = $typeAccount;
				$user->location        = $location;
				$user->oauth_uid       = $uid;
				$user->oauth_provider  = 'twitter';
				$user->token           = $token;
	            $user->save();
				
				$idUser = $user->id;
				
				$userdata = User::where('id',$idUser)
				->where('status','active')
				->first();
			}
		   
		    if( !empty( $userdata ) ){
		    	
				Auth::loginUsingId($userdata['id']);

		        return Redirect::to('/');
		        } else {
		        	  return Lang::get('misc.error');
		        }
		    }
		} else {
		    // Something's missing, go back to square 1
		    return Redirect::to('oauth/twitter');
		}
	}//<<-- End Method
	
	public function stats() {
		
		if( Auth::check() && Auth::user()->type_account != 1 ) {
			return View::make('user.stats');
		} else {
			return Redirect::to('/');
		}
	}//<--- End Method
	
	public function members(){
		
		if( Auth::check() && Auth::user()->type_account == 3 ) {
			
			$data = TeamMembers::where('team_members.team_id',Auth::user()->id)
			->leftjoin('members','members.id','=','team_members.user_id')
			->orderBy('team_members.id','DESC')
			->select('members.*')
			->get();
			
			return View::make('user.members')->with('data',$data);
		} else {
			return Redirect::to('/');
		}
	}//<--- End
	
	public function membersTeam($slug) {
		
		
		$settings = AdminSettings::first();
		
		$user = User::where( 'username','=', $slug )->where('type_account',3)->first();
		
		
		
		$page = Input::get('page');
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$data = User::where( 'team_id', $user->id )->paginate( $settings->result_request );
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		$total = $data->getTotal();
		
		//<<<-- * Redirect the user real name * -->>>
		$uri = Request::path();
		$uriCanonical = '@'.$user->username.'/members';
		
		if( $uri != $uriCanonical ) {
			return Redirect::to($uriCanonical);
		}
		
 		return View::make('user.team-members', compact( 'user','title', 'data', 'total' ));
	}//<--- End Method
	
	public function jobs($slug) {
		
		
		$settings = AdminSettings::first();
		
		$user = User::where( 'username','=', $slug )->where('type_account',3)->first();
		
		$page = Input::get('page');
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$dateNow   = date('Y-m-d G:i:s'); 
	 
		$data   = DB::table('jobs')
			->select(DB::raw('
					members.name, 
					members.avatar,
					members.username,
					members.type_account,
					jobs.id,
					jobs.organization_name,  
					jobs.workstation, 
					jobs.url_job, 
					jobs.location, 
					jobs.date_start, 
					jobs.date_end
			'))
			->leftjoin('members', 'jobs.user_id', '=', 'members.id')
			->leftjoin('paypal_payments_jobs as paypal', 'paypal.item_id', '=', 'jobs.id')
			->where('paypal.payment_status', '=', 'Completed')
			->where('date_end', '>=', $dateNow)
			->where('jobs.user_id', $user->id)
			->orderBy('jobs.id', 'desc')
			->paginate( $settings->result_request );
		
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		$total = $data->getTotal();
		
		//<<<-- * Redirect the user real name * -->>>
		$uri = Request::path();
		$uriCanonical = '@'.$user->username.'/jobs';
		
		if( $uri != $uriCanonical ) {
			return Redirect::to($uriCanonical);
		}
		
 		return View::make('user.team-jobs', compact( 'user','title', 'data', 'total' ));
	}//<--- End Method
	
	public function goods($slug){
		
		$settings = AdminSettings::first();
		
		$user     = User::where( 'username','=', $slug )->where('type_account','!=',1)->first();
		
		$page = Input::get('page');
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}

		 $data   = Shots::where('url_purchased','!=','')
		 ->where('status',1)
		 ->where('user_id',$user->id)
		 ->orderBy('id','DESC')
		 ->paginate( $settings->result_request ); //$settings->result_request
		 
		 
		 $title    = e( $user->name ).' - '.Lang::get('misc.goods_for_sale').' - ';
		 
		 //<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		$total = $data->getTotal();
		
		//<<<-- * Redirect the user real name * -->>>
		$uri = Request::path();
		$uriCanonical = '@'.$user->username.'/goods';
		
		if( $uri != $uriCanonical ) {
			return Redirect::to($uriCanonical);
		}
		
		$total = $data->getTotal();
		 
		return View::make('user.goods', compact( 'user','title', 'data', 'total' ));
		
	}//<--- End
	
	public function getFile($filename)
	{	

		return Response::make(null, 403); // forbidden

	}

	

}
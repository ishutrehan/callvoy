<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

/*Route::get('/', function () {
    return view('welcome');
});*/



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

/*
 *-----------------------
 * Language Route
 * ---------------------- 
 * 
 */
/* App::before(function($request)
{
   $language = Session::get('language',Config::get('app.locale'));
   
   //echo Session::get('language');

   	App::setLocale($language);
   
});*/



Route::get('lang/{id}', function($id){
	
	$lang = Languages::where( 'abbreviation','=', $id )->first();
	
	$total = count( $lang );
	
	if( $total == 0 ) {
		App::abort('404');
	} else {
		Session::put('language', $lang->abbreviation);
   		 return Redirect::back();
	}
	
})->where(array( 'id' => '[a-z]+'));

//Route::controller('lang', 'LangController');


/* 
 |
 |-----------------------------------
 | Index, Popular Views and Search
 |--------- -------------------------
 */
Route::get('/', 'HomeController@getIndex');
Route::get('latest', 'HomeController@getLatest');
Route::get('most/commented', 'HomeController@commented');
Route::get('most/viewed', 'HomeController@viewed');
Route::get('popular', 'HomeController@getPopular');
Route::get('search', 'HomeController@getSearch');

//<---- * Upgrades * --->
// Route::controller('upgrade', 'UpgradeController');

// LOGOUT
Route::get('session/logout', function(){
	
	Session::flush();
	Auth::logout();
	
	return Redirect::to('/');
});


Route::get('projects', 'ProjectsController@projects');

Route::get('search/users', function(){
	
	$q = trim(Input::get('q'));
	
	//<--- * If $q is empty or is minus to 1 * ---->
	if( $q == '' || strlen( $q ) <= 1 ){
		return Redirect::to('/');
	}

	return View::make('default.search-users');
});

Route::get('tags', function(){
	return View::make('default.tags');
});

Route::get('tags/{tags}','HomeController@tags' );

/* 
 |
 |----------------------------
 | Jobs Views
 |--------- ------------------
 */
// Route::controller('jobs', 'JobsController');

Route::get('jobs', function(){
	return View::make('default.jobs');
});

Route::get('jobs/new', function(){
	
});
Route::post('jobs', 'JobsController@add');

// Payment Job
Route::post('jobs/payment', 'JobsController@payment');

// Paypal IPN
Route::post('jobs/paypalipn', 'JobsController@paypalipn');

Route::post('payment/jobs/success', function(){
		
	if ( Auth::check() ){
		Session::flash('success_add_job',Lang::get('misc.success_add_job'));
		return Redirect::to('jobs');
	} else {
		return Redirect::to('/');
	}
});
// Jobs Renew Activate
Route::get('activate/job/{token}', function($token){
	
	if( Auth::check() ) {
		
		$dateNow   = date('Y-m-d G:i:s');
		$findJob = Jobs::where('token',$token)->where('user_id',Auth::user()->id)->first();
		
		if( $findJob->date_end >= $dateNow ) {
        		return Redirect::to('my/jobs');
        	} else {
        		return View::make('default.activate-job',compact('token','$token'));
        	}
			
	} else {
		return Redirect::to('/');
	}
	
	
})->where(array( 'token' => '[A-Za-z0-9]+'));


/* 
 |
 |----------------------------
 | Goods for Sale View
 |--------- ------------------
 */
Route::get('goods', function(){
		
	return View::make('default.goods');
});

/* 
 |
 |----------------------------
 | Designers Views
 |--------- ------------------
 */
Route::get('designers', function(){
		
	/*$sort = Input::get('sort');
	
	if( isset( $sort ) && $sort != '' ) {
		return Redirect::to('designers');
}*/
	return View::make('default.designers');
});

Route::get('designers/prospects', function(){
		
	return View::make('default.designers-prospects');
});
/* 
 |
 |----------------------------
 | Teams Views
 |--------- ------------------
 */
Route::get('teams', function(){
		
	return View::make('default.teams');
});

/* 
 |
 |------------------------
 | Shot Views
 |--------- --------------
 */
 Route::get('shots/{id}-{slug?}', 'ShotController@index')->where( array( 'id' => '[0-9]+', 'slug' => '[A-Za-z0-9\_-]+'));
 
 /* 
 |
 |------------------------
 | Shot Views Likes
 |--------- --------------
 */
 Route::get('shots/{id}-{slug}/likes', 'ShotController@likes')->where( array( 'id' => '[0-9]+', 'slug' => '[A-Za-z0-9\_-]+'));
 Route::get('shots/{id}/likes', 'ShotController@likes')->where( array( 'id' => '[0-9]+'));
 
 
/*
 *-----------------------
 * Ajax Route
 * ---------------------- 
 */
// Route::controller('ajax', 'AjaxController');

/* 
 |
 |------------------------
 | User Views
 |--------- --------------
 */
// Shots
Route::get('@{slug}', 'UserController@profile')->where('slug','[A-Za-z0-9\_-]+');

// Likes
Route::get('@{slug}/likes', 'UserController@likes')->where('slug','[A-Za-z0-9\_-]+');

// Followers
Route::get('@{slug}/followers', 'UserController@followers')->where('slug','[A-Za-z0-9\_-]+');

// Following
Route::get('@{slug}/following', 'UserController@following')->where('slug','[A-Za-z0-9\_-]+');

// Members - Teams
Route::get('@{slug}/members', 'UserController@membersTeam')->where('slug','[A-Za-z0-9\_-]+');

// Jobs - Teams
Route::get('@{slug}/jobs', 'UserController@jobs')->where('slug','[A-Za-z0-9\_-]+');

// Goods for sale
Route::get('@{slug}/goods', 'UserController@goods')->where('slug','[A-Za-z0-9\_-]+');

// Report User
Route::post('report/user', 'UserController@report');

// Block User
Route::post('block/user', 'UserController@blocked');

// Unblock User
Route::post('unblock/user', 'UserController@unblock');

// Delete Account
Route::get('delete/account', 'UserController@delete');

//Projects
Route::get('@{slug}/projects', 'ProjectsController@show')->where('slug','[A-Za-z0-9\_-]+');
Route::post('add/project', 'ProjectsController@add');
Route::post('edit/project', 'ProjectsController@edit');
Route::post('delete/project', 'ProjectsController@delete');
Route::get('@{slug}/projects/{id}-{title?}', 'ProjectsController@detail')->where(array( 'slug' => '[A-Za-z0-9\_-]+', 'id' => '[0-9]+'));

// Lists
Route::get('@{slug}/lists', 'ListsController@show')->where('slug','[A-Za-z0-9\_-]+');
Route::get('@{slug}/lists/memberships', 'ListsController@listed')->where('slug','[A-Za-z0-9\_-]+');
Route::get('@{slug}/lists/{id}-{title?}', 'ListsController@detail')->where(array( 'slug' => '[A-Za-z0-9\_-]+', 'id' => '[0-9]+'));
Route::get('@{slug}/lists/{id}-{title?}/members', 'ListsController@members')->where(array( 'slug' => '[A-Za-z0-9\_-]+', 'id' => '[0-9]+'));
Route::get('@{slug}/lists/{id}/members', 'ListsController@members')->where(array( 'slug' => '[A-Za-z0-9\_-]+', 'id' => '[0-9]+'));
Route::get('{id}/lists', 'ListsController@add_remove_ajax')->where(array( 'id' => '[0-9]+'));
Route::get('list/{id}/u/{user}', 'ListsController@add_user_list')->where(array( 'id' => '[0-9]+','user' => '[0-9]+'));

// Add, Edit and Delete Lists
// Route::controller('lists', 'ListsController');
/* 
 |
 |------------------------
 | User Views LOGGED
 |--------- --------------
 */

 //Clicks Advertising
Route::get('click/ads/{id}', 'AdvertisingController@clicks')->where(array( 'id' => '[0-9]+'));
	
	
//<---- * Ajax Ads * --->
// Route::controller('ads', 'AdvertisingController');
	
Route::group(array('before' => 'auth'), function() {
	 
	 //** Account Settings **/
	Route::get('account', 'UserController@getAccount');
	Route::post('account', 'UserController@update');
	Route::get('account/password', 'UserController@getPassword');
	Route::post('account/password', 'UserController@updatePassword');
	Route::get('account/avatar_cover', 'UserController@avatar_cover');
	Route::get('members', 'UserController@members');
	
	// Jobs User
	Route::get('my/jobs', 'UserController@myJobs');
	Route::get('edit/job/{token}', 'JobsController@getEdit')->where(array( 'token' => '[A-Za-z0-9]+'));
	Route::post('edit/job/{token}', 'JobsController@edit')->where(array( 'token' => '[A-Za-z0-9]+'));
	Route::get('delete/job/{token}', 'JobsController@delete')->where(array( 'token' => '[A-Za-z0-9]+'));
	
	// Ads
	Route::get('my/ads', 'UserController@myAds');
	
	Route::get('ad/new', function(){

		
		$settings    = AdminSettings::first();
		
		if( Auth::check() && $settings->allow_ads == 'on' ) {
			return View::make('user.ad-new');
		} else {
			return Redirect::to('/login');
		}
	});//<-- Function Ads
	
	// Delete Ads
	Route::get('delete/ad/{code}', 'AdvertisingController@delete')->where(array( 'code' => '[A-Za-z0-9]+'));
	
	// Edit Ads
	Route::get('edit/ad/{code}', 'AdvertisingController@getEdit')->where(array( 'code' => '[A-Za-z0-9]+'));
	Route::post('edit/ad/{code}', 'AdvertisingController@edit')->where(array( 'code' => '[A-Za-z0-9]+'));
	
	// Activate Ad
	Route::get('ads/activate/{code}', 'AdvertisingController@getActivate')->where(array( 'code' => '[A-Za-z0-9]+'));
	
	//** Messages **//
	Route::get('messages', 'MessagesController@inbox');
	Route::get('messages/{id}-{user?}', 'MessagesController@messages')->where( array( 'id' => '[0-9]+', 'user' => '[A-Za-z0-9\_-]+'));
	// Route::controller('message', 'MessagesController');
	
	//** Notifications **/
	Route::get('notifications', 'UserController@getNotifications');
	Route::get('notifications/delete', function(){
		
		Notifications::where('destination',Auth::user()->id)
		->update(array('status' => 1, 'trash' => 1)); 
		
		return Redirect::to('notifications');
	});
	
	/**** Upload Shot ****/
	Route::get('upload', function(){
		
		if( Auth::user()->type_account == 2 || Auth::user()->type_account == 3 || Auth::user()->team_id != 0 ) {
			return View::make('default.upload');
		} else {
			return Redirect::to('/');
		}
		
	});
	
	/******* COMMENT ********/
	// Route::controller('comment', 'CommentsController');
	
	
	/*********STATS**********/
	Route::get('stats','UserController@stats');
	
});//<---------- * Before * --------->

Route::group(array('before' => 'role'), function() {
	
	// Admin Settings
	Route::get('panel/admin','AdminController@admin');
	Route::post('panel/admin','AdminController@settings');
	
	// Pages
	Route::get('panel/admin/pages', function(){
	
	return View::make('admin.pages');
	
	});
	// Add Pages
	Route::get('panel/admin/pages/new', function(){
	
	return View::make('admin.add-page');
	
	});
	Route::post('panel/admin/pages/new','AdminController@add_page');
	
	// Edit Page
	Route::get('panel/admin/pages/edit/{id}', function($id){
	
	if( Pages::find( $id ) ) {
		return View::make('admin.edit-page',compact('id','$id'));
	} else {
		App::abort('404');
	}
	
	
	})->where( array( 'id' => '[0-9]+'));
	
	Route::post('panel/admin/pages/edit/{id}', 'AdminController@edit_page')->where(array( 'id' => '[0-9]+'));
	
	// Delete Page
	Route::get('panel/admin/pages/delete/{id}', 'AdminController@delete_page')->where(array( 'id' => '[0-9]+'));
	
	// All Members
	Route::get('panel/admin/members', function(){
	
	return View::make('admin.members');
	
	});
	
	// Edit Members
	Route::get('panel/admin/members/edit/{id}', function($id){
	
	$members = User::where('id',$id)->where('id','!=',1)->where('id','!=',Auth::user()->id)->first();
	
	if( isset( $members ) ) {
		return View::make('admin.edit-member',compact('id','$id'));
	} else {
		App::abort('404');
	}
	})->where( array( 'id' => '[0-9]+'));
	
	Route::post('panel/admin/members/edit/{id}', 'AdminController@edit_member')->where(array( 'id' => '[0-9]+'));
	
	// Delete User
	Route::get('panel/admin/members/delete/{token}', 'AdminController@delete_user')->where(array( 'token' => '[A-Za-z0-9]+'));
	
	// Members reported
	Route::get('panel/admin/members-reported', function(){
	
	return View::make('admin.members-reported');
	
	});
	
	// Delete report
	Route::get('panel/admin/members-reported/delete/{id}', 'AdminController@delete_report_member')->where(array( 'id' => '[0-9]+'));
	
	
	// Shots reported
	Route::get('panel/admin/shots-reported', function(){
	
	return View::make('admin.shots-reported');
	
	});
	
	// Delete report Shots
	Route::get('panel/admin/shots-reported/delete/{id}', 'AdminController@delete_report_shots')->where(array( 'id' => '[0-9]+'));
	
	
	// Payments Settings
	Route::get('panel/admin/payments-settings', function(){
	
	return View::make('admin.payments-settings');
	
	});
	
	Route::post('panel/admin/payments-settings','AdminController@payments_settings');
	
	// Social Login
	Route::get('panel/admin/social-login', function(){
	
	return View::make('admin.social-login');
	
	});
	
	Route::post('panel/admin/social-login','AdminController@social_login');
	
	// Profiles Social
	Route::get('panel/admin/profiles-social', function(){
	
	return View::make('admin.profiles-social');
	
	});
	
	Route::post('panel/admin/profiles-social','AdminController@profiles_social');
	
	
	// Payments
	Route::get('panel/admin/payments', function(){
	return View::make('admin.payments');
	});
	
	// Payments Jobs
	Route::get('panel/admin/payments-jobs', function(){
	return View::make('admin.payments-jobs');
	});
	
	// Payments ADS
	Route::get('panel/admin/payments-ads', function(){
	return View::make('admin.payments-ads');
	});
	
	// Manage Lists
	Route::get('panel/admin/lists', function(){
	return View::make('admin.lists');
	});
	
	// Delete Lists
	Route::get('panel/admin/lists/delete/{id}', 'AdminController@delete_lists')->where(array( 'id' => '[0-9]+'));
	
	
	// Manage projects
	Route::get('panel/admin/projects', function(){
	return View::make('admin.projects');
	});
	
	// Delete projects
	Route::get('panel/admin/projects/delete/{id}', 'AdminController@delete_projects')->where(array( 'id' => '[0-9]+'));
	
	
	// Manage Jobs
	Route::get('panel/admin/jobs', function(){
	return View::make('admin.jobs');
	});
	
	Route::get('panel/admin/jobs/edit/{id}', function($id){
		
	$data = Jobs::where('token',$id)->first();
	
	if( !isset( $data ) ) {
		App::abort('404');
	}

	return View::make('admin.edit-jobs',compact( 'id', $id, 'data', $data ));
	
	})->where(array( 'id' => '[A-Za-z0-9]+'));
	
    // Edit Job
	Route::post('panel/admin/jobs/edit/{id}', 'AdminController@edit_job')->where(array( 'id' => '[A-Za-z0-9]+'));
	
	//Delete Job
	Route::get('panel/admin/jobs/delete/{token}', 'AdminController@delete_job')->where(array( 'token' => '[A-Za-z0-9]+'));
	
	Route::get('panel/admin/ads', function(){
	return View::make('admin.ads');
	});
	
	Route::get('panel/admin/ads/details/{code}', function($code){
		
	$data = Advertising::where('code',$code)->first();
	
	if( !isset( $data ) ) {
		App::abort('404');
	}
		
	return View::make('admin.details-ads',compact( 'code', $code, 'data', $data ));
	
	
	})->where(array( 'id' => '[A-Za-z0-9]+'));
	
	//Delete Ads
	Route::get('panel/admin/ads/delete/{code}', 'AdminController@delete_ads')->where(array( 'code' => '[A-Za-z0-9]+'));
	
	// Stats
	Route::get('panel/admin/statistics', function(){
	return View::make('admin.statistics');
	});
	
	// Google Adsense
	Route::get('panel/admin/google-adsense', function(){
	
	return View::make('admin.google-adsense');
	
	});
	
	Route::post('panel/admin/google-adsense','AdminController@google_adsense');
	
	
	// Comments
	Route::get('panel/admin/comments', function(){
	
	return View::make('admin.comments');
	
	});
	
	
	//***** Languages
		
	Route::get('panel/admin/languages', function(){
	
	return View::make('admin.languages');
	
	});
	// ADD NEW
	Route::get('panel/admin/languages/new', function(){
	
	return View::make('admin.add-languages');
	
	});
	// ADD NEW POST
	Route::post('panel/admin/languages/new','LangController@add_lang');
	
	// EDIT LANG
	Route::get('panel/admin/languages/edit/{id}', function($id){
	
	if( Languages::find( $id ) ) {
		return View::make('admin.edit-languages',compact('id','$id'));
	} else {
		App::abort('404');
	}
	
	
	})->where( array( 'id' => '[0-9]+'));
	
	// EDIT LANG POST
	Route::post('panel/admin/languages/edit/{id}', 'LangController@edit_lang')->where(array( 'id' => '[0-9]+'));
	
	// DELETE LANG
	Route::get('panel/admin/languages/delete/{id}', 'LangController@delete_lang')->where(array( 'id' => '[0-9]+'));
	
	
	
	
	
});//<---------- * Before * --------->
/* 
 |
 |-----------------------------------
 | Shots Actions
 |--------- -------------------------
 */
Route::post('report/shot', 'ShotController@report');
Route::post('shot/edit', 'ShotController@edit');
Route::post('delete/shot', 'ShotController@delete');


/* 
 |
 |-----------------------------------
 | Join and Login
 |--------- -------------------------
 */
Route::get('join', 'UserController@getJoin');

// Account Normal
Route::get('join-normal', 'UserController@getJoinNormal');
Route::post('join-normal', 'UserController@join');

// Team Account
Route::get('join-team', 'UserController@getJoinTeam');
Route::post('join-team', 'UserController@joinTeam');

Route::post('payment/team/success', function(){
	
	$settings = AdminSettings::first();
	
	/*if( Auth::check() ) {
		//Session::flash('welcome',Lang::get('misc.wecolme_users',['name' => Auth::user()->name, 'site_name' => $settings->title ]));
	}*/
	Session::flash('success_account',Lang::get('auth.success_account'));
     return Redirect::to('login');
});

Route::get('renew/membership', function(){
	
	// TEAMS Membership Check
	   if( Auth::check() && Auth::user()->type_account == 3 ) {
	   	
		$_dateNow   = date('Y-m-d G:i:s');
		   	
		$teamMembershipStatus = DB::table('paypal_payments_teams')
		->where('user_id', Auth::user()->id)
		->where('expire','>',$_dateNow)
		->where('payment_status', '=', 'Completed')
		->orderBy('id', 'desc')
		->first(); 
		
		if( !isset( $teamMembershipStatus ) ) {
			return View::make('auth.renew-team');
		} else {
			return Redirect::to('members');
		}
	} else {
		App::abort('404');
	}
});

Route::post('renew/team/success', function(){
	
	$settings = AdminSettings::first();
	
	if( Auth::check() ) {
		Session::flash('success_renew',Lang::get('misc.success_renew'));
	}
     return Redirect::to('members');
});


Route::get('login', 'UserController@getLogin');
Route::post('login', 'UserController@login');

/* 
 |
 |-----------------------------------
 | Verify Account
 |--------- -------------------------
 */
Route::get('verify/account/{confirmation_code}', 'HomeController@getVerifyAccount')->where('confirmation_code','[A-Za-z0-9]+');

/* 
 |
 |------------------------
 | Recover password
 |--------- --------------
 */
Route::get('recover/password', array(
	'uses' => 'UserController@recover_pass',
	'as' => 'recover.pass'
));
Route::post('recover/password', array(
	'uses' => 'UserController@recover_request',
	'as' => 'recover.request'
));
/* 
 |
 |------------------------
 | Password Reset
 |--------- --------------
 */
Route::get('password_reset/{token}', array(
  'uses' => 'UserController@password_reset_token',
  'as' => 'password.reset'
));

Route::post('password_reset/{token}', array(
  'uses' => 'UserController@password_reset_post',
  'as' => 'password.update'
));

/* 
 |
 |----------------------------
 | API
 |--------- ------------------
 */
Route::get('api', function(){
	
	return View::make('pages.api');
});

Route::get('api/username/{username}' , function($username){
	return Response::view('pages.api-json',compact('username','$username'))->header('Content-Type', 'text/plain');
	
})->where(array( 'username' => '[A-Za-z0-9\_-]+'));

Route::get('api/username/{username}/shots' , function($username){
	return Response::view('pages.api-json-shots',compact('username','$username'))->header('Content-Type', 'text/plain');
	
})->where(array( 'username' => '[A-Za-z0-9\_-]+'));

/* 
 |
 |----------------------------
 | Sitemaps
 |--------- ------------------
 */
Route::get('sitemaps.xml', function(){
	return Response::view('default.sitemaps')->header('Content-Type', 'application/xml');
	
});
/* 
 |
 |----------------------------
 | Oauth Twitter
 |--------- ------------------
 */
Route::get('oauth/twitter', 'UserController@oauthTwitter');
Route::get('oauth/twitter/data', 'UserController@get_data_twitter');

Route::get('public/intro_video/{filename}', 'UserController@getFile')->where('filename', '.*');





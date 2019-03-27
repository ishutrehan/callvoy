<?php
namespace App\Http\Controllers;

use App\Models\AdminSettings;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
// use GetStream\StreamLaravel\Eloquent\ActivityTrait;

use View;
use DB;
class HomeController extends BaseController {
	public function __construct(){
		// $client = new GetStream\Stream\Client('nfsbvxjwf9np', 'mscur3u9zzfp38cazbgfms3mq42vjxdep8dnzdn3esm2nhh5dh57632ksccpvp95');
	}
	public function getIndex() {
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		if( isset( $page ) && !Auth::check() ){
			return Redirect::to('latest');
		}
		
		if( !Auth::check() ) {
			$data = Shots::where('status',1)
			->orderBy( 'id', 'desc' )
			->paginate( $settings->result_request );
		} else {
			
			$data = Shots::leftjoin('followers', 'shots.user_id', '=', DB::raw('followers.following AND followers.status = "1"'))
			->where('shots.status',1)
			->where('followers.follower', '=', Auth::user()->id )
			->groupBy('shots.id')
			->orderBy( 'shots.id', 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
			}
		
		/*if( $page > $data->getLastPage() ) {
			App::abort('404');
		}*/
				
		return View::make('index.home')->with( 'data', $data );
	}
	
	public function getVerifyAccount( $confirmation_code ) {
			  
	  if( !Auth::check() ) {
		$user = User::where( 'activation_code', $confirmation_code )->where('status','pending')->first();
		
		if( $user ) {
			
			$update = User::where( 'activation_code', $confirmation_code )
			->where('status','pending')
			->update( array( 'status' => 'active', 'activation_code' => '' ) );
			

			Auth::loginUsingId($user->id);
			
			Session::flash('success_verify', Lang::get('users.account_validated'));
						
			return Redirect::to('/');
		} else {
			return View::make('auth.verify-account');
		}
				
		} else {
			 return Redirect::to('/');
		}
	}
	
	public function getLatest(){
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$title = Lang::get('misc.last_shots').' - ';
		
		$data = Shots::where('status',1)
		->orderBy( 'id', 'desc' )
		->paginate( $settings->result_request );
		
		/*if( $page > $data->getLastPage() ) {
			App::abort('404');
		}*/
				
		return View::make('index.latest')->with( array( 'data' => $data, 'title' => $title ) );
	}
	
	public function getPopular() {
		
		$timeframe = Input::get('timeframe');
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$title = Lang::get('misc.popular_shots').' - ';
		
		// All time
		$data = Shots::where('shots.status',1)
		->join('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
		->groupBy('likes.shots_id')
		->orderBy( DB::raw('COUNT(likes.shots_id)'), 'desc' )
		->select('shots.*')
		->paginate( $settings->result_request );
		
		// Week
		if( $timeframe == 'week' ){
			$data = Shots::where('shots.status',1)
			->join('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
			->where('likes.date', '>=', date('Y-m-d H:i:s', strtotime('-1 week')))
			->groupBy('likes.shots_id')
			->orderBy( DB::raw('COUNT(likes.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
		}
		
		// Month
		if( $timeframe == 'month' ){
			$data = Shots::where('shots.status',1)
			->join('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
			->where('likes.date', '>=', date('Y-m-d H:i:s', strtotime('-1 month')))
			->groupBy('likes.shots_id')
			->orderBy( DB::raw('COUNT(likes.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
		}

		// Year
		if( $timeframe == 'year' ){
			$data = Shots::where('shots.status',1)
			->join('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
			->where('likes.date', '>=', date('Y-m-d H:i:s', strtotime('-1 year')))
			->groupBy('likes.shots_id')
			->orderBy( DB::raw('COUNT(likes.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
		}
		
		
		/*
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}*/

		return View::make('index.popular', compact( 'data','title' ));
	}
	
	public function commented() {
		
		$timeframe = Input::get('timeframe');
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$title = Lang::get('misc.most_commented').' - ';
		
		
		
		$data = Shots::where('shots.status',1)
		->join('comments', 'shots.id', '=', DB::raw('comments.shots_id AND comments.status = "1"'))
		->groupBy('comments.shots_id')
		->orderBy( DB::raw('COUNT(comments.shots_id)'), 'desc' )
		->select('shots.*')
		->paginate( $settings->result_request );
		
		// Week
		if( $timeframe == 'week' ){
			$data = Shots::where('shots.status',1)
			->join('comments', 'shots.id', '=', DB::raw('comments.shots_id AND comments.status = "1"'))
			->where('comments.date', '>=', date('Y-m-d H:i:s', strtotime('-1 week')))
			->groupBy('comments.shots_id')
			->orderBy( DB::raw('COUNT(comments.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
		}
		
		// Month
		if( $timeframe == 'month' ){
			$data = Shots::where('shots.status',1)
			->join('comments', 'shots.id', '=', DB::raw('comments.shots_id AND comments.status = "1"'))
			->where('comments.date', '>=', date('Y-m-d H:i:s', strtotime('-1 month')))
			->groupBy('comments.shots_id')
			->orderBy( DB::raw('COUNT(comments.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
		}

		// Year
		if( $timeframe == 'year' ){
			$data = Shots::where('shots.status',1)
			->join('comments', 'shots.id', '=', DB::raw('comments.shots_id AND comments.status = "1"'))
			->where('comments.date', '>=', date('Y-m-d H:i:s', strtotime('-1 year')))
			->groupBy('comments.shots_id')
			->orderBy( DB::raw('COUNT(comments.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );		
		}
		/*
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}*/

		return View::make('index.most-commented', compact( 'data','title' ));
	}
	
	public function viewed() {
		
		$timeframe = Input::get('timeframe');
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$title = Lang::get('misc.most_viewed').' - ';
		
		$data = Shots::where('shots.status',1)
		->join('visits', 'shots.id', '=', DB::raw('visits.shots_id'))
		->groupBy('visits.shots_id')
		->orderBy( DB::raw('COUNT(visits.shots_id)'), 'desc' )
		->select('shots.*')
		->paginate( $settings->result_request );
		
		// Week
		if( $timeframe == 'week' ){
			$data = Shots::where('shots.status',1)
			->join('visits', 'shots.id', '=', DB::raw('visits.shots_id'))
			->where('visits.date', '>=', date('Y-m-d H:i:s', strtotime('-1 week')))
			->groupBy('visits.shots_id')
			->orderBy( DB::raw('COUNT(visits.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
		}
		
		// Month
		if( $timeframe == 'month' ){
			$data = Shots::where('shots.status',1)
			->join('visits', 'shots.id', '=', DB::raw('visits.shots_id'))
			->where('visits.date', '>=', date('Y-m-d H:i:s', strtotime('-1 month')))
			->groupBy('visits.shots_id')
			->orderBy( DB::raw('COUNT(visits.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );
		}

		// Year
		if( $timeframe == 'year' ){
			$data = Shots::where('shots.status',1)
			->join('visits', 'shots.id', '=', DB::raw('visits.shots_id'))
			->where('visits.date', '>=', date('Y-m-d H:i:s', strtotime('-1 year')))
			->groupBy('visits.shots_id')
			->orderBy( DB::raw('COUNT(visits.shots_id)'), 'desc' )
			->select('shots.*')
			->paginate( $settings->result_request );		
		}
		/*
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}*/

		return View::make('index.most-viewed', compact( 'data','title' ));
	}

	
	public function getSearch() {
		
		$q = trim(Input::get('q'));
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$data = Shots::where( 'title','LIKE', '%'.$q.'%' )
		->where('status', 1 )
		->orWhere( 'description','LIKE', '%'.$q.'%' )
		->where('status', 1 )
		->orWhere( 'tags','LIKE', '%'.$q.'%' )
		->where('status', 1 )
		->groupBy('id')
		->orderBy('id', 'desc' )
		->paginate( $settings->result_request );

		
		$title = Lang::get('misc.result_of').' '. $q .' - ';
		
		$total = $data->getTotal();
		
		//<--- * If $page not exists * ---->
		/*if( $page > $data->getLastPage() ) {
			App::abort('404');
		}*/
		
		//<--- * If $q is empty or is minus to 1 * ---->
		if( $q == '' || strlen( $q ) <= 1 ){
			return Redirect::to('/');
		}
		
		return View::make('default.search', compact( 'data', 'title', 'total', 'q' ));
	}
	
	public function tags($tags) {
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$data = Shots::where( 'tags','LIKE', '%'.$tags.'%' )
		->where('status', 1 )
		->groupBy('id')
		->orderBy('id', 'desc' )
		->paginate( $settings->result_request );

		
		$title = Lang::get('misc.tags').' - '. ucfirst( $tags ).' - ';
		
		$total = $data->getTotal();
		
		//<--- * If $page not exists * ---->
		/*if( $page > $data->getLastPage() ) {
			App::abort('404');
		}*/
		
		return View::make('default.tags-show', compact( 'data', 'title', 'total', 'tags' ));
	}


}//<<-- End
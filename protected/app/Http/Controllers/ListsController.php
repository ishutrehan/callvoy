<?php
namespace App\Http\Controllers;
use App\Models\AdminSettings;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use View;
class ListsController extends BaseController {

	protected $lists;

	public function __construct(Lists $lists){
		$this->lists = $lists;
	}
	
	public function show( $slug ) {
		
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$user = User::where( 'username','=', $slug )->first();
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		if( Auth::check() ) {
			$AuthId = Auth::user()->id;
		} else {
			$AuthId = 0;
		}
		
		$data = $this->lists->where( 'user_id', $user->id )
		->where('type',1)
		->orWhere('user_id', $AuthId)
		->where('type',0)
		->orderBy('id','desc')
		->paginate( $settings->result_request );
				
		$total = 0;
		
		/*foreach ($data as $key) {
			$total = $key->shots->count();
		}*/
		
		//$total = $data->getTotal();
		
		$totalGlobal = $data->getTotal();
		
		$array = array(
		'data' => $data,
		'total' => $total,
		'totalGlobal' => $totalGlobal,
		'user' => $user,
		);
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		return View::make('user.lists')->with($array);
			
	}//<--- END METHOD
	
	public function members( $slug, $id ) {
		
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$user = User::where( 'username','=', $slug )->first();
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		if( Auth::check() ) {
			$AuthId = Auth::user()->id;
			$role = Auth::user()->role;
		} else {
			$AuthId = 0;
			$role = 0;
		}
		
		// Check List ID
		$lists = Lists::where('id',$id)->where('user_id',$user->id)->first();
		
		$data = ListsUsers::where( 'lists_users.lists_id', $id )
		->join( 'lists', 'lists_users.lists_id', '=', 'lists.id' )
		->join( 'members', 'lists_users.user_id', '=', 'members.id' )
		->where('lists.user_id', $user->id)
		->where('lists.type',1)
		->orWhere('lists.user_id', $AuthId)
		->where('lists.type',0)
		->groupBy('lists_users.user_id')
		->orderBy('lists_users.id','desc')
		->paginate( $settings->result_request );
				
		$total = 0;
		
		$totalGlobal = $data->getTotal();
		
		$array = array(
		'data' => $data,
		'total' => $total,
		'totalGlobal' => $totalGlobal,
		'lists' => $lists,
		'user' => $user,
		);
		
		if( empty( $user ) || $user->status != 'active' 
			|| empty( $lists ) 
			|| $lists->type == 0 
			&& $user->id != $AuthId 
			&& $role == 'normal'
			) {
			App::abort('404');
		}
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		return View::make('user.lists-members')->with($array);
			
	}//<--- END METHOD
	
	public function listed( $slug ) {
		
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$user = User::where( 'username','=', $slug )->first();
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$data = ListsUsers::where( 'lists_users.user_id', $user->id )
		->join('lists', 'lists_users.lists_id', '=', DB::raw('lists.id AND lists.type = "1"') )
		->orderBy('lists_users.id','desc')
		->paginate( $settings->result_request );

		$total = 0;
		
		/*foreach ($data as $key) {
			$total = $key->shots->count();
		}*/
		
		//$total = $data->getTotal();
		
		$totalGlobal = $data->getTotal();
		
		$array = array(
		'data' => $data,
		'total' => $total,
		'totalGlobal' => $totalGlobal,
		'user' => $user,
		);
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		return View::make('user.listed')->with($array);
			
	}//<--- END METHOD
	
	public function postAdd(){
	  
	  if( Auth::check() ) {
	  		
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		// Setup the validator
		$rules = array(
			'name'       => 'required|min:3|max:30',
			'description' => 'min:3|max:'.$settings->message_length.'',
			);

		$validator = Validator::make($inputs, $rules);
		
		if( $validator->fails() ) {
			Session::flash('error_add',Lang::get('misc.error'));
            return Redirect::back()->withErrors( $validator );
		} else {
			if( !empty( $inputs['description'] ) ) {
				$description = Helper::checkTextDb($inputs['description']);
			} else {
				$description = '';
			}
			
			$lists              = new Lists;
			$lists->user_id     = Auth::user()->id;
			$lists->name        = trim($inputs['name']);
			$lists->description = $description;
			$lists->type        = $inputs['type'];
			$lists->save();
			
			 return Redirect::back()->with(array( 'success_add' => Lang::get('misc.success_add_list') ));
		}
	  }
	}//<--- END
	
	public function postEdit(){
	  
	  if( Auth::check() ) {
	  		
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		// Check List ID
		$lists = Lists::where('id',$inputs['id'])->where('user_id',Auth::user()->id)->first();
		
		if( isset( $lists ) ){
			
			// Setup the validator
			$rules = array(
				'name'       => 'required|min:3|max:30',
				'description' => 'min:3|max:'.$settings->message_length.'',
				);
	
			$validator = Validator::make($inputs, $rules);
			
			if( $validator->fails() ) {
				Session::flash('error_add',Lang::get('misc.error'));
	            return Redirect::back()->withErrors( $validator );
			} else {
				if( !empty( $inputs['description'] ) ) {
					$description = Helper::checkTextDb($inputs['description']);
				} else {
					$description = '';
				}
				
				$lists->name         = trim($inputs['name']);
				$lists->description  = $description;
				$lists->type         = $inputs['type'];
				$lists->save();
				
				 return Redirect::back()->with(array( 'success_add' => Lang::get('misc.success_update_list') ));
			}//<--- Isset Project
		} else {
			return Redirect::to('/');
		}
	  }
	}//<--- END
	
	public function postDelete(){
	  
	  if( Auth::check() ) {
	  		
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		// Check List ID
		$lists = Lists::where('id',$inputs['lists_id'])->where('user_id',Auth::user()->id)->first();
		
		if( isset( $lists ) ){

				$lists->delete();
			
			$lists_users = ListsUsers::where('lists_id', '=', $inputs['lists_id'])->get();
			foreach($lists_users as $lists_user){
				$lists_user->delete();
			}
			
			// Delete Notification
			$notify    = Notifications::where( 'author', Auth::user()->id )
			->where('target',$inputs['lists_id'])
			->where('type',7)->get();
			
			// DELETE notify
			foreach ($notify as $noty) {
				$noty->delete();
			}

				 return Response::json( array ( 'success' => true ) );
			}//<--- Isset Project
		else {
			return Response::json( array ( 'success' => false, 'error' => Lang::get('misc.error') ) );
		}
		}
	}//<--- END
	
	public function detail( $slug, $id ){
		
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$user = User::where( 'username','=', $slug )->first();
		
		if( !empty( $user ) ) {
			// Check Lists ID
			$lists = Lists::where('id',$id)->where('user_id',$user->id)->first();
		}
		
		if( Auth::check() ) {
			$AuthId = Auth::user()->id;
			$role = Auth::user()->role;
		} else {
			$AuthId = 0;
			$role = 0;
		}

		// Verify User Status
		if( empty( $user ) 
			|| $user->status == 'pending' 
			|| empty( $lists ) 
			|| $lists->type == 0 
			&& $user->id != $AuthId 
			&& $role == 'normal'
		) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}
		
		$data = ListsUsers::where('lists_users.lists_id',$id)
		->join('shots', 'shots.user_id', '=', 'lists_users.user_id' )
		->join('members', 'members.id', '=', 'shots.user_id' )
		->where('shots.status',1)
		->where('members.status','active')
		->orderBy('shots.id','desc')
		->select('shots.*')
		->paginate( $settings->result_request );

		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		$total = $data->getTotal();
		
		return View::make('user.lists-show', compact( 'user','title', 'data', 'total', 'lists' ));
		
	}
	
	public function add_remove_ajax( $id ){
		if( Auth::check() ) {
	 
		 if(Request::ajax()) {
		 	
			$user = User::where('id',$id)->where('status','active')->first();
		 	
			if( isset( $user ) ){
				
			$lists = $this->lists->where('user_id',Auth::user()->id)->orderBy('id','asc')->get();
			$_array       = array();
			
			$i = 0;
			
				foreach ( $lists as $list ) {
					
					$ListsUsers = $list->users()->where('user_id',$user->id)->where('lists_id',$list->id)->first();
					
					$i++;
					
					if( !empty( $ListsUsers ) ) {
						$checked = 'checked="checked"';
						} else {
							$checked = null;
						}
					$_array[] = '<dd>
					<label class="checkbox-inline">
					<input class="no-show addListUser" name="checked" data-user-id="'.$user->id.'" data-list-id="'.$list->id.'" '.$checked.' type="checkbox" value="true">
					<span class="input-sm">'.$list->name.'</span>
					</label></dd>';
				}
			
			if(  !empty( $_array ) ) {
				return Response::json(array('lists' => $_array, 'success' => true, 'user_id' => $user->id));
			} else {
				return Response::json(array('success' => true));
			}
			
			
	} else {
				return Response::json(array('error' => Lang::get('misc.error'), 'success' => false));
			}		 
		 }
		} else {
			return Response::json(array('session_null' => true));
		}
		
	}//<----- End Method
	
		public function add_user_list( $id, $user ){
			if( Auth::check() ) {
	 
			 if(Request::ajax()) {
			 	
				$list = $this->lists->where('id',$id)->where('user_id',Auth::user()->id);
				 
				if( isset( $list ) ) {
				$query = ListsUsers::where('lists_id',$id)
				->where('lists_users.user_id',$user)
				->first();
				 
					 if( isset( $query ) ) {
					 	$query->delete();
						 
						 // Delete Notification
						Notifications::where('author',Auth::user()->id)
						->where('destination', $user)
						->where('target', $id)
						->delete(); 
						
					 } else {
					 	$user_new = new ListsUsers;
						$user_new->lists_id = $id;
						$user_new->user_id = $user;
						$user_new->save();
						
					// Send Notification
					//$destination, $session_id, $type, $target
					Notifications::send( $user, Auth::user()->id, 7, $id );
					 }
					 
			      }//<<-- Isset List
			    }// Request
			}// Auth
		}//<----- End Method
	
	
}
<?php
namespace App\Http\Controllers;
use App\Models\AdminSettings;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use View;
class ProjectsController extends BaseController {

	protected $projects;

	public function __construct(Projects $projects){
		$this->projects = $projects;
	}
	
	public function getIndex() {
		
		return Redirect::to('/');
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
		
		$data = $this->projects->where( 'user_id', $user->id )
		->where('status',1)
		->orderBy('id','desc')->paginate( $settings->result_request );
				
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
		
		return View::make('user.projects')->with($array);
			
			
	}//<--- END METHOD
	
	public function add(){
	  
	  if( Auth::check() ) {
	  		
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		// Setup the validator
		$rules = array(
			'title'       => 'required|min:3|max:30',
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
			
			if( Str::slug( $inputs['title']) == '' ) {
    
				$slugUrl  = Auth::user()->username;
			} else {
				$slugUrl  = Str::slug( $inputs['title'] );
			}
			
			
			$project          = new Projects;
			$project->user_id = Auth::user()->id;
			$project->title   = trim($inputs['title']);
			$project->about   = $description;
			$project->slug    = $slugUrl;
			$project->save();
			
			 return Redirect::back()->with(array( 'success_add' => Lang::get('misc.success_add') ));
		}
	  }
	}//<--- END
	
	public function edit(){
	  
	  if( Auth::check() ) {
	  		
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		// Check Project ID
		$project = Projects::where('id',$inputs['id'])->where('user_id',Auth::user()->id)->first();
		
		if( isset( $project ) ){
			
			// Setup the validator
			$rules = array(
				'title'       => 'required|min:3|max:30',
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
				
				if( Str::slug( $inputs['title']) == '' ) {
	    
					$slugUrl  = Auth::user()->username;
				} else {
					$slugUrl  = Str::slug( $inputs['title'] );
				}
				
				
				$project->title   = trim($inputs['title']);
				$project->about   = $description;
				$project->slug    = $slugUrl;
				$project->save();
				
				 return Redirect::back()->with(array( 'success_add' => Lang::get('misc.success_edit') ));
			}//<--- Isset Project
		} else {
			return Redirect::to('/');
		}
	  }
	}//<--- END
	
	public function delete(){
	  
	  if( Auth::check() ) {
	  		
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		// Check Project ID
		$project = Projects::where('id',$inputs['project_id'])->where('user_id',Auth::user()->id)->first();
		
		if( isset( $project ) ){

				$project->delete();
			
			$shots    = Shots::where('id_project',$inputs['project_id'])->get();
			
			if( !empty( $shots ) ) {
				// Update Shots
				foreach ($shots as $shot) {
					$shot->id_project = 0;
					$shot->created_project = '0000-00-00 00:00:00';
					$shot->save();
				}
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
			// Check Project ID
			$project = Projects::where('id',$id)
			->where('user_id',$user->id)
			->where('status',1)
			->first();
		}
		
		// Verify User Status
		if( empty( $user ) || $user->status == 'pending' || empty( $project ) ) {
			App::abort('404');
		} else if( $user->status == 'suspended' ){
			return View::make('user.suspended');
		}


		$data = $project->shots()->orderBy('created_project', 'DESC')->paginate( $settings->result_request );
		
		//<--- * If $page not exists * ---->
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}
		
		$total = $data->getTotal();
		
		return View::make('user.projects-show', compact( 'user','title', 'data', 'total', 'project' ));
		
	}

	public function projects() {
		
		$settings = AdminSettings::first();
		
		$page = Input::get('page');
		
		$data = Projects::has('shots')
		->where('status',1)
		->orderBy('id','desc')
		->paginate( $settings->result_request );
		
		/*$data = Projects::with(['likeCountRelation' => function($query){
			$query->orderBy('created_project', 'desc');
		}]
		)->paginate( $settings->result_request );*/
		
		if( $page > $data->getLastPage() ) {
			App::abort('404');
		}

		return View::make('index.projects_all', compact( 'data' ));
	}
	
	
	
	
}
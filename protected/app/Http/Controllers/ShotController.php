<?php
namespace App\Http\Controllers;
use App\Models\AdminSettings;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use View;
class ShotController extends BaseController {

	protected $shots;
	
	public function __construct(Shots $shots) {
		$this->shots = $shots;
	}

	public function index( $id ){
		
		$data = $this->shots->where('id',$id)->where('status',1)->first();
		
		if( $data ){
			
			//<--------- * Visits * ---------->
			$user_IP = $_SERVER['REMOTE_ADDR'];
			$date = time();
			
			if( Auth::check() ) {
				// SELECT IF YOU REGISTERED AND VISITED THE PUBLICATION
				$visitCheckUser = $data->visits()->where('user_id',Auth::user()->id)->first();
				
				if( !$visitCheckUser && Auth::user()->id != $data->user()->id ) {
					$visit = new Visits;
					$visit->shots_id = $data->id;
					$visit->user_id  = Auth::user()->id;
					$visit->ip       = $user_IP;
					$visit->save();
				}

			} else {
				
				// IF YOU SELECT "UNREGISTERED" ALREADY VISITED THE PUBLICATION
				$visitCheckGuest = $data->visits()->where('user_id',0)
				->where('ip',$user_IP)
				->orderBy('date','desc')
				->first();

			if( $visitCheckGuest )	{
				  $dateGuest = strtotime( $visitCheckGuest->date  ) + ( 7200 ); // 2 Hours

			}
			
				if( empty( $visitCheckGuest->ip )  ) {
				   	$visit = new Visits;
					$visit->shots_id = $data->id;
					$visit->user_id  = 0;
					$visit->ip       = $user_IP;
					$visit->save();
			   } else if( $dateGuest < $date ) {
			   		$visit = new Visits;
					$visit->shots_id = $data->id;
					$visit->user_id  = 0;
					$visit->ip       = $user_IP;
					$visit->save();
			   }

			}//<--------- * Visits * ---------->

			$next         = $this->shots->where('user_id',$data->user()->id)->where('status', 1)->where('id', '<', $id)->orderBy('id', 'desc')->first();
			$previous     = $this->shots->where('user_id',$data->user()->id)->where('status', 1)->where('id', '>', $id)->orderBy('id', 'asc')->first();
			$comments_sql = $data->comments()->where('status',1)->orderBy('date', 'desc')->paginate( 5 ); //$settings->result_request
			
			$output = array(
			 'data' => $data,
			 'next' => $next,
			 'previous' => $previous,
			 'comments_sql' => $comments_sql
			);
			
			$page = Input::get('page');
			$uri = Request::path();
			
			if( Str::slug( $data->title ) == '' ) {
    
				$slugUrl  = '';
			} else {
				$slugUrl  = '-'.Str::slug( $data->title );
			}
			
			$url_shot = 'shots/'.$data->id.$slugUrl;
			
			//<<<-- * Redirect the user real page * -->>>
			$uriShot      = Request::path();
			$uriCanonical = $url_shot;
			
			if( $uriShot != $uriCanonical ) {
				return Redirect::to($uriCanonical);
			}
			
			//<--- * If $page not exists * ---->
			if( $page > $comments_sql->getLastPage() ) {
				return Redirect::to($uri);
			}
		}
			if( empty( $data ) || $data->user()->status != 'active' ) {
				App::abort('404');
			}
		return View::make('shots.show', $output);
	}

	public function report(){
	  
	  if( Auth::check() ){
	 	
		if(Request::ajax()) {
				
		$id = Input::get('shot_id');
		$report = ShotsReported::where('user_id', '=', Auth::user()->id)->where('shots_id', '=', $id)->first();

		if( isset($report->id ) ){
			 
			$report->delete();
			
		} else {
			$report = new ShotsReported;
			$report->user_id = Auth::user()->id;
			$report->shots_id = $id;
			$report->save();
			
			return Response::json( array ( 'success' => true ) );
		}
		}// Ajax
	 }//Auth
	}// End Method
	
	public function edit(){
	  
	  if( Auth::check() ){
	 	
		$settings = AdminSettings::first();
		  
		$inputs = Input::All();

		$data = $this->shots->where('id',$inputs['id_shot'])
		->where('user_id',Auth::user()->id)
		->where('status',1)
		->first();
		 
		 if( isset( $data ) ) {
		 	
			// Setup the validator
			
			if( isset( $inputs['for_sale'] ) && $inputs['for_sale'] == 1 ) {
				$rules = array(
				'title'       => 'required|min:3|max:40',
				'tags'        => 'required',
				'description' => 'max:'.$settings->shot_length_description.'',
				'url_purchased' => 'required|url',
				'price_item'    => 'required|numeric',
				);
		} else {
			$rules = array(
				'title'       => 'required|min:3|max:40',
				'tags'        => 'required',
				'description' => 'max:'.$settings->shot_length_description.'',
				);
		}
			
			
			$messages = array (
	            "required"   => Lang::get('validation.required'),
	            "max"        => Lang::get('validation.max.string'),
	            "min"        => Lang::get('validation.min.string'),
        	);
			
			$validator = Validator::make($inputs, $rules, $messages);
			
			if($validator->fails()) {
				return Response::json(array(
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			
			    )); 
        } else {
			if( !empty( $inputs['description'] ) ) {
				$description = Helper::checkTextDb($inputs['description']);
			} else {
				$description = '';
			}
			
		 // Check Project ID
		$project = Projects::where('id',$inputs['project'])->where('user_id',Auth::user()->id)->first();
		
		if( isset( $project ) ){
			if( $inputs['project'] != $data->id_project && $inputs['project'] != 0 ) {
					$date_created_project = date( 'Y-m-d G:i:s', time() );
				} else if( $inputs['project'] == 0 ) {
					$date_created_project = '';
				} else {
					$date_created_project = $data->created_project;
				}
		   } else {
		   	    $inputs['project'] = 0;
				$date_created_project = '';
		   }

			if( isset( $inputs['for_team'] ) ) {
					$inputs['for_team'] = Auth::user()->team_id;
				} else {
					$inputs['for_team'] = 0;
				}
				
			$tags = Str::slug( $inputs["tags"], $separator = ',' );
			$tags = implode(',',array_unique(explode(',', $tags)));

		  
		  if( isset( $inputs['for_sale'] ) && $inputs['for_sale'] == 1 ) {
		  	$data->id_project      = $inputs['project'];
			$data->created_project = $date_created_project;
			$data->team_id         = $inputs['for_team'];
			$data->title           = trim( $inputs['title'] );
			$data->description     = trim( $description );
			$data->tags            = $tags;
			$data->url_purchased   = trim($inputs['url_purchased']);
		    $data->price_item      = trim($inputs['price_item']);
			$data->save();
		  } else {
		  	$data->id_project      = $inputs['project'];
			$data->created_project = $date_created_project;
			$data->team_id         = $inputs['for_team'];
			$data->title           = trim( $inputs['title'] );
			$data->description     = trim( $description );
			$data->tags            = $tags;
			$data->url_purchased   = '';
		    $data->price_item      = 0;
			$data->save();
		  }
			
			return Response::json(array(
				'success' => true,
				), 200);
			
		    } 
		 } else {
		 	return Response::json(array('
			session_null' => true,
			'success' => false
			));
		      }
	 }//Auth
	 	return Response::json(array('
			session_null' => true,
			'success' => false
			));
	}// End Method
	
	public function delete(){
	  

	  if( Auth::check() ){
	 	
		if(Request::ajax()) {
		
		$id = Input::get('shot_id');
			
		$shot = Shots::find($id);
		
		if( $shot->user_id == Auth::user()->id || Auth::user()->role == 'admin' ) {
			
			$fileShot         = 'public/shots_img/'.$shot->image;
			$fileShotLarge    = 'public/shots_img/large/'.$shot->large_image;
			$fileShotOriginal = 'public/shots_img/original/'.$shot->original_image;
			$fileAttach       = 'public/attachment_shots/'.$shot->attachment;
			
			$shots_reporteds = ShotsReported::where('shots_id', '=', $id)->get();
			foreach($shots_reporteds as $shots_reported){
				$shots_reported->delete();
			}
			
			$visits = Visits::where('shots_id', '=', $id)->get();
			foreach($visits as $visit){
				$visit->delete();
			}
			
			$shots_likes = Like::where('shots_id', '=', $id)->get();
			foreach($shots_likes as $shots_like){
				$shots_like->delete();
			}
			
			$comments = Comments::where('shots_id', '=', $id)->get();
			
			foreach($comments as $comment){
				$comment_likes = CommentsLikes::where('comment_id', '=', $comment->id)->get();
				foreach($comment_likes as $comment_like){
					$comment_like->delete();
				}

				$comment->delete();
			}
			
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
			
		}//<-----
			
		 return Response::json( array ( 'success' => true ) );
		}// Ajax
	 }//Auth
	}// End Method
	
	public function likes( $id ){
		
		$data = $this->shots->where('id',$id)->where('status',1)->first();
		
		if( Str::slug( $data->title ) == '' ) {
    
				$slugUrl  = '';
			} else {
				$slugUrl  = '-'.Str::slug( $data->title );
			}
			
			$url_shot = 'shots/'.$data->id.$slugUrl.'/likes';
			
			//<<<-- * Redirect the user real page * -->>>
			$uriShot      = Request::path();
			$uriCanonical = $url_shot;
			
			if( $uriShot != $uriCanonical ) {
				return Redirect::to($uriCanonical);
			}
			
			$output = array(
			 'id' => $id,
			 'title_shot' => $data->title,
			 'image_shot' => $data->image,
			 'title' => $data->title.' - '.Lang::get('misc.likes'),
			);
			
			return View::make('shots.likes', $output);
			
	}// End Method
	
}
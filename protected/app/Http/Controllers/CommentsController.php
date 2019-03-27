<?php
class CommentsController extends BaseController {

	protected $comments;

	public function __construct(Comments $comments){
		$this->comments = $comments;
	}
	
	public function getIndex() {
		
		return Redirect::to('/');
	}

	public function postSend(){
	
	if( Auth::check() ){
	  	
	    //==== ADMIN SETTINGS
		$settings = AdminSettings::first();
		$inputs = Input::All();
		$shot = Shots::where('id', $inputs['id_shot'])->where('status',1)->first();
		
		if( isset( $shot ) ) {
			$rules = array(
				'comment' => 'required|min:3|max:'.$settings->comment_length.'',
		        
		    );
			
			$messages = array (
	            "required"   => Lang::get('validation.required'),
	            "max"        => Lang::get('validation.max.string'),
	            "min"        => Lang::get('validation.min.string'),
	        );
			
				$validation = Validator::make( $inputs, $rules, $messages );
				
				if($validation->fails()) {
					
					
		            $redirect = Redirect::back()->withInput()->withErrors( $validation );
					
					$redirect->setTargetUrl($redirect->getTargetUrl() . '#commentsGrid'); //#commentsGrid
					
					return $redirect;
					
		        } else {
		        
					$this->comments->shots_id = $inputs['id_shot'];
					$this->comments->user_id = Auth::user()->id;
					$this->comments->reply = Helper::checkTextDb( $inputs['comment'] );
					$this->comments->save();
					
					$CommentId = $this->comments->id;
					
					/*------* SEND NOTIFICATION * ------*/
					
					if( Auth::user()->id != $shot->user_id ) {
						Notifications::send( $shot->user_id, Auth::user()->id, 3, $shot->id );
					}
					
					// Mentions
					$data_shot = strtolower( $inputs['comment'] ); 
					 preg_match_all('~([@])([^\s@!\"\$\%&\'\(\)\*\+\,\-./\:\;\<\=\>?\[/\/\/\\]\^\`\{\|\}\~]+)~', $data_shot, $_matches ); 
					 
					 foreach ( $_matches as $_key ) {
						$_key = array_unique(  $_key );
					}
					$_numMentions = count( $_matches[1] );
					
					for ( $j = 0; $j < $_numMentions; ++$j ) {
						
						$_key[$j] = strip_tags( $_key[$j] );
				
				/* Verified Username  */
				 $ckUsername = User::where( 'username', trim( $_key[$j] ) )->where('status','active')->first();
				 
				 if( !empty( $ckUsername ) ) {
				 	
					if( $ckUsername->id != Auth::user()->id && $ckUsername->id != $shot->user_id ) {
						/* Send Interaction */
						Notifications::send( $ckUsername->id, Auth::user()->id, 5, $shot->id );
					}
				 	
				 }
			}//<---- * END SEND NOTIFICATION ON MENTIONS * ----->
					
					$redirect = Redirect::back();
					
					$redirect->setTargetUrl($redirect->getTargetUrl() . '#comment'.$CommentId); //#commentsGrid
					
					return $redirect;
				}
		    } else {
		    	return Redirect::to('/');
		    }
		}//Auth
	}//<--------- END METHOD
	
	public function postDelete() {
	 
	 if( Auth::check() ){
	 	
		if(Request::ajax()) {
		
		$comment_id = Input::get('comment_id');
		$data = $this->comments->where('id',$comment_id)
		->where('user_id', Auth::user()->id)
		->first();
		
		if( isset( $data ) ) {
			
			// Delete Notification
			Notifications::where('author',Auth::user()->id)
			->where('target', $data->shots_id)
			->where('created_at', $data->date)
			->update(array('trash' => 1, 'status' => 1 ));
			
			$data->delete();
			
			return Response::json( array( 'success' => true ) );
			
		} else {
			return Response::json( array( 'success' => false, 'error' => Lang::get('misc.error') ) );
		}
		}// Ajax
	  }//Auth
	   else {
		return Response::json( array ( 'session_null' => true ) );
	  }
	}//<--- END METHOD
	
	public function postLike() {
	 
	 if( Auth::check() ){
	 	
		if(Request::ajax()) {
			
		$id = Input::get('comment_id');
			
	    $comment = $this->comments->where('id', $id)->where('status',1)->first();
			
		$comment_like = CommentsLikes::where('user_id', '=', Auth::user()->id)
		->where('comment_id', '=', $id)->first();
		
		if( isset( $comment_like->id ) ){
			
			if( $comment_like->status == 1 ) {
				//UNLIKE
				$comment_like->status = 0;
				$comment_like->save();
				
				$comment_count = CommentsLikes::where('comment_id', '=', $id)->where('status',1)->count();
				
				return Response::json( array ( 'success' => true, 'type' => 'unlike', 'count' => $comment_count ) );
			} else {
				//UNLIKE
				$comment_like->status = 1;
				$comment_like->save();
				
				$comment_count = CommentsLikes::where('comment_id', '=', $id)->where('status',1)->count();
				
				return Response::json( array ( 'success' => true, 'type' => 'like', 'count' => $comment_count ) );
			} 
			
		} else { 
			$like = new CommentsLikes;
			$like->user_id = Auth::user()->id;
			$like->comment_id = $id;
			$like->save();
			
			// SEND NOTIFICATION
			Notifications::send( $comment->user_id, Auth::user()->id, 6, $comment->shots_id );
			
			$comment_count = CommentsLikes::where('comment_id', '=', $id)->where('status',1)->count();
			
			return Response::json( array ( 'success' => true, 'type' => 'like', 'count' => $comment_count ) );
		}
		}
	 }//Auth
	   else {
		return Response::json( array ( 'session_null' => true ) );
	  }
	}//<--- END METHOD
	
	public function postDeleteadmin() {
	 
	 if( Auth::check() ){
	 	
		if(Request::ajax()) {
		
		$comment_id = Input::get('comment_id');
			
		$data = $this->comments->where('id',$comment_id)
		->first();
		
		if( isset( $data ) && Auth::user()->role == 'admin' ) {
			
			$data->delete();
			
			return Response::json( array( 'success' => true ) );
			
		} else {
			return Response::json( array( 'success' => false, 'error' => Lang::get('misc.error') ) );
			}
		}// Ajax
	  }//Auth
	   else {
		return Response::json( array ( 'session_null' => true ) );
	  }
	}//<--- END METHOD
}
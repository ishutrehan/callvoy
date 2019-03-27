<?php
namespace App\Http\Controllers;
use App\Models\AdminSettings;
use App\Models\Messages;
use App\Models\Conversations;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use View;
class MessagesController extends BaseController {

	protected $messages;

	public function __construct(Messages $messages){
		$this->messages = $messages;
	}
	
	public function getIndex() {
		
		return Redirect::to('/');
	}

	public function inbox() {
		
		if( Auth::check() ) {
			
			$settings = AdminSettings::first();
			
			/*$message = $this->messages
			->where('to_user_id',Auth::user()->id)
			->where('remove_from', 1 )
			->orWhere( 'from_user_id', Auth::user()->id )
			->where('remove_from', 1 )
			->leftjoin('conversations','conversations.id','=','messages.conversation_id')
			->orderBy('messages.updated_at', 'DESC')
			->groupBy('conversations.id')
			->select('messages.*')
			->paginate( $settings->result_request );*/
			
			$message = Conversations::has('messages')->where('user_1',Auth::user()->id)
			->orWhere('user_2',Auth::user()->id)
			->orderBy('updated_at', 'DESC')
			->paginate( $settings->result_request );

			
				return View::make('user.messages')->with(array( 'message' => $message ));
			
			} else {
				return Redirect::to('/');
			}	
			
	}//<--- End Method
	
	public function messages( $id ){
		
		if( Auth::check() ) {
			
			$settings = AdminSettings::first();
			
			$findUser = User::find($id);
			
			if( !isset( $findUser ) ) {
				App::abort('404');
			}
			
			$url_user = 'messages/'.$id.'-'.$findUser->username;
			
			//<<<-- * Redirect the user real page * -->>>
			$uriUser      = Request::path();
			$uriCanonical = $url_user;
			
			if( $uriUser != $uriCanonical ) {
				return Redirect::to($uriCanonical);
			}
			
			$id_user_conversation = $id;
			
			$user_blocked = DB::table('block_user')
		   ->where('user_id',Auth::user()->id)
		   ->where('user_blocked',$id)
		   ->orWhere('user_id',$id)
		   ->where('user_blocked',Auth::user()->id)
		   ->first();
		   
		   // Users Blocked
		   if( isset( $user_blocked ) ) {
		   	return Redirect::to('messages');
		   }
			
			$message = $this->messages
			->where('to_user_id',Auth::user()->id)
			->where('from_user_id',$id)
			->orWhere( 'from_user_id', Auth::user()->id )
			->where('to_user_id',$id)
			->orderBy('messages.updated_at', 'ASC')
			->get();
			
			//UPDATE MESSAGE 'READED'
			$this->messages->where('from_user_id',$id)
			->where('to_user_id',Auth::user()->id)
			->update(array('status' => 'readed'));
			
			return View::make('user.messages-show', compact( 'message','id_user_conversation' ));
		} else {
			return Redirect::to('/');
		}
		
	}//<--- End Method
	
	public function postSend(){
   	
	if( Auth::check() ) {
	 
		 if(Request::ajax()) {
				
			//==== ADMIN SETTINGS
			$settings = AdminSettings::first();
			
			 // Find user in Database
			 $user = User::find( Input::get('id_user') );
			 
			 $user_blocked = DB::table('block_user')
			   ->where('user_id',Auth::user()->id)
			   ->where('user_blocked',Input::get('id_user'))
			   ->orWhere('user_id',Input::get('id_user'))
			   ->where('user_blocked',Auth::user()->id)
			   ->first();
		   
		   // Users Blocked
		   if( isset( $user_blocked ) ) {
		   	return Response::json(array('session_null' => true));
			   exit;
		   }
			
			//=== PATHS
			$path      = 'public/temp/';
			$pathFinal = 'public/attachment_messages/';
			
			//============= FILES UPLOAD PDF, WORD, ETC ========//
			$name    = Input::file('fileUpload'); 
			
			if( $settings->allow_attachments_messages == 'off' ){
				$name = '';
			}
			
			if( strlen( $name ) ) {
				
				$originalName = Helper::spacesUrlFiles(Input::file('fileUpload')->getClientOriginalName());
			    $file         = strtolower( time().'_'.Auth::user()->id.'_'.Str::quickRandom(5)."_".$originalName );
				$typeMime     = Input::file('fileUpload')->getMimeType();
				$sizeFile     = Input::file('fileUpload')->getSize();
				
				if( $sizeFile > $settings->file_size_allowed ){
		 	return Response::json(array(
			        'success' => false,
			        'error_custom' => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ),
			
			    )); 
		 }
			
			} else {
				$typeMime = false;
			}
			
			// Setup the validator
			$rules = array(
				'message' => 'required|min:5|max:'.$settings->message_length.'',
				'fileUpload'   => 'max:'.$settings->file_size_allowed.'|mimes:'.$settings->file_support_attach.''
				);
			
			$messages = array (
	            "required"    => Lang::get('validation.required'),
	            "message.max"  => Lang::get('validation.max.string'),
				"fileUpload.mimes" =>Lang::get('misc.attach_file_support').' '.$settings->file_support_attach,
				"fileUpload.max"  => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 )
        	);
			$validator = Validator::make(Input::all(), $rules, $messages);
			
			
			// Validate the input and return correct response
			if ($validator->fails()) {
			    return Response::json(array(
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			        //'errors' => Lang::get('validation.error_message').' '.$settings->message_length,
			
			    )); 
			} else {
				
				if( isset( $name ) && $settings->allow_attachments_messages == 'on' ) {
					$name->move($pathFinal, $file);
					$fileAttach = $file;
				} else {
					$fileAttach = '';
				}
				
				// Verify Conversation Exists
				$conversation = Conversations::where('user_1',Auth::user()->id)
				->where('user_2',Input::get('id_user'))
				->orWhere('user_1',Input::get('id_user'))
				->where('user_2',Auth::user()->id)->first();
				
				
				$time = date( 'Y-m-d G:i:s', time() );
				
				$conversation->updated_at = $time;
				$conversation->save();
				
				$conversationID = $conversation->id;
				
				$message = new Messages;
				$message->conversation_id = $conversationID;
				$message->from_user_id    = Auth::user()->id;
				$message->to_user_id      = Input::get('id_user');
				$message->message         = Helper::checkTextDb(Input::get('message'));
				$message->attach_file     = $fileAttach;
				$message->updated_at      = $time;
				$message->save();
				
				return Response::json(array(
				'success' => true,
				), 200);
			}
			
	  }//Request::ajax()
	}//Auth::check()
	else {
		return Response::json(array('session_null' => true));
	}
   }//<<--- End Method
   
   public function postDelete() {
	 
	 if( Auth::check() ){
	 	
		if(Request::ajax()) {
		
		$message_id = Input::get('message_id');
		
		$data = $this->messages->where('from_user_id',Auth::user()->id)
		->where('id',$message_id)
		->orWhere('to_user_id', Auth::user()->id)
		->where('id',$message_id)->first();
		
		if( isset( $data ) ) {
			
			$fileAttach    = 'public/attachment_messages/'.$data->attach_file;
			
			$data->delete();
			
			//<<<-- Delete Attach -->>>/
			if ( File::exists($fileAttach) ) {
				File::delete($fileAttach);	
			}//<--- IF FILE EXISTS
			
			$countMessages = $this->messages->where('conversation_id',$data->conversation_id)->count();
			
			if( $countMessages == 0 ) {
				$conversation = Conversations::find($data->conversation_id);
				$conversation->delete();
			}
			
			
			return Response::json( array( 'success' => true, 'total' => $countMessages ) );
			
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
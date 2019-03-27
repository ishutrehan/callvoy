<?php

use Imagecraft\ImageBuilder;
namespace App\Http\BaseController;

class AjaxController extends BaseController {

	public function getIndex() {

		return Redirect::to('/');
	}


	public function postLike() {

	 if( Auth::check() ) {

		 if(Request::ajax()) {

			$idLike = (int)Input::get('id');

				// Model Like
				$like = New Like;

				$shot = Shots::where('id', $idLike)->where('status',1)->first();


				if( !isset( $shot ) ) {
					return false;
					exit;
				}

				// Verified if exist LIKE
				$data = Like::where( 'shots_id', $idLike )
						->where('user_id',Auth::user()->id)
						->first();

				// Exist LIKE
				if( !empty( $data ) ) {

					// Set Status Off
					if( $data->status == 1 ) {
					Like::where( 'shots_id', $idLike )
						->where('user_id',Auth::user()->id)
						->update(array('status' => 0));

						// Delete Notification
						Notifications::where('author',Auth::user()->id)
						->where('destination', $idLike)
						->where('trash', 0)
						->update(array('trash' => 1, 'status' => 0 ));
					}
					// Set Status On
					else if( $data->status == 0 ) {
					Like::where( 'shots_id', $idLike )
						->where('user_id',Auth::user()->id)
						->update(array('status' => 1));


					}
				} else {
					//If there is no insert new Like
					$like->user_id   = Auth::user()->id;
					$like->shots_id = $idLike;
					$like->save();

				if( Auth::user()->id != $shot->user_id ) {


					Notifications::send( $shot->user_id, Auth::user()->id, 2, $shot->id );
					//Send Notification
					/*$notification = new Notifications;
					$notification->destination = $shot->user_id;
					$notification->author = Auth::user()->id;
					$notification->type =  2;
					$notification->target = $shot->id;
					$notification->save();*/
				  }
				}

				$countLikeNow = Like::where('shots_id', $idLike )
							->where('status',1)
							->count();

				$totalLike = Helper::formatNumber( $countLikeNow );

				return $totalLike;
	   }// End Request::ajax()
	  }// End Auth

   }//<---- * End Method

   public function postFollow() {

	if( Auth::check() ) {

		 if(Request::ajax()) {

			$user_id = (int)Input::get('id');

			  // Verified if exist Follow
			  $followActive = Followers::where( 'follower', Auth::user()->id )
			 ->where( 'following', $user_id )->first();

			 // Find user in Database
			 $user = User::where( 'id', $user_id )->where('status','active')->first();

			 if( $user ) {

				// Model Like
				$follow = New Followers;

				if( $followActive ) {

					if( $followActive->status == 1 ) {
						Followers::where( 'follower', Auth::user()->id )
						->where( 'following', $user_id )
						->update(array('status' => 0));

						// Delete Notification
						Notifications::where('author',Auth::user()->id)
						->where('destination', $user_id)
						->where('trash', 0)
						->update(array('trash' => 1, 'status' => 1 ));

						return Response::json( array( 'status' => 2 ) );
					}

					else if( $followActive->status == 0 ) {
						Followers::where( 'follower', Auth::user()->id )
						->where( 'following', $user_id )
						->update(array('status' => 1));

						return Response::json( array( 'status' => 3 ) );
					}

				} else {
					//If there is no insert new Like
					$follow->follower = Auth::user()->id;
					$follow->following = $user_id;
					$follow->save();

					// Send Notification
					Notifications::send( $user_id, Auth::user()->id, 1, Auth::user()->id );


					if( $user->email_notification_follow == 1 && $user->email != '' ) {

						$usernameAuth = Auth::user()->username;

					    $userName = '@'.$usernameAuth;
						$emailUser = $user->email;
						$nameUser = $user->name;

						Mail::send('emails.follow', array( 'username' => $usernameAuth ),
						function($message) use ($userName,$emailUser,$nameUser) {
			            $message->to($emailUser, $nameUser)
			                ->subject( $userName . Lang::get('users.you_are_following') );
					});
				}

				 return Response::json( array( 'status' => true ) );

				}

			 } else {
					return Response::json( array( 'status' => false, 'error' => Lang::get('misc.error') ) );
				}

		 	}// End Request::ajax()
				else {
					return Response::json( array( 'status' => false, 'error' => Lang::get('misc.error') ) );
				}
					  }// End Auth
				else {
					return Response::json( array( 'status' => false, 'error' => Lang::get('misc.error') ) );
				}
   }//<---- * End Method

   public function getNotifications() {

		if( Auth::check() ) {

		   if(Request::ajax()) {
			// Notifications
			$notifications_count = Notifications::where('destination',Auth::user()->id)->where('status',0)->count();

			// Messages
			$messages_count = Messages::where('to_user_id',Auth::user()->id)->where('status','new')->count();

			if( $messages_count == 0 ){
				$messages_count = '0';
			}

			if( $notifications_count == 0 ){
				$notifications_count = '0';
			}

			return Response::json( array ( 'messages' => $messages_count, 'notifications' => $notifications_count ) );

		   } else {
				return Response::json( array ( 'error' => 1 ) );
			}
	  }//Auth
	  else {
				return Response::json( array ( 'error' => 1 ) );
			}

   }//<---- * End Method

   public function postAvatar() {

	if( Auth::check() ) {

		 if(Request::ajax()) {

			$settings    = AdminSettings::first();
			$AuthUser    = Auth::user()->id; //$session id
			$path        = 'public/temp/';
			$path_avatar = 'public/avatar/';
			$imgOld      = $path_avatar.Auth::user()->avatar;

			chmod( $path, 0777 );

			$valid_formats = array(
					"image/pjpeg",
					"image/jpeg",
					"image/jpg",
					"image/JPG",
					"image/png",
					"image/x-png",
					"image/gif"
				);

			$tmp_input = Input::all();
			$name = Input::file('photo');
			$size = Input::file('photo')->getSize();
			$type = Input::file('photo')->getMimeType();


			if( strlen( $name ) ) {

			// Switch
			switch($type) {
				case "image/gif":
					$ext = 'gif';
					break;
			    case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
				case "image/JPG":
					$ext= 'jpg';
					break;
			    case "image/png":
				case "image/x-png":
					$ext = 'png';
					break;
		  	}

				if( in_array( $type, $valid_formats ) ) {

					if( $size < ( 2250 * 2250 ) ) {

							$random      = Str::random( 5 );
							$photo_post  = strtolower( Auth::user()->username."_".$AuthUser.$random ).'.'.$ext;
							$tmp         = Input::file('photo');

							/* Get Width and Height */
							$dimensionsImage = getimagesize( $tmp );
							$widthImage      = $dimensionsImage[0];
							$heightImage     = $dimensionsImage[1];

							/* Width and Height */
							if( $widthImage >= 128 && $heightImage >= 128 ) {

								if( $name->move($path, $photo_post) ) {

							//<---- GIF ANIMATE ----->
								if( $type == 'image/gif' ) {

									$DS = DIRECTORY_SEPARATOR;

									$file_src = base_path().$DS.'public'.$DS.'temp'.$DS.$photo_post;


									require_once base_path().$DS.'app'.$DS.'libraries'.$DS.'imagecraft'.$DS.'vendor'.$DS.'autoload.php';

									$options = ['engine' => 'php_gd', 'locale' => 'en' , 'gif_animation' => true ];

									$_builder = new ImageBuilder($options);
									$_layer   = $_builder->addBackgroundLayer();
									$_layer->filename( $file_src );
									$_layer->resize(128, 128, 'fill_crop');
									$_image   = $_builder->save();

									if ($_image->isValid()) {
									    file_put_contents( $file_src , $_image->getContents() );
									}

									/*$img = new Imagick( $file_src );

									$img = $img->coalesceImages();

									foreach ($img as $frame) {
									  $frame->cropThumbnailImage(128, 128);
									}

									$img = $img->deconstructImages();

									$img->writeImages($file_src, true); */
								}
								else {
								//<<-------- START RESIZE IMAGE ----------->>>>
									/* 128x128 px */
									Helper::resizeImageFixed( $path.$photo_post, 128, 128, $path.$photo_post );
								 }
								//<<-------- END RESIZE IMAGE ----------->>>>

									// Copy folder
									if ( File::exists($path.$photo_post) ) {

										/* Avatar */
										File::copy($path.$photo_post, $path_avatar.$photo_post);
										File::delete($path.$photo_post);

									}//<--- IF FILE EXISTS

									//<<<-- Delete old image -->>>/
									if ( File::exists($imgOld) && $imgOld != $path_avatar.'default.jpg' ) {

										File::delete($path.$photo_post);
										File::delete($imgOld);
									}//<--- IF FILE EXISTS #1

									// Update Database
									User::where( 'id', Auth::user()->id )->update( array( 'avatar' => $photo_post ) );

									return Response::json( array ( 'output' => '', 'error' => 0, 'photo' => $photo_post ) );


								} else {
									return Response::json( array ( 'output' => Lang::get('misc.error'), 'error' => 1 ) );
								}

							} else {
								return Response::json( array ( 'output' => Lang::get('misc.width_height_min_avatar'), 'error' => 1 ) );
							}

					} else {
						return Response::json( array ( 'output' => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ), 'error' => 1 ) );
					}

				} else {
					return Response::json( array ( 'output' => Lang::get('misc.formats_available'), 'error' => 1 ) );
				}
			} else {
				return Response::json( array ( 'output' => Lang::get('misc.please_select_image'), 'error' => 1 ) );
			}

		 }//Request::ajax()
	}//Auth::check()
	else {
		return Response::json( array ( 'session_null' => true ) );
	}

   }//<--- End Method

  public function postCover() {

	if( Auth::check() ) {

		 if(Request::ajax()) {

			$settings    = AdminSettings::first();
			$AuthUser    = Auth::user()->id; //$session id
			$path        = 'public/temp/';
			$path_cover  = 'public/cover/';
			$imgOld      = $path_cover.Auth::user()->cover;

			chmod( $path, 0777 );

			$valid_formats = array(
					"image/pjpeg",
					"image/jpeg",
					"image/jpg",
					"image/JPG",
					"image/png",
					"image/x-png",
					"image/gif"
				);

			$tmp_input = Input::all();
			$name = Input::file('photo');
			$size = Input::file('photo')->getSize();
			$type = Input::file('photo')->getMimeType();

			if( strlen( $name ) ) {

			// Switch
			switch($type) {
				case "image/gif":
					$ext = 'gif';
					break;
			    case "image/pjpeg":
				case "image/jpeg":
				case "image/jpg":
				case "image/JPG":
					$ext= 'jpg';
					break;
			    case "image/png":
				case "image/x-png":
					$ext = 'png';
					break;
		  	}

				if( in_array( $type, $valid_formats ) ) {

					if( $size < ( $settings->file_size_allowed ) ) {

							$random      = Str::random( 5 );
							$photo_post  = 'cover_'.strtolower( Auth::user()->username."_".$AuthUser.$random ).'.'.$ext;
							$tmp         = Input::file('photo');

							/* Get Width and Height */
							$dimensionsImage = getimagesize( $tmp );
							$widthImage      = $dimensionsImage[0];
							$heightImage     = $dimensionsImage[1];

							/* Width and Height */
							if( $widthImage >= 400 && $heightImage >= 200 ) {

								if( $name->move($path, $photo_post) ) {

									//=============== Image Large =================//
									$width  = Helper::getWidth( $path.$photo_post );
									$height = Helper::getHeight( $path.$photo_post );
									$max_width = '1500';

									if( $width < $height ) {
										$max_width = '800';
									}

									if ( $width > $max_width ) {
										$scale = $max_width / $width;
										$uploaded = Helper::resizeImage( $path.$photo_post, $width, $height, $scale, $path.$photo_post );
									} else {
										$scale = 1;
										$uploaded = Helper::resizeImage( $path.$photo_post, $width, $height, $scale, $path.$photo_post );
									}

									// Copy folder
									if ( File::exists($path.$photo_post) ) {

										/* Cover */
										File::copy($path.$photo_post, $path_cover.$photo_post);
										File::delete($path.$photo_post);

									}//<--- IF FILE EXISTS

									//<<<-- Delete old image -->>>/
									if ( File::exists($imgOld) && $imgOld != $path_cover.'cover.jpg' ) {

										File::delete($path.$photo_post);
										File::delete($imgOld);
									}//<--- IF FILE EXISTS #1

									// Update Database
									User::where( 'id', Auth::user()->id )->update( array( 'cover' => $photo_post ) );

									return Response::json( array ( 'output' => '', 'error' => 0, 'photo' => $photo_post ) );


								} else {
									return Response::json( array ( 'output' => Lang::get('misc.error'), 'error' => 1 ) );
								}

							} else {
								return Response::json( array ( 'output' => Lang::get('misc.width_height_min'), 'error' => 1 ) );
							}

					} else {
						return Response::json( array ( 'output' => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ), 'error' => 1 ) );
					}

				} else {
					return Response::json( array ( 'output' => Lang::get('misc.formats_available'), 'error' => 1 ) );
				}
			} else {
				return Response::json( array ( 'output' => Lang::get('misc.please_select_image'), 'error' => 1 ) );
			}

		 }//Request::ajax()
	}//Auth::check()
	else {
		return Response::json( array ( 'session_null' => true ) );
	}

   }//<--- End Method

   public function getTrash(){

	if( Auth::check() ) {

		 if(Request::ajax()) {

			$type = Input::get('type');

			 if( $type == 'avatar' ) {
			 	$root        = 'public/avatar/';
				$field_db    = 'avatar';
				$default_img = 'default.jpg';
				$imgOld      = Auth::user()->avatar;
			 } else if( $type == 'cover' ) {
			 	$root         = 'public/cover/';
				$field_db     = 'cover';
				$default_img  = 'cover.jpg';
				$imgOld       = Auth::user()->cover;
			 }

			 /*
			 * --------------------------
			 *   Root of Photo
			 * -------------------------
			 */

			$photo_id       = Input::get('token_id');;

			/*
			 * --------------------------
			 *   Folder permissions
			 * -------------------------
			 */
			 if( $imgOld == $photo_id ) {
			 	chmod( $root.$photo_id, 0777 );

				 if ( File::exists( $root.$photo_id ) )  {
				 	 File::delete($root.$photo_id);

					 /* Update Database */
					User::where( 'id', Auth::user()->id )->update( array( $field_db => $default_img ) );

					 return Response::json( array( 'status' => 1 ) );
				 }
			 }

		}//Request::ajax()
	}//Auth::check()
   }//<--- End Method

   public function postSendmessage(){

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

				if( !isset( $conversation ) ) {
					$newConversation = new Conversations;
					$newConversation->user_1 = Auth::user()->id;
					$newConversation->user_2 = Input::get('id_user');
					$newConversation->updated_at = $time;
					$newConversation->save();

					$conversationID = $newConversation->id;

				} else {
					$conversation->updated_at = $time;
					$conversation->save();

					$conversationID = $conversation->id;
				}

				$message = new Messages;
				$message->conversation_id = $conversationID;
				$message->from_user_id    = Auth::user()->id;
				$message->to_user_id      = Input::get('id_user');
				$message->message         = Helper::checkTextDb(Input::get('message'));
				$message->attach_file     = $fileAttach;
				$message->updated_at      = $time;
				$message->save();

				if( $user->email_notification_msg == 1 && $user->email != '' ) {

						$usernameAuth = Auth::user()->username;

						$userName = '@'.$usernameAuth;
						$emailUser = $user->email;
						$nameUser = $user->name;

						Mail::send('emails.msg-private', array( 'username' => $usernameAuth ),
						function($message) use($userName,$emailUser,$nameUser) {
			            $message->to($emailUser, $nameUser)
			                ->subject( $userName . Lang::get('users.send_msg_private') );
					});
				}

				return Response::json(array(
				'success' => true,
				'message' => Helper::checkTextDb(Input::get('message')),
				'file' => $fileAttach
				), 200);
			}

	  }//Request::ajax()
	}//Auth::check()
	else {
		return Response::json(array('session_null' => true));
	}
   }//<<--- End Method

    public function postUpload(){

	if( Auth::check() ) {

		 if(Request::ajax()) {


		 	$inputs = Input::All();

			//==== ADMIN SETTINGS
			$settings = AdminSettings::first();

			$temp            = 'public/temp/'; //=== PATHS
			$path            = 'public/shots_img/'; //=== PATHS SHOTS
			$path_large      = 'public/shots_img/large/'; //=== PATHS SHOTS
			$path_original   = 'public/shots_img/original/'; //=== PATHS SHOTS
			$path_attachment = 'public/attachment_shots/';//=== PATHS ATTACH
			$extension_file  = '';


			//============= FILES UPLOAD SHOT ========//
			$file_name    = Input::file('fileShot');

			if( strlen( $file_name ) ) {
				$extension          = Input::file('fileShot')->getClientOriginalExtension();
				$type_mime_shot     = Input::file('fileShot')->getMimeType();
				$sizeFile           = Input::file('fileShot')->getSize();
				$title_shot         = Str::slug( Input::get('title'), '-' );
				$shot_file          = strtolower( time().'_'.Auth::user()->id.'_'.Str::quickRandom(5).'_'.$title_shot.".".$extension );
				$shot_file_large    = strtolower( time().'_'.Auth::user()->id.'_'.Str::quickRandom(5).'_'.$title_shot.".".$extension );
				$shot_file_original = 'original_'.strtolower( time().'_'.Auth::user()->id.'_'.Str::quickRandom(5).'_'.$title_shot.".".$extension );

			if( $sizeFile > $settings->file_size_allowed ){
		 	return Response::json(array(
			        'success' => false,
			        'error_custom' => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ),

			    ));
		 }


			}

			//============= FILES UPLOAD PDF, WORD, ETC ========//
			$file_attach    = Input::file('attach_file');


			if( strlen( $file_attach ) ) {

				$extension_file = Input::file('attach_file')->getClientOriginalExtension();
				$sizeFileAttach = Input::file('attach_file')->getSize();
				$originalName   = Helper::spacesUrlFiles(Input::file('attach_file')->getClientOriginalName());
			    $file           = strtolower( Auth::user()->id.'u'.time().Str::quickRandom(5)."_".$originalName );

				if( $sizeFileAttach > $settings->file_size_allowed ){
		 	return Response::json(array(
			        'success' => false,
			        'errors' => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ),

			    ));
		 }
			}

			// Setup the validator

			if( isset( $inputs['for_sale'] ) && $inputs['for_sale'] == 1 ) {
				$rules = array(
				'fileShot'      => 'required|max:'.$settings->file_size_allowed.'|mimes:jpg,gif,png,jpe,jpeg',
				'title'         => 'required|min:3|max:40',
				'tags'          => 'required',
				'attach_file'   => 'max:'.$settings->file_size_allowed.'|mimes:'.$settings->file_support_attach.'',
				'description'   => 'max:'.$settings->shot_length_description.'',
				'url_purchased' => 'required|url',
				'price_item'    => 'required|numeric',
				);
			} else {
				$rules = array(
				'fileShot'    => 'required|max:'.$settings->file_size_allowed.'|mimes:jpg,gif,png,jpe,jpeg',
				'title'       => 'required|min:3|max:40',
				'tags'        => 'required',
				'attach_file' => 'max:'.$settings->file_size_allowed.'|mimes:'.$settings->file_support_attach.'',
				'description' => 'max:'.$settings->shot_length_description.'',
				);
			}


			$messages = array (
				'fileShot.required' => Lang::get('misc.please_select_image'),
				'fileShot.mimes'    => Lang::get('misc.formats_available'),
	            "required"          => Lang::get('validation.required'),
	            "attach_file.max"   => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ),
				"attach_file.mimes" => Lang::get('misc.attach_file_support').' '.$settings->file_support_attach,
        	);
			$validator = Validator::make($inputs, $rules, $messages);


			// Validate the input and return correct response
			if ($validator->fails()) {
			    return Response::json(array(
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),

			    ));
			} else {

				//======= Upload Attach
				if( isset( $file_attach ) && $settings->allow_attachments == 'on' ) {
					$file_attach->move($temp, $file);
				}

				if( $file_name->move( $temp, $shot_file_original ) ) {


					//===== Imagick GIF ANIMATE =====//
					if( $type_mime_shot == 'image/gif' && extension_loaded( 'imagick' ) ) {

						set_time_limit(0);

						$DS = DIRECTORY_SEPARATOR;

						$file_src       = base_path().$DS.'public'.$DS.'temp'.$DS.$shot_file_original;
						$file_src_thumb = base_path().$DS.'public'.$DS.'temp'.$DS.$shot_file_large;


						require_once base_path().$DS.'app'.$DS.'libraries'.$DS.'imagecraft'.$DS.'vendor'.$DS.'autoload.php';

						$options = ['engine' => 'php_gd', 'locale' => 'en' , 'gif_animation' => false ];

						$builder = new ImageBuilder($options);

						$layer = $builder->addBackgroundLayer();
						$layer->filename( $file_src );

						if( isset( $inputs['image'] ) && $inputs['image'] == '0' ) {
							$layer->resize(650, 400, 'fill_crop');
						} else {
							$layer->resize(650, 650, 'shrink');
						}


						$image = $builder->save();

						if ($image->isValid()) {
						    file_put_contents( $file_src_thumb , $image->getContents() );
						} else {

						return Response::json(array(
						        'success' => false,
						        'errors' => $image->getMessage().PHP_EOL,

						    ));
						}

						/*$img = new Imagick( $file_src );
						$img = $img->coalesceImages();

						foreach ($img as $frame) {

							if( isset( $inputs['image'] ) && $inputs['image'] == '0' ) {
								$frame->cropThumbnailImage(650, 400);
							} else {
								$frame->resizeImage( 650, 650, Imagick::FILTER_LANCZOS, 1, TRUE);
							}

						}
						$img = $img->deconstructImages();
						$img->writeImages($file_src_thumb, true);*/

						// THUMBNAIL
						Helper::resizeImageFixed( $temp.$shot_file_original, 200, 150, $temp.$shot_file );

					}// END IMAGE GIF

					else {

						if( isset( $inputs['image'] ) && $inputs['image'] == '0' ) {
							Helper::resizeImageFixed( $temp.$shot_file_original, 650, 400, $temp.$shot_file_large );
						} else {

							//=============== px =================//
							$width  = Helper::getWidth( $temp.$shot_file_original );
							$height = Helper::getHeight( $temp.$shot_file_original );
							$max_width = '650';

							if( $width < $height ) {
								$max_width = '650';
							}

							if ( $width > $max_width ) {
								$scale = $max_width / $width;
								$uploaded = Helper::resizeImage( $temp.$shot_file_original, $width, $height, $scale, $temp.$shot_file_large );
							} else {
								$scale = 1;
								$uploaded = Helper::resizeImage( $temp.$shot_file_original, $width, $height, $scale, $temp.$shot_file_large );
							}

							//Helper::resizeImage( $temp.$shot_file, 650, 650, 1, $temp.$shot_file_large );
						}


						// THUMBNAIL
						Helper::resizeImageFixed( $temp.$shot_file_original, 200, 150, $temp.$shot_file );
					}



					//======= Copy folder FILE SHOT =========//
					if ( File::exists($temp.$shot_file_original) ) {

						/* FILE SHOT */
						File::copy($temp.$shot_file, $path.$shot_file);
						File::copy($temp.$shot_file_large, $path_large.$shot_file_large);
						File::copy($temp.$shot_file_original, $path_original.$shot_file_original);
						File::delete($temp.$shot_file);
						File::delete($temp.$shot_file_large);
						File::delete($temp.$shot_file_original);
					}//<--- IF FILE EXISTS

					//======= Copy folder FILE ATTACH ===========
					if ( isset( $file_attach ) ) {

						/* FILE ATTACH */
						File::copy($temp.$file, $path_attachment.$file);
						File::delete($temp.$file);
					}//<--- IF FILE EXISTS
					else {
						$file = '';
					}

				}

				if( !empty( $inputs['description'] ) ) {
					$description = Helper::checkTextDb($inputs['description']);
				} else {
					$description = '';
				}

				// Check Project ID
				$project = Projects::where('id',$inputs['project'])->where('user_id',Auth::user()->id)->first();

				if( isset( $project ) ){
					if( $inputs['project'] != 0 ) {
						$date_created_project = date( 'Y-m-d G:i:s', time() );
					} else {
						$date_created_project = '';
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
				// Insert DATABASE
				$shot = new Shots;
				$shot->id_project      = $inputs['project'];
				$shot->created_project = $date_created_project;
				$shot->team_id         = $inputs['for_team'];
				$shot->image           = $shot_file;
				$shot->large_image     = $shot_file_large;
				$shot->original_image  = $shot_file_original;
				$shot->title           = trim($inputs['title']);
				$shot->description     = $description;
				$shot->user_id         = Auth::user()->id;
				$shot->token_id        = Str::random( 40 );
				$shot->tags            = $tags;
				$shot->attachment      = $file;
				$shot->extension       = strtolower( $extension );
				$shot->extension_file  = strtolower( $extension_file );
				$shot->extension_file  = strtolower( $extension_file );
				$shot->url_purchased   = trim($inputs['url_purchased']);
				$shot->price_item      = trim($inputs['price_item']);
				$shot->save();
			} else {
				// Insert DATABASE
				$shot = new Shots;
				$shot->id_project      = $inputs['project'];
				$shot->created_project = $date_created_project;
				$shot->team_id         = $inputs['for_team'];
				$shot->image           = $shot_file;
				$shot->large_image     = $shot_file_large;
				$shot->original_image  = $shot_file_original;
				$shot->title           = trim($inputs['title']);
				$shot->description     = $description;
				$shot->user_id         = Auth::user()->id;
				$shot->token_id        = Str::random( 40 );
				$shot->tags            = $tags;
				$shot->attachment      = $file;
				$shot->extension       = strtolower( $extension );
				$shot->extension_file  = strtolower( $extension_file );
				$shot->save();
			}


			/*--------------------
			 * SEND NOTIFICATION
			 * ON MENTIONS
			 * ------------------
			 */

		$shotId = $shot->id;

		if( !empty( $inputs['description'] ) ) {

			 $data_shot = strtolower( $description );
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

					if( $ckUsername->id != Auth::user()->id ) {
						/* Send Interaction */
						Notifications::send( $ckUsername->id, Auth::user()->id, 4, $shotId );
					}

				 }
			}//<---- * END SEND NOTIFICATION ON MENTIONS * ----->
		}//<----- !empty( $inputs['description'] )

				if( Str::slug( $inputs['title']) == '' ) {

					$slugUrl  = '';
				} else {
					$slugUrl  = '-'.Str::slug( $inputs['title'] );
				}

				$url_shot = URL::to('/').'/shots/'.$shotId.$slugUrl;

			return Response::json(array(
				'success' => true,
				'target' => $url_shot,
				'text' => Lang::get('misc.published_successfully', ['upload_shot' => '<a class="btn btn-danger btn-sm" href="'.URL::to('upload').'">'.Lang::get('misc.upload_other_shot').'</a>' ])
				), 200);
			}


	  }//Request::ajax()
	}//Auth::check()
	else {
		return Response::json(array('
		session_null' => true,
		'success' => false
		));
	}
   }//<<--- End Method

   public function getMentions() {
   	 if( Auth::check() ) {

		 if(Request::ajax()) {

			$input = Input::get('filter');

			$response = User::where('username', 'LIKE', '%'.$input.'%')
			->orWhere('name', 'LIKE', '%'.$input.'%')
			->where('status','active')
			->orderBy('type_account','DESC')
			->take(5)
			->get();

			$countPosts      = count( $response );

   if( $countPosts != 0 ) :

   foreach ( $response as $key ) {

		//============ VERIFIED
		if( $key->type_account == 2 ) {
			$pro = ' <span class="label label-primary btn-pro-xs">'.Lang::get("misc.pro").'</span>';
		} else if($key->type_account == 3){
			$pro = ' <span class="label label-primary btn-team-xs">'.Lang::get("misc.team").'</span>';
		} else {
			$pro = null;
		}

	 	$arrayLoop[] = 	array(
				'name' => stripslashes( $key->name ) . $pro,
				'username' => $key->username,
				"avatar" => $key->avatar
		);
		}
   $array = array(
			"tags" => $arrayLoop
		 );

		 return Response::json( $array );

   else:
	    $array = array(
			"tags" => 0
		 );

	    return Response::json( $array );

		endif;  //<<<--- $countPosts != 0

		 }//Request
	 }// Auth
   }

public function postAddlist(){

	  if( Auth::check() ) {

		$settings = AdminSettings::first();

		$inputs = Input::All();
		$inputUserId = Input::get('user_id');

		// Setup the validator
		$rules = array(
			'name'       => 'required|min:3|max:30',
			'description' => 'min:3|max:'.$settings->message_length.'',
			);

		$validator = Validator::make($inputs, $rules);

		if( $validator->fails() ) {
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

			$lists              = new Lists;
			$lists->user_id     = Auth::user()->id;
			$lists->name        = trim($inputs['name']);
			$lists->description = $description;
			$lists->type        = $inputs['type'];
			$lists->save();

			$listsId = $lists->id;

			 return Response::json(array(
				'success' => true,
				'message' => Lang::get('misc.success_add_list'),
				'user_id' => $inputUserId,
				), 200);
		}
	  }
	}//<--- END

	public function getChat(){

		if( Auth::check() ) {

			if(Request::ajax()) {

			$message = Messages::where('to_user_id',Auth::user()->id)
				->where('from_user_id',Input::get('user_id'))
				->where('id','>',Input::get('last_id'))
				->orWhere( 'from_user_id', Auth::user()->id )
				->where('to_user_id',Input::get('user_id'))
				->where('id','>',Input::get('last_id'))
				->orderBy('messages.updated_at', 'ASC')
				->get();

			$count = $message->count();
			$_array = array();

			if( $count != 0 ) {
				// foreach
				foreach ( $message as $msg ) {

			if( $msg->from_user_id  == Auth::user()->id ){
		  	 	$avatar   = $msg->to()->avatar;
				$name     = $msg->to()->name;
				$userID   = $msg->to()->id;
				$username = $msg->to()->username;

		  	 } else if ( $msg->to_user_id  == Auth::user()->id ) {
		  	 	$avatar   = $msg->from()->avatar;
				$name     = $msg->from()->name;
				$userID   = $msg->from()->id;
				$username = $msg->from()->username;
		  	 }

			 $attach = 'public/attachment_messages'."/".$msg->attach_file;

			 $ext = pathinfo( $attach );

			 $formats_image = array(
					"gif",
					"jpeg",
					"jpg",
					"JPG",
					"png",
					"x-png",
					"gif"
				);

				if( $msg->attach_file != '' && in_array( $ext['extension'], $formats_image ) ) {
					$attach_file = "<a data-url='' target='_blank' class='galery' href='".URL::to('public/attachment_messages')."/".$msg->attach_file."'><img width='300' class='img-responsive' src='".URL::asset('public/attachment_messages')."/".$msg->attach_file."' /></a>";
				} else if( $msg->attach_file != '' && !in_array( $msg->extension_file, $formats_image ) ) {
					$attach_file = '<div class="btn-default btn-xs btn-border text-left list-media-mv"> <a href="'.URL::to('public/attachment_messages').'/'.$msg->attach_file.'" target="_blank" class="btn-block "> <i class="glyphicon glyphicon-paperclip myicon-right"></i> '.$msg->attach_file.' <span class="icon-download pull-right"></span> </a> </div>';
				} else {
					$attach_file = null;
				}

				$_array[] = '<li data="'.$msg->id.'" class="media li-group list-group-item border-group list-slimscroll chatlist margin-zero">
	                         <div class="media">
	                            <div class="pull-left">
	                            <a href="'.URL::to('@').$msg->from()->username.'">
                               		<img width="40" src="'.URL::asset('public/avatar').'/'.$msg->from()->avatar.'" alt="Image" class="border-image-profile-2 media-object img-circle">
	                              </a>
	                            </div>
	                            <div class="media-body clearfix">

	                      <div class="pull-right small">
							<a href="javascript:void(0);" class="link-post showTooltip removeMsg" data-delete="'.Lang::get('misc.delete_message').'" data="'.$msg->id.'" title="'.Lang::get('misc.delete').'" data-toggle="tooltip" data-placement="left">
								<i class="glyphicon glyphicon-trash"></i>
								</a>
							</div>

	                               <div class="pull-right small">
	                               	<span class="timestamp timeAgo myicon-right" data="'.date('c',strtotime( $msg->created_at ) ).'"></span>
	                               	</div>

	                               <div class="media-nowrap">
	                               	<a href="'.URL::to('@').$msg->from()->username.'" class="text-decoration-none">
	                               		<strong class="media-heading">'.e( $msg->from()->name ).'</strong>
	                               	</a>
	                               </div>
	                               <p class="text-col paragraph none-overflow">'.e( $msg->message ).'</p>

			                     '.$attach_file.'

	                            </div><!-- media-body -->
	                         </div>
	          	       </li>';

					   // UPDATE HOW READ MESSAGE

					   if( $msg->to_user_id == Auth::user()->id ) {
					   	    $readed = Messages::where('id',$msg->id)
							->where('to_user_id',Auth::user()->id)
					   	    ->update(array('status' => 'readed'));
					   }


				}//<--- foreach
			}//<--- IF != 0

			return Response::json(array(
				'total'    => $count,
				'messages' => $_array,
				'success' => true,
				'to' => Input::get('user_id'),
				), 200);

		   }// Ajax
		} else {
			return Response::json(array('
			session_null' => true,
			'success' => false
			));
		}

      }//<--- End Method


      public function postUpdatemail(){

      	if( Auth::check() ) {

			if(Request::ajax()) {

		$settings = AdminSettings::first();

		$inputs = Input::All();

		// Setup the validator
		$rules = array(
			'email'     => 'required|email|unique:members,email,'.Auth::user()->id,
			);

		$validator = Validator::make($inputs, $rules);

		if( $validator->fails() ) {
			return Response::json(array(
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			         ));
		} else {

			$user           = User::find( Auth::user()->id );
			$user->email    = trim( strtolower( $inputs["email"] ) );
			$user->update();

			 return Response::json(array(
				'success' => true,
				), 200);
		}

			}// AJAX
		}//<-- CHECK
		else {
			return Response::json(array('
			session_null' => true,
			'success' => false
			));
		}
      }//<--- END

      public function postInvite(){

      	if( Auth::check() ) {

			if(Request::ajax()) {

		$settings = AdminSettings::first();

		$inputs = Input::All();

		$invite = DB::table('invitations_join')->where('email',$inputs["email"])->first();

		if( isset( $invite ) ) {
			return Response::json(array(
			        'success' => false,
			        'error_custom' => Lang::get('misc.already_sent_invitation'),
			         ));
		}

		// Setup the validator
		$rules = array(
			'email'     => 'required|email|unique:members,email',
			);

		$messages = array(
		'unique' => Lang::get('misc.email_exists_invite'),
		);

		$validator = Validator::make($inputs, $rules, $messages);

		if( $validator->fails() ) {
			return Response::json(array(
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			         ));
		} else {

			DB::table('invitations_join')->insert(
			array(
				'user_id' => Auth::user()->id,
				'email' => trim( strtolower( $inputs["email"] ) ),
				)
			);

			$email      = $inputs["email"];
			$title_site = $settings->title;

			Mail::send('emails.invite', array( 'username' => Auth::user()->name.' @'.Auth::user()->username ),
			function($message) use ($email,$title_site) {
		            $message->to( $email )
		                ->subject( Lang::get('misc.invitation_to_join').' '.$title_site );
		        });

			 return Response::json(array(
				'success' => true,
				'message' => Lang::get('misc.success_invite'),
				), 200);
		}

			}// AJAX
		}//<-- CHECK
		else {
			return Response::json(array('
			session_null' => true,
			'success' => false
			));
		}
      }//<--- END

      public function postTeampaypalipn(){

      	$settings    = AdminSettings::first();
		$email = $settings->email_notifications;

		/* $string = 'item_id=1&name=Mike&email=inversionesdursot%40hotmail.com';
		 * parse_str($string, $custom);
		 * echo $custom['item_id'];*/

		$req = 'cmd=_notify-validate';
		$fullipnA = array();

		foreach ( $_POST as $key => $value ) {
			$fullipnA[$key] = $value;
			$encodedvalue = urlencode(stripslashes($value));
			$req .= "&$key=$encodedvalue";
		}

		$fullipn = Helper::Array2Str(" : ", "\n", $fullipnA );

		if ( $settings->paypal_sandbox == 1) {
		// SandBox
		$url = "https://www.sandbox.paypal.com/cgi-bin/webscr";
		} else {
		// Real environment
		$url = "https://www.paypal.com/cgi-bin/webscr";
		}

		$curl_result=$curl_err='';
		$fp = curl_init();
		curl_setopt($fp, CURLOPT_URL,$url);
		curl_setopt($fp, CURLOPT_RETURNTRANSFER,1);
		curl_setopt($fp, CURLOPT_POST, 1);
		curl_setopt($fp, CURLOPT_POSTFIELDS, $req);
		curl_setopt($fp, CURLOPT_HTTPHEADER, array("Content-Type: application/x-www-form-urlencoded", "Content-Length: " . strlen($req)));
		curl_setopt($fp, CURLOPT_HEADER , 0);
		curl_setopt($fp, CURLOPT_VERBOSE, 1);
		curl_setopt($fp, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($fp, CURLOPT_TIMEOUT, 30);

		$response = curl_exec($fp);
		$curl_err = curl_error($fp);
		curl_close($fp);


		// Vars received by Paypal
		$payment_status = $_POST['payment_status'];

	if ( strcmp ( $response, "VERIFIED") == 0 ) {

	// Check the status of the order
	if ( $payment_status != "Completed" ) {

		Mail::send('emails.payments.payment_failed', array( 'status' => $payment_status ),
		function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});
			exit;
	}
 	// all good so far, the transaction has been confirmed so I can do all -> Update DB, stock, credit computations, activate accounts etc etc

	// Vars received by Paypal
	$item_name        = $_POST['item_name'];
	$item_number      = $_POST['item_number'];
	$payment_status   = $_POST['payment_status'];
	$payment_amount   = $_POST['mc_gross'];
	$payment_currency = $_POST['mc_currency'];
	$txn_id           = $_POST['txn_id'];
	$receiver_email   = $_POST['receiver_email'];
	$payer_email      = $_POST['payer_email'];
	$txn_type         = $_POST['txn_type'];
	$payment_type     = $_POST['payment_type'];
	$custom           = $_POST['custom'];

	// Buyer information
	$first_name           = $_POST['first_name'];
	$last_name            = $_POST['last_name'];
	$address_name         = $_POST['address_name'];
	$address_country      = $_POST['address_country'];
	$address_country_code = $_POST['address_country_code'];
	$address_zip          = $_POST['address_zip'];
	$address_state        = $_POST['address_state'];
	$address_city         = $_POST['address_city'];
	$address_street       = $_POST['address_street'];


	//<---------- * INSERT TEAM * ----------->
	parse_str($custom, $inputs);

	if( isset( $inputs["username"] ) ){
		$inputs["username"] = $inputs["username"];
	} else {
		$inputs["username"] = '';
	}
	$find_user = User::where('username',$inputs["username"])->first();

	if( !isset( $find_user ) && $inputs["renew"] == 'false' ) {

		Mail::send('emails.payments.payment_success', array( 'status' => $payer_email, 'data' => $_POST ),
	function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});

		if( $inputs["renew"] == 'false' ) {

		//user_id=21&organization_name=Envato&workstation=UI Designeer&url_job=http://envato.com/jobs#designer&location=Anywhere
		$token = Str::random(75);

		$user                  = new User;
	    $user->name            = trim( $inputs["team_name"] );
	    $user->username        = trim( $inputs["username"] );
	    $user->email           = trim( strtolower( $inputs["email"] ) );
		$user->password        = Hash::make( $inputs["password"] );;
	    $user->date            = date( 'Y-m-d G:i:s', time() );
		$user->avatar          = 'default.jpg';
		$user->cover           = 'cover.jpg';
		$user->status          = 'active';
		$user->type_account    = 3;
		$user->activation_code = '';
		$user->token           = $token;
	    $user->save();

		$idTeam = $user->id;

		if( isset( $user->id ) ) {
			$login_true = array(
		    'email'    => trim( strtolower( $inputs["email"] ) ),
		    'password' => $inputs["password"]
			);

			Auth::attempt( $login_true );
		}
	}

		// Expire
		$expire = date('Y-m-d G:i:s', strtotime('+1 year'));

		DB::table('paypal_payments_teams')->insert(
	    	array(
	    	'user_id' => $idTeam,
	    	'item_name' => $item_name,
	    	'item_number' => $item_number,
	    	'payment_status' => $payment_status,
	    	'payment_amount' => $payment_amount,
	    	'payment_currency' => $payment_currency,
	    	'payer_email' => $payer_email,
	    	'payment_type' => $payment_type,
	    	'custom' => $custom,
	    	'invoice' => '',
	    	'first_name' => $first_name,
	    	'last_name' => $last_name,
	    	'address_name' => $address_name,
	    	'address_country' => $address_country,
	    	'address_country_code' => $address_country_code,
	    	'address_zip' => $address_zip,
	    	'address_state' => $address_state,
	    	'address_city' => $address_city,
	    	'address_street' => $address_street,
	    	'expire' => $expire,
			)
		);
	}// IF Find User

	if( $inputs["renew"] == 'true' ) {

		Mail::send('emails.payments.payment_success', array( 'status' => $payer_email, 'data' => $_POST ),
	function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});

			$idTeam = $inputs["team_id"];

			// Expire
			$expire = date('Y-m-d G:i:s', strtotime('+1 year'));

			DB::table('paypal_payments_teams')->insert(
		    	array(
		    	'user_id' => $idTeam,
		    	'item_name' => $item_name,
		    	'item_number' => $item_number,
		    	'payment_status' => $payment_status,
		    	'payment_amount' => $payment_amount,
		    	'payment_currency' => $payment_currency,
		    	'payer_email' => $payer_email,
		    	'payment_type' => $payment_type,
		    	'custom' => $custom,
		    	'invoice' => '',
		    	'first_name' => $first_name,
		    	'last_name' => $last_name,
		    	'address_name' => $address_name,
		    	'address_country' => $address_country,
		    	'address_country_code' => $address_country_code,
		    	'address_zip' => $address_zip,
		    	'address_state' => $address_state,
		    	'address_city' => $address_city,
		    	'address_street' => $address_street,
		    	'expire' => $expire,
				)
			);
		}


	  } else{
	  	 //the transaction is invalid I can NOT charge the client.
	  	 Mail::send('emails.payments.payment_failed', array( 'status' => $fullipn ),
	  	 function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});
	}
   }//<--- END

   public function getAddmembers(){

	if( Auth::check() ) {

		 if(Request::ajax()) {

			$settings = AdminSettings::first();
			$input  = Input::get('search');
			$idTeam = Auth::user()->id;

			$totalMembers = TeamMembers::where('team_id',Auth::user()->id)->count();

			if( $totalMembers == $settings->members_limit ) {
				$limit_reached = true;
				echo '<li class="list">
			<span class="notfound_auto none-overflow">'.Lang::get('misc.limit_reached').'</span>

			</li>';
			} else {
				$limit_reached = false;
			}

			$response = User::where('username', 'LIKE', '%'.$input.'%')
			->where('id','!=',Auth::user()->id)
			->where('type_account','!=',3)
			->where('status','active')
			->where('team_id',0)
			->orWhere('name', 'LIKE', '%'.$input.'%')
			->where('id','!=',Auth::user()->id)
			->where('type_account','!=',3)
			->where('team_id',0)
			->where('status','active')
			->orderBy('type_account','DESC')
			->take(4)
			->get();

			$countMembers  = count( $response );

if( $limit_reached == false ){
   if( $countMembers != 0 ) :

   foreach ( $response as $key ) {

	if( $key->type_account == '2' ) {
			$type_account = ' <span class="label label-primary btn-pro-xs">'.Lang::get("misc.pro").'</span>';
		} else {
			$type_account = null;
		}

	 echo '<li class="list">
   	 <a href="javascript:void(0);" data-id="'.$key->id.'" data-id-team="'.$idTeam.'" class="add-member">
   	 <img width="24" height="24" style="vertical-align: middle; border-radius: 3px; -webkit-border-radius: 3px; margin-right: 3px;" src="'.URL::to('public/avatar').'/'.$key->avatar.'">
   	 <span style="line-height: 18px;">
 		<strong>'.e($key->name).$type_account.'</strong>
		<strong style="font-weight: normal; font-size: 12px; vertical-align: 0; float: none;">@'.$key->username.'</strong>
 	</span></a>
   	 </li>';
	}// foreach

   else:

	   echo '<li class="list">
			<span class="notfound_auto">'.Lang::get('misc.no_results_found').'</span>

			</li>';

		endif;  //<<<--- $countPosts != 0
		 }//<-- limit_reached
		}//Request


	 }// Auth

   }//<--- End Method

   public function postAddmember() {

	 if( Auth::check() ){

		if(Request::ajax()) {

		$user_id = Input::get('user_id');

		$user = User::find($user_id);

		if( $user->type_account != 3
			&& $user->id != Auth::user()->id
			&& $user->team_id == 0
			){

				$data = new TeamMembers;
				$data->team_id = Auth::user()->id;
				$data->user_id = $user->id;
				$data->save();

				$user->team_id = Auth::user()->id;
				$user->save();

				$response = '<!-- Start MediaDesigner -->
<div class="media media-designer members-team-list">
    		<span class="pull-left">
    			<a class="image-thumb" title="'.$user->name.'" href="'.URL::to('@').$user->username.'">
    			<img width="45" height="45" class="media-object img-circle" src="'.URL::asset('public/avatar').'/'.$user->avatar.'">
    			</a>
    		</span>
    		<div class="media-body">
    			<div class="pull-left">
    				<h4 class="media-heading">
    				<a class="link-user-profile" title="'.$user->name.'" href="'.URL::to('@').$user->username.'">
    					'.$user->name.'</a>
    			</h4>
    			 <!-- List group -->
    	<ul class="list-group list-designer margin-zero">
    		<li>
    			 	'.'@'.$user->username.'
    			</li>
    			 </ul>
    			</div><!-- /End Pull Left -->

    			<span class="pull-right">
    				<i class="icon-cancel-circle delete-member" id="deleteBtn" data-id="'.$user->id.'" data-delete="'.Lang::get('misc.delete_member').'" title="'.Lang::get('misc.delete').'"></i>
    			</span>
  </div><!-- /End Media Body -->
</div><!-- End MediaDesigner -->';

				Notifications::send( $user->id, Auth::user()->id, 8, Auth::user()->id );

				return Response::json( array( 'success' => true, 'response' => $response ) );

		}// USER
		else {
				return Response::json( array( 'success' => false, 'error' => Lang::get('misc.error'), 'response' => $response ) );
			}
		}// Ajax
	  }//Auth
	   else {
		return Response::json( array ( 'session_null' => true ) );
	  }
	}//<--- END METHOD

   public function postDeletemember() {

	 if( Auth::check() ){

		if(Request::ajax()) {

		$user_id = Input::get('user_id');

		$user = User::find($user_id);

		$shots = Shots::where('team_id',Auth::user()->id)->get();

		$data = TeamMembers::where('user_id',$user_id)->where('team_id',Auth::user()->id)->first();

		$notification = Notifications::where('destination',$user_id)
		->where('author',Auth::user()->id)
		->where('type',8)
		->first();

		if( isset( $data ) ) {

			$data->delete();
			$notification->delete();

			$user->team_id = 0;
			$user->save();

			foreach($shots as $shot){
				$shot->team_id = 0;
				$shot->save();
			}

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

		public function postCommentslikes() {


			if(Request::ajax()) {

				$comment_id = Input::get('comment_id');

				$_array   = array();

				$data = CommentsLikes::where('comment_id', $comment_id)->where('status', 1)->get();

				if( !isset( $data ) ){
					return false;
					exit;
				}

				foreach ($data as $key) {
					$_array[] = '<li><a href="'.URL::to('@').$key->user()->username.'" class="showTooltip" data-toggle="tooltip" data-placement="left" title="'.$key->user()->name.'">
					<img src="'.URL::asset('public/avatar').'/'.$key->user()->avatar.'" class="img-circle" width="25">
					</a></li>';
				}
				return $_array;
				//return Response::json( array ( 'users' => $_array, 'error' => null, 'id' => $comment_id ) );

			}
		}//<--- END METHOD


}//<<--------------- End Class

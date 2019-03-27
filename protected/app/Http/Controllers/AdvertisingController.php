<?php
namespace App\Http\BaseController;
class AdvertisingController extends BaseController {
	
	protected $ads;

	public function __construct(Advertising $ads){
		$this->ads = $ads;
	}
	
	public function getIndex() {
		
		return Redirect::to('/');
	}
	
	public function postAdd(){
	  
	  if( Auth::check() ) {
	  	
		if(Request::ajax()) {	
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		$temp            = 'public/temp/'; //=== PATHS
		$path            = 'public/ad/'; //=== PATHS AD
			
			
			//============= FILES UPLOAD SHOT ========//
			$file_name    = Input::file('uploadImage');
			
			if( strlen( $file_name ) ) {
				$extension     = Input::file('uploadImage')->getClientOriginalExtension();
				$type_mime_ad  = Input::file('uploadImage')->getMimeType();
				$sizeFile      = Input::file('uploadImage')->getSize();
				$imageAd       = strtolower( time().'_'.Auth::user()->id.'_'.Str::quickRandom(5).".".$extension );
			
			if( $sizeFile > $settings->file_size_allowed ){
		 	return Response::json(array(
			        'success' => false,
			        'errors' => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ),
			
			    )); 
		 }
			
			}//file_name
		
		// Setup the validator
		$rules = array(
			'campaign_name'    => 'required|min:3|max:30',
			'ad_title'         => 'required|min:3|max:50',
			'ad_description'   => 'required|min:3|max:100',
			'ad_url'           => 'required|url',
			'uploadImage'      => 'required|max:'.$settings->file_size_allowed.'|mimes:jpg,gif,png,jpe,jpeg',
			'type'             => 'required',
			'quantity'         => 'required',
			
			);
			
		$messages = array (
			'uploadImage.required' => Lang::get('misc.please_select_image'),
            "required"             => Lang::get('validation.required'),
    	);
		
		$validator = Validator::make($inputs, $rules, $messages);

		// Validate the input and return correct response
			if ($validator->fails()) {
			    return Response::json(array(
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			
			    )); 
				
			}  else {
				
				if( $file_name->move( $temp, $imageAd ) ) {

					Helper::resizeImageFixed( $temp.$imageAd, 268, 208, $temp.$imageAd );
					
					//======= Copy folder FILE =========//
					if ( File::exists($temp.$imageAd) ) {
						
						/* IMAGE */	
						File::copy($temp.$imageAd, $path.$imageAd);
						File::delete($temp.$imageAd);
					}//<--- IF IMAGE EXISTS
				}
				
				$code = Auth::user()->id.Str::random($length = 40);
				// Insert DATABASE
				$ads                 = new Advertising;
				$ads->user_id        = Auth::user()->id;
				$ads->campaign_name  = trim($inputs['campaign_name']);
				$ads->ad_title       = trim($inputs['ad_title']);
				$ads->ad_desc        = trim($inputs['ad_description']);
				$ads->ad_url         = trim($inputs['ad_url']);
				$ads->code           = $code;
				$ads->ad_image       = $imageAd;
				$ads->type           = $inputs['type'];
				$ads->quantity       = $inputs['quantity'];
				$ads->balance        = 0;
				$ads->save();
								
				$url_ads = URL::to('ads/activate').'/'.$code;
				
				return Response::json(array(
				'success' => true,
				'target' => $url_ads,
				), 200);
				
				}//<--- ELSE TRUE
		}//<--- Ajax
	  }//<--- Check
		  else {
			return Response::json(array('
			session_null' => true,
			'success' => false
			));
		}
	}//<--- End Method
	
	public function getEdit( $code ){
		
		$data = $this->ads->where('code',$code)->where('user_id',Auth::user()->id)->first();
		  
		  if( !isset( $data ) ) {
		  	App::abort('404');
		  	exit;
		  } else {
		  	return View::make('user.edit-ad')->with('data', $data);
		  }
		
	}
	
	public function postEdit(){
	  
	  if( Auth::check() ) {
	  	
		if(Request::ajax()) {	
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		$data = $this->ads->where('code',$inputs['code'])->where('user_id',Auth::user()->id)->first();
				  
		  if( !isset( $data ) ) {
		  	return Response::json(array('
			session_null' => true,
			'success' => false
			));
		  	exit;
		  } else {
		
		$temp            = 'public/temp/'; //=== PATHS
		$path            = 'public/ad/'; //=== PATHS AD
		$pathOldImage    = 'public/ad/'.$data->ad_image; //=== Old Image
			
			
			//============= FILES UPLOAD SHOT ========//
			$file_name    = Input::file('uploadImage');
			
			if( strlen( $file_name ) ) {
				$extension     = Input::file('uploadImage')->getClientOriginalExtension();
				$type_mime_ad  = Input::file('uploadImage')->getMimeType();
				$sizeFile      = Input::file('uploadImage')->getSize();
				$imageAd       = strtolower( time().'_'.Auth::user()->id.'_'.Str::quickRandom(5).".".$extension );
			
			if( $sizeFile > $settings->file_size_allowed ){
		 	return Response::json(array(
			        'success' => false,
			        'errors' => Lang::get('misc.max_size').' '.Helper::formatBytes( $settings->file_size_allowed, 0 ),
			
			    )); 
		 }
			
			}//file_name
		
		if( isset( $file_name ) ) {
			// Setup the validator
		$rules = array(
			'campaign_name'    => 'required|min:3|max:30',
			'ad_title'         => 'required|min:3|max:50',
			'ad_description'   => 'required|min:3|max:100',
			'ad_url'           => 'required|url',
			'uploadImage'      => 'required|max:'.$settings->file_size_allowed.'|mimes:jpg,gif,png,jpe,jpeg',
			);
		} else {
			// Setup the validator
		$rules = array(
			'campaign_name'    => 'required|min:3|max:30',
			'ad_title'         => 'required|min:3|max:50',
			'ad_description'   => 'required|min:3|max:100',
			'ad_url'           => 'required|url',
			);
		}
		
			
		$messages = array (
            "required"             => Lang::get('validation.required'),
    	);
		
		$validator = Validator::make($inputs, $rules, $messages);

		// Validate the input and return correct response
			if ($validator->fails()) {
			    return Response::json(array(
			        'success' => false,
			        'errors' => $validator->getMessageBag()->toArray(),
			
			    )); 
				
			}  else {
				
				if( isset( $file_name ) && $file_name->move( $temp, $imageAd ) ) {

					Helper::resizeImageFixed( $temp.$imageAd, 268, 208, $temp.$imageAd );
					
					//======= Copy folder FILE =========//
					if ( File::exists($temp.$imageAd) ) {
						
						/* IMAGE */	
						File::copy($temp.$imageAd, $path.$imageAd);
						File::delete($temp.$imageAd);
						
						/**** Delete Image Old ****/
						if( File::exists($pathOldImage) ) {
							File::delete($pathOldImage);
						}
						
						// Path
						$imageAd = $imageAd;
						
					}//<--- IF IMAGE EXISTS
				} else {
					$imageAd = $data->ad_image;
				}
				
				// UPDATE
				$data->campaign_name = trim($inputs['campaign_name']);
				$data->ad_title      = trim($inputs['ad_title']);
				$data->ad_desc       = trim($inputs['ad_description']);
				$data->ad_url        = trim($inputs['ad_url']);
				$data->ad_image      = $imageAd;
				$data->type          = $data->type;
				$data->quantity      = $data->quantity;
				$data->status        = $inputs['status'];
				$data->save();
				
				return Response::json(array(
				'success' => true,
				), 200);
				
				}//<--- ELSE TRUE
			}//<--- Check AD
		}//<--- Ajax
	  }//<--- Check
		  else {
			return Response::json(array('
			session_null' => true,
			'success' => false
			));
		}
	}//<--- End Method
	
	public function getActivate( $code ){
		
		$data = $this->ads->where('code',$code)->where('user_id',Auth::user()->id)->first();
		  
		  if( !isset( $data ) ) {
		  	App::abort('404');
		  	exit;
		  } else {
		  	
			// Payments
	   $payments = DB::table('paypal_payments_ads')->where('item_id',$data->id)->orderBy('id','DESC')->first();
	 
	  //Check that the ad is Activate / Expired  	
	  if( 
	  isset( $payments->payment_status ) 
	  && $payments->payment_status == 'Completed' 
	  && $data->balance < $data->quantity  
	  ){
	   		return Redirect::to('my/ads');
	   }
			
		  	return View::make('user.activate-ad')->with('data', $data);
		  }
	}
	
	public function postActivate(){
		
		if( Auth::check() ) {
	  	
		$settings = AdminSettings::first();
		$inputs = Input::All();
		
		$data = $this->ads->where('code',$inputs['ad_code'])->where('user_id',Auth::user()->id)->first();
		
			if( isset( $data ) ) {
				$data->type          = $inputs['type'];
				$data->quantity      = $inputs['quantity'];
				$data->save();
			}
			
			return Redirect::back();
		}
	}
	
	public function delete($code){
	  
	  if( Auth::check() ) {
	  			  
		$ad = $this->ads->where('code',$code)->where('user_id',Auth::user()->id)->first();
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
			
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method
	
	public function postPayment(){
		
		if ( Auth::check() ){
		return View::make('user.payment-ad');
	} else {
		return Redirect::to('/');
	}	
	}

	public function postPaypalipn(){
		
		$settings    = AdminSettings::first();
		$email = $settings->email_notifications;
		
		/* $string = 'item_id=3';
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
	
	
	parse_str($custom, $ads_settings);
	
	$findAdsToken = DB::table('paypal_payments_ads')->where('token',$ads_settings['token'])->first();
	
	if( !isset( $findAdsToken ) ){
			Mail::send('emails.payments.payment_success', array( 'status' => $payer_email, 'data' => $_POST ), 
			function($message) use($email) {
					            $message->to($email)
					                ->subject( Lang::get('misc.new_payment') );
							});
						
			DB::table('paypal_payments_ads')->insert(
		    	array(
		    	'item_id' => $ads_settings['id_ad'],
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
		    	'token' => $ads_settings['token'],
				)
			);
			
			// Balance to 0
			$ad = Advertising::find($ads_settings['id_ad']);
			
			$ad->type     = $ads_settings['type'];
			$ad->quantity = $ads_settings['quantity'];
			$ad->balance = 0;
			$ad->save();
			
		}// Find Ads Token
 
	  } else{ 	
	  	 //the transaction is invalid I can NOT charge the client. 
	  	 Mail::send('emails.payments.payment_failed', array( 'status' => $fullipn ), 
	  	 function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});
	}
				
	}//<--- End Method
	
	public function postSuccess(){
		
		if ( Auth::check() ){
		Session::flash('success_add_ad',Lang::get('misc.success_add_ad'));
		return Redirect::to('my/ads');
		} else {
			return Redirect::to('/');
		}
	}//<--- End Method
	
	public function clicks($id){

		$data = $this->ads->where('id',$id)->first();
		
		if( !isset( $data ) ) {
			return Redirect::to('/');
		} else {
			
			if( $data->type == 'clicks' ){
				
				// Insert Clicks
			Advertising::clicksAds($data->id);
			
			// URL TO URL
			return Redirect::to($data->ad_url);
				
				
				
			} else {
				
				return Redirect::to($data->ad_url);
			}
		}//<-- ELSE
		
	}//<--- End Method
	
}
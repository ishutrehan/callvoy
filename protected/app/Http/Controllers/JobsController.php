<?php
namespace App\Http\Controllers;
use App\Models\AdminSettings;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use View;
class JobsController extends BaseController {
	
	protected $jobs;

	public function __construct(Jobs $jobs){
		$this->jobs = $jobs;
	}
	
	public function getIndex() {
		
		return Redirect::to('/');
	}
	
	public function getNew(){
		
		$settings = AdminSettings::first();
		
		if( Auth::check() && $settings->allow_jobs == 'on' ) {
		return View::make('default.jobs-new');
	} else {
		return Redirect::to('/login');
	}
	}
	
	public function add(){
	  
	  if( Auth::check() ) {
	  		
		$settings = AdminSettings::first();
		
		$inputs = Input::All();
		
		// Setup the validator
		$rules = array(
			'organization_name'      => 'required|min:3|max:30',
			'job_title'              => 'required|min:3|max:30',
			'url_to_job_description' => 'required|url',
			'location'               => 'required|min:2|max:40',
			'contact_name'           => 'required|min:3|max:30',
			'contact_email'          => 'required|email',
			
		);

		$validator = Validator::make($inputs, $rules);
		
		if( $validator->fails() ) {
            return Redirect::back()->withInput()->withErrors( $validator );
		} else {

			return View::make('default.jobs-pay');
		}
	  }
	}//<--- End Method
	
	public function getEdit( $token ){
		
		$data = Jobs::where('token',$token)->where('user_id',Auth::user()->id)->first();
		
		if( !isset( $data ) ) {
		  	App::abort('404');
		  	exit;
		  } else {
		  	return View::make('user.edit-job')->with('data', $data);
		  }
		
	}
	
	public function edit(){
	  
	  if( Auth::check() ) {
	  	
		$settings = AdminSettings::first();
		$inputs = Input::All();
		  
		$job = Jobs::where('token',$inputs['token'])->where('user_id',Auth::user()->id)->first();
		  
		  if( !isset( $job ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {

		// Setup the validator
		$rules = array(
			'url_to_job_description' => 'required|url',
			'location'               => 'required|min:2|max:40',
			
			);

		$validator = Validator::make($inputs, $rules);
		
		if( $validator->fails() ) {
            return Redirect::back()->withInput()->withErrors( $validator );
		} else {
			
			// UPDATE JOB
			$job->url_job  = $inputs['url_to_job_description'];
			$job->location = $inputs['location'];
			$job->save();
			
			Session::flash('notification',Lang::get('misc.success_update_job'));
			return Redirect::back();
		}
		  }//else !Job
	  }
	}//<--- End Method
	
	public function delete($token){
	  
	  if( Auth::check() ) {
	  	
		$settings = AdminSettings::first();
		  
		$job = Jobs::where('token',$token)->where('user_id',Auth::user()->id)->first();
		  
		  if( !isset( $job ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {
			// DELETE JOB
			$job->delete();
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method
	
	public function postPayment(){
		
		if ( Auth::check() ){
		return View::make('default.payment-job');
	} else {
		return Redirect::to('/');
	}	
	}

	public function postPaypalipn(){
		
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
	
	switch( $settings->duration_jobs ) {
			case '15days':
				$duration_jobs  = '+15 days';
				break;
			case '30days':
				$duration_jobs  = '+1 month';
				break;
			case '60days':
				$duration_jobs  = '+2 month';
				break;
			case '90days':
				$duration_jobs  = '+3 month';
				break;
		}
	
	//<---------- * INSERT JOB * ----------->				
	$date = date('Y-m-d G:i:s', strtotime($duration_jobs));
	parse_str($custom, $jobs);
	
	$findJobExists = Jobs::where('token',$jobs['token'])->first();
  
  if( !isset( $findJobExists ) ){
  	
	Mail::send('emails.payments.payment_success', array( 'status' => $payer_email, 'data' => $_POST ), 
	function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});
  	
	//user_id=21&organization_name=Envato&workstation=UI Designeer&url_job=http://envato.com/jobs#designer&location=Anywhere
			$job              = new Jobs;
			$job->user_id     = $jobs['user_id'];
			$job->organization_name = $jobs['organization_name'];
			$job->workstation = $jobs['workstation'];
			$job->url_job     = $jobs['url_job'];
			$job->location    = $jobs['location'];
			$job->date_end    = $date;
			$job->token       = $jobs['token'];
			$job->save();
				
			$idJob = $job->id;
			
						
			DB::table('paypal_payments_jobs')->insert(
		    	array(
		    	'item_id' => $idJob,
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
				)
			);
		}//<<-- IF FIND
 
	  } else{ 	
	  	 //the transaction is invalid I can NOT charge the client. 
	  	 Mail::send('emails.payments.payment_failed', array( 'status' => $fullipn ), 
	  	 function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});
	}
				
	}//<--- End Method
	
	public function postPaypalipnactivate(){
		
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
	
	switch( $settings->duration_jobs ) {
			case '15days':
				$duration_jobs  = '+15 days';
				break;
			case '30days':
				$duration_jobs  = '+1 month';
				break;
			case '60days':
				$duration_jobs  = '+2 month';
				break;
			case '90days':
				$duration_jobs  = '+3 month';
				break;
		}
	
	//<---------- * INSERT JOB * ----------->				
	$date = date('Y-m-d G:i:s', strtotime($duration_jobs));
	parse_str($custom, $jobs);
	
	$findJobExists = Jobs::where('token',$jobs['token'])->where('user_id',$jobs['user_id'])->first();
  
  if( isset( $findJobExists ) ){
  	
	Mail::send('emails.payments.payment_success', array( 'status' => $payer_email, 'data' => $_POST ), 
	function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});
  	
	//user_id=21&organization_name=Envato&workstation=UI Designeer&url_job=http://envato.com/jobs#designer&location=Anywhere
			$findJobExists->date_end = $date;
			$findJobExists->save();
				
			$idJob = $findJobExists->id;
			
						
			DB::table('paypal_payments_jobs')->insert(
		    	array(
		    	'item_id' => $idJob,
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
				)
			);
		}//<<-- IF FIND
 
	  } else{ 	
	  	 //the transaction is invalid I can NOT charge the client. 
	  	 Mail::send('emails.payments.payment_failed', array( 'status' => $fullipn ), 
	  	 function($message) use($email) {
			            $message->to($email)
			                ->subject( Lang::get('misc.new_payment') );
					});
	}
				
	}//<--- End Method
}
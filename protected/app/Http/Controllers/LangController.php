<?php
namespace App\Http\Controllers;
use App\Models\AdminSettings;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use View;
class LangController extends Controller {

	/**
	 * Setup the layout used by the controller.
	 *
	 * @return void
	 */
	 
	 	 
	 // English language
	/*public function getEn()
	{
		 Session::put('language','en');
		 return Redirect::to('/');
	}*/
	 
	 public function add_lang() {
	
	if( Auth::check() && Auth::user()->role != 'normal' ) {
					 
		$inputs = Input::All();
		
		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});
			
		$rules = array(
		
			'title'          =>  'required',
			'abbreviation'   =>  'required|min:2|max:2|ascii_only|alpha_dash|unique:languages',
	    );
		
		$messages = array (
            "required"   => Lang::get('validation.required'),
            "ascii_only" => Lang::get('validation.alpha_dash'),
            "alpha_dash" => Lang::get('validation.alpha_dash'),
			"unique"     => Lang::get('validation.unique'),
        );
		

			$validation = Validator::make( $inputs, $rules, $messages );
			
			if($validation->fails()) {
	            return Redirect::to('panel/admin/languages/new')->withInput()->withErrors( $validation );
	        } else {
	        	
				$lang               = new Languages;
				$lang->name         = trim($inputs['title']);
				$lang->abbreviation = trim(strtolower($inputs['abbreviation']));;
				$lang->save();
				
				Session::flash('notification',Lang::get('misc.success_lang_create'));
	        	return Redirect::to('panel/admin/languages/new');
	        }
	       } else {
	       	return Redirect::to('/');
	       }
		}//<--- End Method
		
		
		public function edit_lang() {
	
	if( Auth::check() && Auth::user()->role != 'normal' ) {
					 
		$inputs = Input::All();
		
		Validator::extend('ascii_only', function($attribute, $value, $parameters){
    		return !preg_match('/[^x00-x7F\-]/i', $value);
		});
		
		$lang = Languages::find($inputs['id']);
		
		if( !$lang ) {
			 return Redirect::to('panel/admin/pages');
		}
			
		$rules = array(
		
			'title'          =>  'required',
			'abbreviation'   =>  'required|min:2|max:2|ascii_only|alpha_dash|unique:languages,abbreviation,'.$inputs['id'],
	    );
		
		$messages = array (
            "required"   => Lang::get('validation.required'),
            "ascii_only" => Lang::get('validation.alpha_dash'),
            "alpha_dash" => Lang::get('validation.alpha_dash'),
			"unique"     => Lang::get('validation.unique'),
        );
		

			$validation = Validator::make( $inputs, $rules, $messages );
			
			if($validation->fails()) {
	            return Redirect::back()->withInput()->withErrors($validation);
	        } else {
	        	
				$lang->name         = trim($inputs['title']);
				$lang->abbreviation = trim(strtolower($inputs['abbreviation']));;
				$lang->save();
				
				Session::flash('notification',Lang::get('misc.success_update'));
	        	return Redirect::back();
	        }
	       } else {
	       	return Redirect::to('/');
	       }
		}//<--- End Method
		
	public function delete_lang($id){
	  
	  if( Auth::check() && Auth::user()->role != 'normal' ) {
		
		$lang = Languages::find( $id );
		  
		  if( !isset( $lang ) ) {
		  	return Redirect::to('/');
		  	exit;
		  } else {

			// DELETE PAGE
			$lang->delete();
			return Redirect::back();
		  }//else !Job
	  }
	}//<--- End Method


}

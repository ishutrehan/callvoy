<?php
namespace App\Http\Controllers;
use App\Models\AdminSettings;
use App\Models\Shots;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use View;
class UpgradeController extends BaseController {

	
	public function getVersion16() {
		
		$settings    = AdminSettings::first();
		$users    = User::first();
		
		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.Lang::get('admin.upgrade_done').'</h2>';
		
		if( Auth::check() && Auth::user()->role == 'admin'   ){
			
				if( !isset( $settings->team_free )
					&& !isset( $settings->allow_ads ) 
					&& !isset( $settings->allow_jobs )
					&& !isset( $settings->pro_users_default ) 
					&& !isset( $users->team_free )
				)
				{
				 // Admin Settings
				 Schema::table('admin_settings', function($table){
					$table->enum('team_free', ['off', 'on'])->default('off')->after('instagram');
					$table->enum('allow_ads', ['off', 'on'])->default('on')->after('instagram');
					$table->enum('allow_jobs', ['off', 'on'])->default('on')->after('instagram');
					$table->enum('pro_users_default', ['off', 'on'])->default('off')->after('instagram');
				 });
				 
				 // Members
				 Schema::table('members', function($table){
					$table->enum('team_free', ['0', '1'])->default('0')->after('token');
				 });
				 
				 return $upgradeDone;
				 
			} else {
				return Redirect::to('/');
			}
			
		} else {
			App::abort('404');
		}
	}//<--- End Method
	
	
	public function getVersion17() {
		
		$settings    = AdminSettings::first();
		
		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.Lang::get('admin.upgrade_done').'</h2>';
		
		if( Auth::check() && Auth::user()->role == 'admin'   ){
			
				if( !isset( $settings->google_adsense )	)
				{
				 // Admin Settings
				 Schema::table('admin_settings', function($table){
					$table->text('google_adsense')->after('team_free');
				 });
				 
				 return $upgradeDone;
				 
			} else {
				return Redirect::to('/');
			}
			
		} else {
			App::abort('404');
		}
	}//<--- End Method
	
	
	public function getVersion18() {
		
		$settings    = AdminSettings::first();
		
		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.Lang::get('admin.upgrade_done').'</h2>';
		
		if( Auth::check() && Auth::user()->role == 'admin'   ){
			
				if( !isset( $settings->user_no_pro_comment_on )	)
				{
				 // Admin Settings
				 Schema::table('admin_settings', function($table){
					$table->enum('user_no_pro_comment_on', ['off', 'on'])->default('off')->after('google_adsense');
				 });
				 
				 return $upgradeDone;
				 
			} else {
				return Redirect::to('/');
			}
			
		} else {
			App::abort('404');
		}
	}//<--- End Method
	
	public function getVersion20() {
		$settings    = AdminSettings::first();
		$shots       = Shots::first();
		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.Lang::get('admin.upgrade_done').'</h2>';
		
	if( Auth::check() && Auth::user()->role == 'admin'   ){
		if( !isset( $shots->original_image ) )
				{
					// Shots
				 Schema::table('shots', function($table){
					$table->string('original_image', 150)->after('large_image');
				 });
				 
				 return $upgradeDone;
				 
				} else {
					return Redirect::to('/');
				}
	      }  else {
				App::abort('404');
		}
	}//<--- End Method
	
	public function getVersion21(){
		
		$upgradeDone = '<h2 style="text-align:center; margin-top: 30px; font-family: Arial, san-serif;color: #4BBA0B;">'.Lang::get('admin.upgrade_done').'</h2>';
	
	if( Auth::check() && Auth::user()->role == 'admin'   ){	
		if (Schema::hasTable('languages'))
		{
		   return Redirect::to('/');
		} else {
			
			Schema::create('languages', function($table)
		        {
		            $table->increments('id');
		            $table->string('name', 100);
		            $table->string('abbreviation', 32);
		        });
			
			if( Schema::hasTable('languages') ) {
				DB::table('languages')->insert(
    				array('name' => 'English', 'abbreviation' => 'en')
				);
			}	
				
			return $upgradeDone;
		}
		
	  } else {
				App::abort('404');
		}
		
	}//<--- End Method
	
	
}//<--- En Class
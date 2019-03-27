<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Advertising;
use DB;
class Advertising extends Model {
	
	protected $table = 'advertising';
	protected $guarded = array();
	public   $timestamps = false;
		
	public function user() {
        return $this->belongsTo('User')->first();
    }
	
	public static function totalStats( $id, $type ){
		//Verify that ad type
		if( $type == 'clicks' ) {
			$table = 'ad_clicks';
		} else if( $type == 'impressions' ) {
			$table = 'ad_impressions';
		}
		return DB::table($table)->where('ad_id', '=', $id )->count();
	}//<--- End Method		
	
	public static function impressionsAds( $id_ad ){
		
		$data = Advertising::where('id',$id_ad)->first();
		
		if( !isset( $data ) ) {
			return false;
			exit;
		}
	 
		//<--------- * clicks_impressions_ads * ---------->
			$user_IP = $_SERVER['REMOTE_ADDR'];
			$date = time();
			
			if( Auth::check() ) {
				// SELECT IF YOU REGISTERED AND VISITED THE PUBLICATION
				$impressionCheckUser = DB::table('ad_impressions')->where('ad_id',$id_ad)->where('user_id',Auth::user()->id)->first();
				
				if( !$impressionCheckUser && Auth::user()->id != $data->user_id ) {
					
					//<<-- Insert
					DB::table('ad_impressions')->insert(
					array(
						'ad_id'   => $id_ad,
						'user_id' => Auth::user()->id,
						'ip'      => $user_IP
						)
					);//<<-- End Insert
					
					// Drecrement Balance
					$data->increment('balance',1); 
				}//<-- IF

			} else {
				
				// IF YOU SELECT "UNREGISTERED" ALREADY VISITED THE PUBLICATION
				$impressionCheckGuest = DB::table('ad_impressions')->where('ad_id',$id_ad)->where('user_id',0)
				->where('ip',$user_IP)
				->orderBy('date','desc')
				->first();

			if( $impressionCheckGuest )	{
				  $dateGuest = strtotime( $impressionCheckGuest->date  ) + ( 7200 ); // 2 Hours
			}
				if( empty( $impressionCheckGuest->ip )  ) {
					//<<-- Insert
					DB::table('ad_impressions')->insert(
					array(
						'ad_id'   => $id_ad,
						'user_id' => 0,
						'ip'      => $user_IP
						)
					);//<<-- End Insert
					
					// Drecrement Balance
					$data->increment('balance',1);
					
			   } else if( $dateGuest < $date ) {
			   		//<<-- Insert
					DB::table('ad_impressions')->insert(
					array(
						'ad_id'   => $id_ad,
						'user_id' => 0,
						'ip'      => $user_IP
						)
					);//<<-- End Insert
					
					// Drecrement Balance
					$data->increment('balance',1);
			   }
			}//<--------- * clicks_impressions_ads * ---------->
	}//<--------- * End Method * ---------->
	
	public static function clicksAds( $id_ad ){
		
		$data = Advertising::where('id',$id_ad)->first();
		
		if( !isset( $data ) ) {
			return false;
			exit;
		}
	 
		//<--------- * clicks_impressions_ads * ---------->
			$user_IP = $_SERVER['REMOTE_ADDR'];
			$date = time();
			
			if( Auth::check() ) {
				// SELECT IF YOU REGISTERED AND VISITED THE PUBLICATION
				$clicksCheckUser = DB::table('ad_clicks')->where('ad_id',$id_ad)->where('user_id',Auth::user()->id)->first();
				
				if( !$clicksCheckUser && Auth::user()->id != $data->user_id ) {
					
					//<<-- Insert
					DB::table('ad_clicks')->insert(
					array(
						'ad_id'   => $id_ad,
						'user_id' => Auth::user()->id,
						'ip'      => $user_IP
						)
					);//<<-- End Insert
					
					// Drecrement Balance
					$data->increment('balance',1); 
				}//<-- IF

			} else {
				
				// IF YOU SELECT "UNREGISTERED" ALREADY VISITED THE PUBLICATION
				$clicksCheckGuest = DB::table('ad_clicks')->where('ad_id',$id_ad)->where('user_id',0)
				->where('ip',$user_IP)
				->orderBy('date','desc')
				->first();

			if( isset( $clicksCheckGuest ) )	{
				  $dateGuest = strtotime( $clicksCheckGuest->date  ) + ( 7200 ); // 2 Hours
			}
				if( empty( $clicksCheckGuest->ip )  ) {
					//<<-- Insert
					DB::table('ad_clicks')->insert(
					array(
						'ad_id'   => $id_ad,
						'user_id' => 0,
						'ip'      => $user_IP
						)
					);//<<-- End Insert
					
					// Drecrement Balance
					$data->increment('balance',1);
					
			   } else if( $dateGuest < $date ) {
			   		//<<-- Insert
					DB::table('ad_clicks')->insert(
					array(
						'ad_id'   => $id_ad,
						'user_id' => 0,
						'ip'      => $user_IP
						)
					);//<<-- End Insert
					
					// Drecrement Balance
					$data->increment('balance',1);
			   }
			}//<--------- * clicks_impressions_ads * ---------->
	}//<--------- * End Method * ---------->
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shots;
class Shots extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	 protected $guarded = array();
	 public $timestamps = false;
	
	 public function user() {
        return $this->belongsTo('User')->first();
    }
	 
	 public function comments(){
		return $this->hasMany('Comments');
	}
	 
	 public function visits(){
		return $this->hasMany('Visits');
	}
	 
	 public function project(){
        return $this->belongsTo('Projects','id_project');
    }
	 
	 public function lists(){
        return $this->belongsTo('Lists');
    }

	 
	 public static function getShots( $orderBy ) {
	 	
		$settings = AdminSettings::first();
		
		//$date = date( 'Y-m-d G:i:s', time() );
		$date = date('Y-m-d G:i:s', strtotime('-12 month'));
		
	 	$sql = DB::table('shots')
		->select(DB::raw('
		COUNT( likes.shots_id ) totalLikes,
		members.id as id_user,
		members.name,
		members.username,
		members.avatar,
		members.type_account,
		shots.id as id_shot,
		shots.image,
		shots.attachment,
		shots.extension,
		shots.title'))
		->leftjoin('members', 'shots.user_id', '=', 'members.id')
		->leftjoin('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
		->where('shots.status', 1 )
		->where('members.status', 'active' )
		//->where('shots.date', '>=', $date)
		->groupBy('shots.id')
		->orderBy( $orderBy, 'desc' )
		->paginate( $settings->result_request );
		
		return $sql;
	 }
	 
	 public static function getShotsFollowing() {
	 	
		$settings = AdminSettings::first();
		$userAuth = Auth::user();
				
	 	$sql = DB::table('shots')
		->select(DB::raw('
		COUNT( likes.shots_id ) totalLikes,
		COUNT( followers.id ) ShotsFollowing,
		members.id as id_user,
		members.name,
		members.username,
		members.avatar,
		members.type_account,
		shots.id as id_shot,
		shots.extension,
		shots.attachment,
		shots.image,
		shots.title'))
		->leftjoin('members', 'shots.user_id', '=', 'members.id')
		->leftjoin('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
		->leftjoin('followers', 'shots.user_id', '=', DB::raw('followers.following AND followers.status = "1"'))
		->where('shots.status', 1 )
		->where('members.status', 'active' )
		->where('followers.follower', '=', $userAuth->id )
		->groupBy('shots.id')
		->orderBy( 'shots.id', 'desc' )
		->paginate( $settings->result_request );
		
		return $sql;
	 }
	 
	 public static function getAllShotsUser( $id ) {
	 	
		$settings = AdminSettings::first();
				
	 	$sql = DB::table('shots')
		->select(DB::raw('
		members.id as id_user,
		members.name,
		members.username,
		members.avatar,
		members.type_account,
		shots.id as id_shot,
		shots.extension,
		shots.attachment,
		shots.image,
		shots.title'))
		->leftjoin('members', 'shots.user_id', '=', 'members.id')
		->leftjoin('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
		->where('shots.status', 1 )
		->where('shots.user_id', '=', $id)
		->where('members.status', 'active' )
		->orWhere('shots.team_id', $id )
		->where('shots.status', 1 )
		->where('members.status', 'active' )
		->groupBy('shots.id')
		->orderBy( 'shots.id', 'desc' )
		->paginate( $settings->result_request );
		
		return $sql;
	 }
	 
	 public static function getLikesShots( $id ) {
	 	
		$settings = AdminSettings::first();
				
	 	$sql = DB::table('shots')
		->select(DB::raw('
		members.id as id_user,
		members.name,
		members.username,
		members.avatar,
		members.type_account,
		shots.id as id_shot,
		shots.image,
		shots.extension,
		shots.attachment,
		shots.title'))
		->leftjoin('members', 'shots.user_id', '=', 'members.id')
		->leftjoin('likes', 'shots.id', '=', DB::raw('likes.shots_id AND likes.status = "1"'))
		->leftjoin('likes as L', 'shots.id', '=', DB::raw('L.shots_id AND L.status = "1"') )
		->where('shots.status', 1 )
		->where('L.user_id', '=', $id)
		->where('members.status', 'active' )
		->groupBy('shots.id')
		->orderBy( 'L.id', 'DESC' )
		->paginate( $settings->result_request );
		
		return $sql;
	 }
	 
	 public static function totalLikes( $id ){
		return DB::table('likes')->where('shots_id', '=', $id )->where('status', '=', 1)->count();
	}
	
	public static function totalVisits( $id ){
		return DB::table('visits')->where('shots_id', '=', $id )->count();
	}
	
	public static function totalComments( $id ){
		return DB::table('comments')->where('shots_id', '=', $id)->where('status', '=', 1)->count();
	}
	 

}
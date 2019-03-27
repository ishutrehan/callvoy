<?php
namespace App\Models;
use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Illuminate\Database\Eloquent\Model;

// use App\Models\User;

// class User extends Model implements UserInterface, RemindableInterface {
class User extends Model {
	
	// use UserTrait, RemindableTrait;
	
	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'members';
	public $timestamps = false;
		 
	 /**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	/**
	 * Get the unique identifier for the user.
	 *
	 * @return mixed
	 */
	 public function shots() {
        return $this->hasMany('Shots');
    }
	 public function notifications() {
        return $this->hasMany('Notifications', 'destination');
    }
	 
	 public function projects() {
        return $this->hasMany('Projects');
    }
	 
	 public function messages() {
        return $this->hasMany('Messages', 'to_user_id');
    }
	 public function lists() {
        return $this->hasMany('Lists');
    }
	 
	 public function listed() {
        return $this->hasMany('ListsUsers');
    }
	 
	 public function sent_messages() {
    return $this->hasMany('Messages', 'from_user_id');
	 }
	 
	 /**
	 * Get total Shots.
	 *
	 */
	 public static function totalShots( $id ){
		return DB::table('shots')->where('user_id', '=', $id )->where('status', '=', 1)->orWhere('team_id', $id )->where('status', '=', 1)->count();
	}
	 
	 /**
	 * Get total Likes.
	 *
	 */
	 public static function totalLikes( $id ){
		return DB::table('likes')->where('user_id', '=', $id )->where('status', '=', 1)->count();
	}
	 
	  /**
	 * Get total Followers.
	 *
	 */
	 public static function totalFollowers( $id ){
		return DB::table('followers')->where('following', '=', $id )->where('status', '=', 1)->count();
	}
	 
	  /**
	 * Get total Following.
	 *
	 */
	 public static function totalFollowing( $id ){
		return DB::table('followers')->where('follower', '=', $id )->where('status', '=', 1)->count();
	}
	 
	  /**
	 * Get total Projects.
	 *
	 */
	 public function total_projects(){
		return DB::table('projects')->where('user_id', '=', $this->id )->count();
	}
	 
	 public function total_lists(){
		return DB::table('lists')->where('user_id', '=', $this->id)->count();
	}
	 
	 public function total_listed(){
		return DB::table('lists_users')->where('lists_users.user_id', '=', $this->id)->join('lists','lists.id','=',DB::raw('lists_users.lists_id AND lists.type = "1"'))->count();
	}
	 
}
<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\MembersReported;
class MembersReported extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	 protected $table = 'members_reported';
	 protected $guarded = array();
	 public $timestamps = false;
	
	 public function user(){
		return $this->belongsTo('User')->first();
	}
	 
	 public function user_reported(){
		return $this->belongsTo('User','id_reported')->first();
	}

}
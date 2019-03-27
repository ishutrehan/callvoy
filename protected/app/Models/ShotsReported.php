<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\ShotsReported;
class ShotsReported extends Model {

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	 
	 protected $table = 'shots_reported';
	 protected $guarded = array();
	 public $timestamps = false;
	
	 public function user(){
		return $this->belongsTo('User')->first();
	}

	public function shots(){
		return $this->belongsTo('Shots')->first();
	}
}
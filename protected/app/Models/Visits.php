<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Visits;
class Visits extends Model {

	protected $guarded = array();
	public $timestamps = false;
	
	public function user() {
        return $this->belongsTo('User')->first();
    }
			
	public function shots(){
		return $this->belongsTo('Shots')->first();
	}

}
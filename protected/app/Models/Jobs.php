<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Jobs;
class Jobs extends Model {

	protected $guarded = array();
	public   $timestamps = false;
		
	public function user() {
        return $this->belongsTo('User')->first();
    }		
}
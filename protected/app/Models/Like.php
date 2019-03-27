<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Like;
class Like extends Model {

	protected $guarded = array();
	public $timestamps = false;
	
	public function user() {
        return $this->belongsTo('User')->first();
    }

}
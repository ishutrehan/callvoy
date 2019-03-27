<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\TeamMembers;
class TeamMembers extends Model {
	
	protected $guarded = array();
	public   $timestamps = false;
		
	public function users() {
        return $this->belongsTo('User')->first();
    }

}
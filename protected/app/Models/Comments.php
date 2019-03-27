<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Comments;
class Comments extends Model {

	protected $guarded = array();
	public $timestamps = false;
		
	public function user() {
        return $this->belongsTo('User')->first();
    }
	
	public function shots(){
		return $this->belongsTo('Shots')->first();
	}
	
	public function total_likes(){
		return DB::table('comments_likes')->where('comment_id', '=', $this->id)->where('status',1)->count();
	}

}
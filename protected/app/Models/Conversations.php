<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Conversations;
class Conversations extends Model {

	protected $guarded = array();
	public $timestamps = false;
		
	public function user() {
        return $this->belongsTo('User')->first();
    }
		
	public function last(){
		return $this->hasMany('Messages','conversation_id')->orderBy('messages.updated_at', 'DESC')->take(1)->first();
	}
	
	public function messages() {
        return $this->hasMany('Messages','conversation_id')->orderBy('messages.updated_at', 'DESC');
    }
	
	public function from() {
        return $this->belongsTo('User', 'from_user_id')->first();
    }
	
	public function to() {
        return $this->belongsTo('User', 'to_user_id')->first();
    }

	

}
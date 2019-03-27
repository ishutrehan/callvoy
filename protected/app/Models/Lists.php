<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use App\Models\Lists;
class Lists extends Model {

	protected $guarded = array();
	public $timestamps = false;
		
	public function user() {
        return $this->belongsTo('User')->first();
    }
	
	public function users() {
        return $this->hasMany('ListsUsers')->orderBy('id','desc');
    }	
	
	public function shots() {
        return $this->hasMany('Shots','id')->orderBy('id','desc');
    }
	
}
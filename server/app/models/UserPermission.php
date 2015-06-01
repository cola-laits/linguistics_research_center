<?php 

class UserPermission extends Eloquent {
	protected $table = 'user_permission';
	
	public function user()
	{
		return $this->belongsTo('User');
	}
}
<?php

// FIXME convert stuff that's here to User.php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class UserL4 extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'user';
	
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password');
	
	/**
	 * Get the user's full name by concatenating the first and last names
	 *
	 * @return string
	 */
	
	public function permissions()
	{
		return $this->hasMany('UserPermission', 'user_id', 'id');
	}
	
	public function getFullName()
	{
		return $this->first_name . ' ' . $this->last_name;
	}
	
	public function isAdmin()
	{
		foreach ($this->permissions as $permission) {
			if ($permission->permission == 'ADMIN') {
				return True;
			}
		}
		return False;
	}
	
	public function seriesAuthorizations()
	{
		$auths = array();
		foreach ($this->permissions as $permission) {
			if ($permission->permission == 'ADMIN') {
				continue;
			}
			$auths[] = $permission->permission;
		}
		return $auths;
	}

}

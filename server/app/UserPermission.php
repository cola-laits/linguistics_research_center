<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\UserPermission
 *
 * @property int $id
 * @property int $user_id
 * @property string|null $permission
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission wherePermission($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\UserPermission whereUserId($value)
 * @mixin \Eloquent
 */
class UserPermission extends Model {
	protected $table = 'user_permission';
	
	public function user()
	{
		return $this->belongsTo('\App\User');
	}
}

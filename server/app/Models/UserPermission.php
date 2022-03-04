<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

/**
 * App\Models\UserPermission
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $created_by
 * @property string|null $updated_by
 * @property int $eieol_series_id
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission query()
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereCreatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereEieolSeriesId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUpdatedBy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|UserPermission whereUserId($value)
 * @mixin \Eloquent
 */
class UserPermission extends Model {
	protected $table = 'user_permission';

	public function user()
	{
		return $this->belongsTo(User::class);
	}
}

<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Carbon;

/**
 * App\Models\UserPermission
 *
 * @property int $id
 * @property int $user_id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property int $eieol_series_id
 * @property-read \App\Models\User $user
 * @method static Builder|UserPermission newModelQuery()
 * @method static Builder|UserPermission newQuery()
 * @method static Builder|UserPermission query()
 * @method static Builder|UserPermission whereCreatedAt($value)
 * @method static Builder|UserPermission whereCreatedBy($value)
 * @method static Builder|UserPermission whereEieolSeriesId($value)
 * @method static Builder|UserPermission whereId($value)
 * @method static Builder|UserPermission whereUpdatedAt($value)
 * @method static Builder|UserPermission whereUpdatedBy($value)
 * @method static Builder|UserPermission whereUserId($value)
 * @property-read \App\Models\EieolSeries|null $eieol_series
 * @mixin Eloquent
 */
class UserPermission extends Model {
    use CrudTrait;
    /*
     * FIXME old table for mapping series edit permissions - replace with Spatie/permissions eventually
     */
	protected $table = 'user_permission';
    protected $fillable = ['user_id','eieol_series_id'];

	public function user()
	{
		return $this->belongsTo(User::class);
	}

    public function eieol_series()
    {
        return $this->belongsTo(EieolSeries::class);
    }
}

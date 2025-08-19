<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Support\Carbon;

class UserPermission extends Model {

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

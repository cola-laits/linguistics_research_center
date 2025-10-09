<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserPermission extends Model
{

    /*
     * FIXME old table for mapping series edit permissions - replace with Spatie/permissions eventually
     */
    protected $table = 'user_permission';
    protected $fillable = ['user_id', 'eieol_series_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function eieol_series(): BelongsTo
    {
        return $this->belongsTo(EieolSeries::class);
    }
}

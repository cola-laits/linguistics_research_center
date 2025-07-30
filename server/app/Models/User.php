<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Notifications\DatabaseNotificationCollection;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\EieolSeries;
use Illuminate\Support\Carbon;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    protected $table = 'user';

    use Notifiable;
    use CrudTrait;
    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $guarded = ['id'];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public function editableSeries() {
        return $this->belongsToMany(EieolSeries::class, 'user_permission', 'user_id', 'eieol_series_id');
    }

    public function isAdmin()
    {
        return $this->hasRole('Site Manager')
            || $this->hasRole('EIEOL Manager')
            || $this->hasRole('Lexicon Manager');
    }
}

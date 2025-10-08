<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    protected $table = 'user';

    use Notifiable;
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

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function editableSeries()
    {
        return $this->belongsToMany(EieolSeries::class, 'user_permission', 'user_id', 'eieol_series_id');
    }

    public function isAdmin()
    {
        return $this->hasRole('Site Manager')
            || $this->hasRole('EIEOL Manager')
            || $this->hasRole('Lexicon Manager');
    }

    public function canAccessPanel($panel): bool
    {
        return true;
    }
}

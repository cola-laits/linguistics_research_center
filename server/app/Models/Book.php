<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $table = 'book';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sections(): HasMany
    {
        return $this->hasMany(BookSection::class)->orderBy('order');
    }
}

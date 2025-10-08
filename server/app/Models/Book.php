<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $table = 'book';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function sections()
    {
        return $this->hasMany(BookSection::class)->orderBy('order');
    }
}

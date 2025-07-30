<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use CrudTrait;
    protected $table = 'book';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    use HasFactory;

    public function sections() {
        return $this->hasMany(BookSection::class)->orderBy('order');
    }
}

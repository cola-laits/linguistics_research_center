<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookSection extends Model
{
    protected $table = 'book_section';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function book()
    {
        return $this->belongsTo(Book::class);
    }
}

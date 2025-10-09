<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BookSection extends Model
{
    protected $table = 'book_section';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}

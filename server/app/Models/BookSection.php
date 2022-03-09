<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookSection extends Model
{
    use \Backpack\CRUD\app\Models\Traits\CrudTrait;
    protected $table = 'book_section';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    use HasFactory;

    public function book() {
        return $this->belongsTo(Book::class);
    }
}

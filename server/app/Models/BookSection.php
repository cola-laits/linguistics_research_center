<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * 
 *
 * @property int $id
 * @property int $book_id
 * @property string $name
 * @property string $slug
 * @property int $order
 * @property string $content
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Models\Book $book
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection query()
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereBookId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereContent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereOrder($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|BookSection whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class BookSection extends Model
{
    use CrudTrait;
    protected $table = 'book_section';

    protected $guarded = ['id', 'created_at', 'updated_at'];

    use HasFactory;

    public function book() {
        return $this->belongsTo(Book::class);
    }
}

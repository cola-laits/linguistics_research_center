<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Backpack\CRUD\app\Models\Traits\SpatieTranslatable\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LexReflexCrossReference extends Model
{
    use CrudTrait;
    use HasFactory;
    use HasTranslations;

    protected $table = 'lex_reflex_cross_reference';
    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $translatable = ['relationship'];

    public function to_reflex() : BelongsTo
    {
        return $this->belongsTo(LexReflex::class, 'to_reflex_id');
    }
}
